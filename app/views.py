from app.models import *
from django.shortcuts import get_object_or_404, redirect, render
from django.contrib import messages
from django.contrib.auth.hashers import check_password, make_password
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.core.mail import send_mail
from django.core.paginator import Paginator
from django.utils.crypto import get_random_string

# Website
def index(request):
    if 'user_id' in request.session:
        return redirect('userpage')
    
    institutions = Institution.objects.all()
    courses = Course.objects.all().order_by('?')[:5]
    feedbacks = Feedback.objects.all().filter(status='show')

    context = {'institutions': institutions, 'courses': courses, 'feedbacks': feedbacks}
    return render(request, 'index.html', context)

def authentication(request):
    if 'user_id' in request.session:
        return redirect('userpage')
    
    return render(request, 'authentication.html')

def signup(request):
    if request.method == "POST":
        name = request.POST.get("name")
        email = request.POST.get("signup-email")

        if User.objects.filter(email=email).exists():
            messages.error(request, "Email already exists.")
            return redirect('user')

        password = make_password(request.POST.get("signup-password"))

        user = User(name=name, email=email, password=password)
        user.save()
        messages.success(request, "Account created successfully.")

        return redirect('authentication')

    return render(request, "userpage.html")

def login(request):
    if 'user_id' in request.session:
        return redirect('userpage')
    
    if request.method == 'POST':
        email = request.POST.get('email')
        password = request.POST.get('login-password')

        try:
            user = User.objects.get(email=email)
        except User.DoesNotExist:
            messages.error(request, "Email does not exist.")
            return render(request, 'authentication.html')

        if not check_password(password, user.password):
            messages.error(request, "Invalid password.")
            return render(request, 'authentication.html')

        if user.status != 'active':
            messages.error(request, "Your account is suspended. Please contact support.")
            return render(request, 'authentication.html')

        request.session['user_id'] = user.id
        request.session.set_expiry(7200) 
        return redirect('userpage')

    return render(request, 'authentication.html')

def logout(request):
    keys_to_remove = ['user_id']

    for key in keys_to_remove:
        if key in request.session:
            del request.session[key]
    
    return redirect('index')

def about_us(request):
    feedbacks = Feedback.objects.filter(status='show').order_by('?')[:5]
    return render(request, 'about.html', {'feedbacks': feedbacks})

def institution_details(request, id):
    try:
        institution = Institution.objects.get(id=id)  # Get the institution by ID
        # Get related images using the related_name ('images') on the InstitutionImage model
        gallery_images = institution.images.all()

        offered_courses = InstitutionCourse.objects.filter(institution=institution)
        
        context = {'institution': institution, 'gallery_images': gallery_images, 'offered_courses': offered_courses}
        return render(request, 'institution_details.html', context)
    except Institution.DoesNotExist:
        return render(request, '404.html', {'error': 'Institution not found'})
    
def course_details(request, id):
    try:
        course = Course.objects.get(id=id)
        courses = Course.objects.all().order_by('?')[:5]
        offering_institutions = InstitutionCourse.objects.filter(course=course)

        prospect_careers = course.Prospect_Career.split(',') if course.Prospect_Career else []
        prospect_careers = [Prospect_Career.strip() for Prospect_Career in prospect_careers]  # Strip extra space
        
        context = {'course': course, 'courses': courses, 'prospect_careers': prospect_careers, 'offering_institutions': offering_institutions}
        return render(request, 'course_details.html', context)
    except Course.DoesNotExist:
        return render(request, '404.html', {'error': 'Institution not found'})
    
# User
def userpage(request):
    if 'user_id' not in request.session:
        return redirect('authentication')
    
    user_id = request.session.get('user_id')
    user = User.objects.get(id=user_id)

    institutions = Institution.objects.all()

    context = {'institutions': institutions, 'user': user}
    return render(request, 'userpage.html', context)

def profile(request):
    if 'user_id' not in request.session:
        return redirect('authentication')
    
    user_id = request.session.get('user_id')
    user = User.objects.get(id=user_id)

    PROVINCES = [
        ('province_1', 'Province No. 1'),
        ('province_2', 'Province No. 2'),
        ('bagmati', 'Bagmati Province'),
        ('gandaki', 'Gandaki Province'),
        ('lumbini', 'Lumbini Province'),
        ('karnali', 'Karnali Province'),
        ('sudurpashchim', 'Sudurpashchim Province'),
    ]

    context = {'user': user, 'provinces': PROVINCES}
    return render(request, 'profile.html', context)

def update_profile(request, id):
    if 'user_id' not in request.session:
        return redirect('authentication')
    
    user = get_object_or_404(User, id=id)

    if request.method == 'POST':
        user.name = request.POST.get('name')
        user.email = request.POST.get('email')
        user.phone = request.POST.get('phone')
        
        province = request.POST.get('province')
        if province:
            user.province = province

        user.save()
        
        messages.success(request, 'Profile details updated successfully.')
        return redirect('profile')
    
    return redirect('profile')

def institutions(request):
    institutions = Institution.objects.all().order_by('name')
    return render(request, 'institutions.html', {'institutions': institutions})

def courses(request):
    courses = Course.objects.all().order_by('name')
    return render(request, 'courses.html', {'courses': courses})

def applications(request):
    if 'user_id' not in request.session:
        return redirect('authentication')
    
    user_id = request.session.get('user_id')
    user = User.objects.get(id=user_id)

    institutions = Institution.objects.filter(admission=True).order_by('name')
    applications = Application.objects.all().order_by('-id')

    context = {'institutions': institutions, 'applications': applications, 'user': user}
    return render(request, 'applications.html', context)

def send_application(request):
    # Check if the user is authenticated
    if 'user_id' not in request.session:
        return redirect('authentication')  # Redirect to login/authentication if user is not logged in

    user_id = request.session.get('user_id')
    user = User.objects.get(id=user_id)

    if request.method == 'POST':
        # Retrieve form data
        name = request.POST.get('name')
        email = request.POST.get('email')
        phone = request.POST.get('phone')
        institution_id = request.POST.get('institution')
        program_id = request.POST.get('program')
        query = request.POST.get('query', '')  # Optional field

        # Validate input
        if not institution_id or not program_id:
            messages.error(request, "Please select both an institution and a program.")
            return redirect('applications')  # Replace with the name of the form view

        # Get the related institution and program objects
        institution = get_object_or_404(Institution, id=institution_id)
        program = get_object_or_404(InstitutionCourse, id=program_id)

        # Check if the user has already applied for the same institution and program
        existing_application = Application.objects.filter(
            user=user, institution=institution, program=program
        ).exists()

        if existing_application:
            messages.warning(request, "You have already applied to this program at this institution.")
            return redirect('applications')  # Replace with the name of the form view

        # Create and save the application
        application = Application(
            user=user,
            institution=institution,
            program=program,
            phone=phone,
            email=email,
            query=query,
        )
        application.save()

        messages.success(request, "Your application has been successfully submitted!")
        return redirect('applications')  # Redirect to a success page

    # If not a POST request, redirect to the application form
    institutions = Institution.objects.all()  # Fetch all institutions for the form
    context = {'user': user, 'institutions': institutions}
    return render(request, 'applications.html', context)

def feedbacks(request):
    if 'user_id' not in request.session:
        return redirect('authentication')
    
    user_id = request.session.get('user_id')
    user = User.objects.get(id=user_id)

    feedback_list = Feedback.objects.filter(user=user).order_by('-id')
    paginator = Paginator(feedback_list, 5)

    page_number = request.GET.get('page')
    page_obj = paginator.get_page(page_number)

    context = {'page_obj': page_obj, 'user': user}
    return render(request, 'feedbacks.html', context)

def send_feedback(request):
    if 'user_id' not in request.session:
        return redirect('authentication')
    
    user_id = request.session.get('user_id')
    user = User.objects.get(id=user_id)

    if request.method == 'POST':
        email = request.POST.get('email')
        phone = request.POST.get('phone')
        review = request.POST.get('review')

        feedback = Feedback(user=user, email=email, phone=phone, review=review)
        feedback.save()
        messages.success(request, 'Feedback sent successfully.')

        return redirect('feedbacks')

    return render(request, 'feedbacks.html', {'user': user})

# Institution
def institution_authentication(request):
    if 'institution_id' in request.session:
        return redirect('institution-dashboard')
    
    return render(request, 'institution_authentication.html')

def institution_signup(request):
    if request.method == "POST":
        name = request.POST.get("name")
        institution = request.POST.get("institution")
        email = request.POST.get("signup-email")

        if InstitutionAdmin.objects.filter(email=email).exists():
            messages.error(request, "Email already exists.")
            return redirect('institution-authentication')
            
        if InstitutionAdmin.objects.filter(institution=institution).exists():
            messages.error(request, "Institution already exists.")
            return redirect('institution-authentication')

        password = make_password(request.POST.get("signup-password"))

        user = InstitutionAdmin(name=name, institution=institution, email=email, password=password)
        user.save()
        messages.success(request, "Account created successfully.")

        return redirect('institution-authentication')

    return render(request, "institution_authentication.html")

def institution_login(request):
    if 'institution_id' in request.session:
        return redirect('institution-dashboard')
    
    if request.method == 'POST':
        email = request.POST.get('email')
        password = request.POST.get('login-password')

        try:
            institution = InstitutionAdmin.objects.get(email=email)
        except InstitutionAdmin.DoesNotExist:
            messages.error(request, "Email does not exist.")
            return render(request, 'institution_authentication.html')

        if not check_password(password, institution.password):
            messages.error(request, "Invalid password.")
            return render(request, 'institution_authentication.html')

        if institution.status != 'approved':
            messages.error(request, "Your account is in verification phase.")
            return render(request, 'institution_authentication.html')

        request.session['institution_id'] = institution.id
        request.session.set_expiry(7200) 
        return redirect('institution-dashboard')

    return render(request, 'institution_authentication.html')

def institution_logout(request):
    # Check if the institution is logged in
    if 'institution_id' in request.session:
        del request.session['institution_id']
    
    # Redirect to the institution authentication page after logging out
    return redirect('institution-authentication')

def institution_dashboard(request):
    if 'institution_id' not in request.session:
        return redirect('institution-authentication')

    institution_admin_id = request.session.get('institution_id')
    try:
        institution_admin = InstitutionAdmin.objects.get(id=institution_admin_id)
        institution = institution_admin.managed_institution
    except InstitutionAdmin.DoesNotExist:
        return redirect('institution-authentication')

    offered_courses_count = InstitutionCourse.objects.filter(institution=institution).count()
    admissions_count = Application.objects.filter(institution=institution, status='accepted').count()

    # Access the last_admissions field correctly
    last_admissions = institution.last_admissions
    current_admissions = admissions_count

    # Avoid division by zero and calculate the percentage change
    if last_admissions != 0:
        percentage_change = ((current_admissions - last_admissions) / last_admissions) * 100
    else:
        percentage_change = 0  # Handle case where last_admissions is 0 (no data for last admissions)

    percentage_change = round(percentage_change, 1)

    # Determine if the change is positive or negative
    if percentage_change > 0:
        change_type = "increase"
    elif percentage_change < 0:
        change_type = "decrease"
    else:
        change_type = "no change"

    context = {'institution': institution, 'offered_courses_count': offered_courses_count, 'admissions_count': admissions_count, 'percentage_change': percentage_change, 'change_type': change_type}
    return render(request, 'institution_dashboard.html', context)

def institution_profile(request):
    if 'institution_id' not in request.session:
        return redirect('institution-authentication')
    
    institution_id = request.session.get('institution_id')
    institution = InstitutionAdmin.objects.get(id=institution_id)
        
    AFFILIATION_CHOICES = [
        ('tribhuvan', 'Tribhuvan University'),
        ('pokhara', 'Pokhara University'),
        ('kathmandu', 'Kathmandu University'),
        ('gandaki', 'Gandaki University'),
        ('purbanchal', 'Purbanchal University'),
        ('foreign', 'Foreign University'),
    ]

    # admin_institution = institution.managed_institution
    admin_institution = Institution.objects.filter(admin=institution).first()

    if admin_institution:
        return render(request, 'institution_profile.html', {'affiliation_choices': AFFILIATION_CHOICES, 'institution': institution, 'admin_institution': admin_institution, 'edit_mode': True})
    else:
        return render(request, 'institution_profile.html', {'affiliation_choices': AFFILIATION_CHOICES, 'institution': institution, 'edit_mode': False})

def add_institution(request):
    if 'institution_id' not in request.session:
        return redirect('institution-authentication')
    
    institution_id = request.session.get('institution_id')
    institution = InstitutionAdmin.objects.get(id=institution_id)

    if request.method == 'POST':
        name = request.POST.get('name')
        affiliation = request.POST.get('affiliation')
        foreign_university_name = request.POST.get('foreign_university_name')
        program = request.POST.get('program')
        overview = request.POST.get('overview')
        message = request.POST.get('message')
        phone = request.POST.get('phone')
        email = request.POST.get('email')
        website = request.POST.get('website')
        address = request.POST.get('address')
        map = request.POST.get('map')
        logo = request.FILES.get('file-upload')

        institution = Institution(name=name, affiliation=affiliation, Foreign_University_Name=foreign_university_name, program=program, overview=overview, message=message, phone=phone, email=email, website=website, address=address, map=map, logo=logo, admin=institution)
        institution.save()
        messages.success(request, 'Institution added successfully.')

        return redirect('institution-profile')
    
def update_institution(request, institution_id):
    institution = get_object_or_404(Institution, id=institution_id)

    if request.method == 'POST':
        if 'file-upload' in request.FILES:
            logo = request.FILES['file-upload']
            institution.logo = logo

        # Get the form data and update the institution fields
        affiliation = request.POST.get('affiliation')
        if affiliation == 'foreign':
            foreign_university_name = request.POST.get('foreign_university_name')
            institution.Foreign_University_Name = foreign_university_name
        else:
            institution.Foreign_University_Name = None

        institution.name = request.POST.get('name')
        institution.affiliation = affiliation
        institution.email = request.POST.get('email')
        institution.phone = request.POST.get('phone')
        institution.website = request.POST.get('website')
        institution.address = request.POST.get('address')
        institution.overview = request.POST.get('overview')
        institution.message = request.POST.get('message')
        institution.program = request.POST.get('program')
        institution.map = request.POST.get('map')
        institution.save()

        messages.success(request, 'Institution details updated successfully.')
        return redirect('institution-profile')

    return render(request, 'institution_profile.html', {'institution': institution, 'edit_mode': True, 'affiliation_choices': Institution.AFFILIATION_CHOICES,})

def programs(request):
    if 'institution_id' not in request.session:
        return redirect('institution-authentication')

    institution_admin_id = request.session.get('institution_id')
    try:
        institution_admin = InstitutionAdmin.objects.get(id=institution_admin_id)
        institution = institution_admin.managed_institution
    except InstitutionAdmin.DoesNotExist:
        return redirect('institution-authentication')

    courses = Course.objects.all().order_by('name')
    offered_courses = InstitutionCourse.objects.filter(institution=institution).order_by('course__name')

    context = {'institution': institution, 'courses': courses, 'offered_courses': offered_courses}
    return render(request, 'programs.html', context)

def add_offered_course(request):
    if 'institution_id' not in request.session:
        return redirect('institution-authentication')
    
    institution_admin_id = request.session.get('institution_id')
    try:
        institution_admin = InstitutionAdmin.objects.get(id=institution_admin_id)
    except InstitutionAdmin.DoesNotExist:
        return redirect('institution-authentication')

    institution = institution_admin.managed_institution
    
    if request.method == 'POST':
        course_id = request.POST.get('course')
        details = request.POST.get('overview')

        try:
            course = Course.objects.get(id=course_id)
        except Course.DoesNotExist:
            messages.error(request, "Invalid course selection.")
            return redirect('programs')

        if InstitutionCourse.objects.filter(institution=institution, course=course).exists():
            messages.error(request, "This course is already offered by this institution.")
            return redirect('programs')

        InstitutionCourse.objects.create(institution=institution, course=course, details=details)

        messages.success(request, f"{course.name} has been successfully added to {institution.name}.")
        return redirect('programs')
    
    courses = Course.objects.all().order_by('name')

    context = {'institution': institution, 'courses': courses}
    return render(request, 'programs.html', context)

def edit_offered_course(request, institution_course_id):
    try:
        offered_course = InstitutionCourse.objects.get(id=institution_course_id)
    except InstitutionCourse.DoesNotExist:
        return redirect('programs')

    return render(request, 'edit_offered_course.html', {'offered_course': offered_course})

def update_offered_course(request, institution_course_id):
    if 'institution_id' not in request.session:
        return redirect('institution-authentication')

    offered_course = get_object_or_404(InstitutionCourse, id=institution_course_id)

    if request.method == 'POST':
        offered_course.details = request.POST.get('details', '').strip()
        offered_course.save()
        messages.success(request, "Course details updated successfully.")
        return redirect('edit-offered-course', institution_course_id=institution_course_id)

    return redirect('edit-offered-course', institution_course_id=institution_course_id)

def admission(request):
    if 'institution_id' not in request.session:
        return redirect('institution-authentication')
    
    institution_id = request.session.get('institution_id')
    institution = InstitutionAdmin.objects.get(id=institution_id)

    admissions = Application.objects.all().order_by('-id')

    context = {'institutions': institutions, 'admissions': admissions, 'institution': institution}
    return render(request, 'admission.html', context)
    
# Admin
def admin_authentication(request):
    if 'admin_id' in request.session:
        return redirect('dashboard')
    
    return render(request, 'admin_authentication.html')

def admin_login(request):
    if 'admin_id' in request.session:
        return redirect('dashboard')
    
    if request.method == 'POST':
        email = request.POST.get('email')
        password = request.POST.get('login-password')

        try:
            admin = SuperAdmin.objects.get(email=email)
        except SuperAdmin.DoesNotExist:
            messages.error(request, "Email does not exist.")
            return render(request, 'admin_authentication.html')

        if not check_password(password, admin.password):
            messages.error(request, "Invalid password.")
            return render(request, 'admin_authentication.html')

        if admin.status != 'active':
            messages.error(request, "Your account is suspended. Please contact support.")
            return render(request, 'admin_authentication.html')

        request.session['admin_id'] = admin.id
        request.session.set_expiry(7200) 
        return redirect('dashboard')

    return render(request, 'admin_authentication.html')

def admin_signup(request):
    if request.method == "POST":
        name = request.POST.get("name")
        role = request.POST.get("role")
        phone = request.POST.get("phone")
        email = request.POST.get("email")
        password = request.POST.get("password")

        if SuperAdmin.objects.filter(phone=phone).exists():
            messages.error(request, "Phone number already exists.")
            return redirect('system-user')
        
        if SuperAdmin.objects.filter(email=email).exists():
            messages.error(request, "Email already exists.")
            return redirect('system-user')

        password = make_password(request.POST.get("signup-password"))

        admin = SuperAdmin(name=name, role=role, phone=phone, email=email, password=password)
        admin.save()
        messages.success(request, "Account created successfully.")

        return redirect('system-user')

    return render(request, "system_user.html")

def admin_logout(request):
    if 'admin_id' in request.session:
        del request.session['admin_id']
    
    return redirect('admin-authentication')

def dashboard(request):
    if 'admin_id' not in request.session:
        return redirect('admin-authentication')
    
    admin_id = request.session.get('admin_id')
    admin = SuperAdmin.objects.get(id=admin_id)
    
    return render(request, 'dashboard.html', {'admin': admin})

def system_user(request):
    ROLES = [
        ('admin', 'Admin'),
        ('staff', 'Staff'),
    ]

    system_users = SuperAdmin.objects.all().order_by('name')

    context = {'roles':ROLES, 'system_users':system_users}
    return render(request, 'system_user.html', context)

def user(request):
    users = User.objects.all().order_by('-id')
    return render(request, 'user.html', {'users': users})

def institution(request):
    institutions = Institution.objects.all().select_related('admin').order_by('-id')
    return render(request, 'institution.html', {'institutions': institutions})

def course(request):
    FIELDS = [
        ('engineering', 'Engineering'),
        ('cit', 'Computer and Information Technology'),
        ('management', 'Management'),
        ('st', 'Science and Technology'),
        ('medicine', 'Medicine'),
        ('law', 'Law')
    ]

    LEVELS = [
        ('bachelor', 'Bachelor'),
        ('master', 'Master')
    ]

    AFFILIATION_CHOICES = [
        ('tribhuvan', 'Tribhuvan University'),
        ('pokhara', 'Pokhara University'),
        ('kathmandu', 'Kathmandu University'),
        ('gandaki', 'Gandaki University'),
        ('purbanchal', 'Purbanchal University'),
        ('foreign', 'Foreign University'),
    ]

    courses = Course.objects.all().order_by('-id')
    institutions = Institution.objects.all()

    context = {'fields': FIELDS, 'levels': LEVELS, 'affiliation_choices': AFFILIATION_CHOICES, 'courses': courses, 'institutions': institutions}
    return render(request, 'course.html', context)

def add_course(request):
    if request.method == "POST":
        name = request.POST.get('name')
        abbreviation = request.POST.get('abbreviation')
        year = request.POST.get('year')
        field = request.POST.get('field')
        level = request.POST.get('level')
        affiliation = request.POST.get('affiliation')
        foreign_university_name = request.POST.get('foreign_university_name')
        about = request.POST.get('about')
        eligibility = request.POST.get('eligibility')
        admission_criteria = request.POST.get('admission_criteria')
        job_prospect = request.POST.get('job_prospect')
        prospect_career = request.POST.get('prospect_career')

        course = Course(name=name, abbreviation=abbreviation, year=year, field=field, level=level, affiliation=affiliation, Foreign_University_Name=foreign_university_name, about=about,
            eligibility=eligibility, Admission_Criteria=admission_criteria, Job_Prospect=job_prospect, Prospect_Career=prospect_career)
        course.save()
        
        return redirect("course")

    institutions = Institution.objects.all()
    return render(request, "course.html", {"institutions": institutions})

def edit_course(request, course_id):
    course = get_object_or_404(Course, id=course_id)
    institutions = Institution.objects.all()

    FIELDS = [
        ('engineering', 'Engineering'),
        ('cit', 'Computer and Information Technology'),
        ('management', 'Management'),
        ('st', 'Science and Technology'),
        ('medicine', 'Medicine'),
        ('law', 'Law')
    ]

    LEVELS = [
        ('bachelor', 'Bachelor'),
        ('master', 'Master')
    ]

    AFFILIATION_CHOICES = [
        ('tribhuvan', 'Tribhuvan University'),
        ('pokhara', 'Pokhara University'),
        ('kathmandu', 'Kathmandu University'),
        ('gandaki', 'Gandaki University'),
        ('purbanchal', 'Purbanchal University'),
        ('foreign', 'Foreign University'),
    ]

    context = {'course': course, 'institutions': institutions, 'fields': FIELDS, 'levels': LEVELS, 'affiliation_choices': AFFILIATION_CHOICES}
    return render(request, 'edit_course.html', context)

def update_course(request, course_id):
    course = get_object_or_404(Course, id=course_id)

    if request.method == 'POST':
        affiliation = request.POST.get('affiliation')
        if affiliation == 'foreign':
            foreign_university_name = request.POST.get('foreign_university_name')
            course.Foreign_University_Name = foreign_university_name
        else:
            course.Foreign_University_Name = None

        course.name = request.POST.get('name')
        course.affiliation = affiliation
        course.abbreviation = request.POST.get('abbreviation')
        course.year = request.POST.get('year')
        course.field = request.POST.get('field')
        course.level = request.POST.get('level')
        course.about = request.POST.get('about')
        course.eligibility = request.POST.get('eligibility')
        course.Admission_Criteria = request.POST.get('admission_criteria')
        course.Job_Prospect = request.POST.get('job_prospect')
        course.Prospect_Career = request.POST.get('prospect_career')
        offered_by_ids = request.POST.getlist('offered_by')
        if offered_by_ids:
            course.Offered_by.set(offered_by_ids)
        course.save()

        messages.success(request, 'Course details updated successfully.')
        return redirect('edit-course', course_id=course.id)

    return render(request, 'edit_course.html', {'course': course})

def feedback(request):
    if 'admin_id' not in request.session:
        return redirect('admin-authentication')
    
    feedbacks = Feedback.objects.all().order_by('-id')
    return render(request, 'feedback.html', {'feedbacks': feedbacks})

# Ajax
# Update the status of an institution admin account
@csrf_exempt
def update_status(request, institution_id):
    if request.method == 'POST':
        status = request.POST.get('status')
        try:
            institution = InstitutionAdmin.objects.get(id=institution_id)
            institution.status = status
            institution.save()
            return JsonResponse({'success': True, 'message': 'Status updated successfully.'})
        except InstitutionAdmin.DoesNotExist:
            return JsonResponse({'success': False, 'message': 'Institution not found.'}, status=404)
    return JsonResponse({'success': False, 'message': 'Invalid request method.'}, status=400)

# Pass dynamic courses
def get_courses(request, institution_id):
    courses = InstitutionCourse.objects.filter(institution_id=institution_id).select_related('course')
    course_data = [{'id': course.course.id, 'name': course.course.name} for course in courses]
    return JsonResponse({'courses': course_data})

def password_setting(request):
    return render(request, 'request_otp.html')

def change_setting(request):
    return render(request, 'change_password.html')

def send_otp(email):
    user = None

    if SuperAdmin.objects.filter(email=email).exists():
        user = SuperAdmin.objects.get(email=email)
    elif User.objects.filter(email=email).exists():
        user = User.objects.get(email=email)
    elif InstitutionAdmin.objects.filter(email=email).exists():
        user = InstitutionAdmin.objects.get(email=email)
    
    if user:
        # Generate an OTP
        otp = get_random_string(length=6, allowed_chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')
        
        OTP.objects.create(email=email, otp=otp)

        send_mail(
            'Your OTP for password change',
            f'Your OTP to change your password is: {otp}',
            'zaynierashik@gmail.com',  # Replace with your own email
            [email],
            fail_silently=False,
        )
        return True
    return False

def request_otp(request):
    if request.method == 'POST':
        email = request.POST.get('email')
        
        if send_otp(email):
            return render(request, 'change_password.html', {'message': 'OTP sent to your email. Please check the inbox.'})
        else:
            return render(request, 'request_otp.html', {'error': 'Email not found. Please check and try again.'})

def change_user_password(request):
    if request.method == 'POST':
        email = request.POST.get('email')
        otp = request.POST.get('otp')
        new_password = request.POST.get('new_password')
        
        success, message = change_password(email, otp, new_password)
        
        if success:
            return render(request, 'change_password.html', {"message": message})
        else:
            return render(request, 'change_password.html', {"message": message})

def change_password(email, otp, new_password):
    if SuperAdmin.objects.filter(email=email).exists():
        user = SuperAdmin.objects.get(email=email)
    elif User.objects.filter(email=email).exists():
        user = User.objects.get(email=email)
    elif InstitutionAdmin.objects.filter(email=email).exists():
        user = InstitutionAdmin.objects.get(email=email)
    else:
        return False, "Email not found"
    
    try:
        otp_entry = OTP.objects.get(email=email, otp=otp)
    except OTP.DoesNotExist:
        return False, "Invalid OTP"
    
    if not otp_entry.is_valid():
        return False, "OTP has expired"
    
    user.password = make_password(new_password)
    user.save()
    
    otp_entry.delete()
    
    return True, "Password changed successfully"

# Test Views
# Getting application count and resetting admission count
def update_application_status(request, application_id):
    """Approve or reject an application and track admissions."""
    application = get_object_or_404(Application, id=application_id)

    if request.method == "POST":
        new_status = request.POST.get("status")

        if new_status in ['accepted', 'rejected', 'pending']:
            application.status = new_status
            application.save()
            messages.success(request, f"Application status updated to {new_status.capitalize()}!")

    return redirect('institution-dashboard')

def reset_admission_count(request, institution_id):
    """Reset admission count for a new academic period."""
    institution = get_object_or_404(Institution, id=institution_id)
    
    if request.method == "POST":
        institution.reset_admissions()
        messages.success(request, "Admissions count has been reset for the new period.")

    return redirect('institution-dashboard')
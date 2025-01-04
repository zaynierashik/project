from app.models import *
from django.shortcuts import get_object_or_404, redirect, render
from django.contrib import messages
from django.contrib.auth.hashers import check_password, make_password
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt

# Website
def index(request):
    institutions = Institution.objects.all()
    feedbacks = Feedback.objects.all().filter(status='show')

    context = {'institutions': institutions, 'feedbacks': feedbacks}
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

        # Split the programs into a list
        programs = institution.program.split(',') if institution.program else []
        programs = [program.strip() for program in programs]  # Strip extra spaces
        
        return render(
            request, 
            'institution_details.html', 
            {'institution': institution, 'programs': programs, 'gallery_images': gallery_images}
        )
    except Institution.DoesNotExist:
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

    return render(request, 'profile.html', {'user': user})

def institutions(request):
    institutions = Institution.objects.all().order_by('-id')
    return render(request, 'institutions.html', {'institutions': institutions})

def courses(request):
    return render(request, 'courses.html',)

def applications(request):
    if 'user_id' not in request.session:
        return redirect('authentication')
    
    user_id = request.session.get('user_id')
    user = User.objects.get(id=user_id)

    institutions = Institution.objects.all().order_by('name')
    feedbacks = Feedback.objects.all().order_by('-id')

    context = {'institutions': institutions, 'feedbacks': feedbacks, 'user': user}
    return render(request, 'applications.html', context)

def feedbacks(request):
    if 'user_id' not in request.session:
        return redirect('authentication')
    
    user_id = request.session.get('user_id')
    user = User.objects.get(id=user_id)

    feedbacks = Feedback.objects.all().order_by('-id')

    context = {'feedbacks': feedbacks, 'user': user}
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
        return redirect('dashboard')
    
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
    return render(request, 'institution_dashboard.html')

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

        # Update the rest of the fields
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

        # Save the updated institution object
        institution.save()

        # Display a success message
        messages.success(request, 'Institution details updated successfully.')

        # Redirect to the institution profile page or any other page you need
        return redirect('institution-profile')

    # If it's a GET request, render the form pre-filled with institution data
    return render(request, 'institution_profile.html', {
        'institution': institution,
        'edit_mode': True,
        'affiliation_choices': Institution.AFFILIATION_CHOICES,
    })

def programs(request):
    return render(request, 'programs.html')
    
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
        field = request.POST.get('field')
        level = request.POST.get('level')
        affiliation = request.POST.get('affiliation')
        foreign_university_name = request.POST.get('foreign_university_name')
        about = request.POST.get('about')
        eligibility = request.POST.get('eligibility')
        admission_criteria = request.POST.get('admission_criteria')
        job_prospect = request.POST.get('job_prospect')
        prospect_career = request.POST.get('prospect_career')
        offered_by_ids = request.POST.getlist('offered_by')

        offered_by_ids = [institution_id for institution_id in offered_by_ids if institution_id]

        course = Course(name=name, abbreviation=abbreviation, field=field, level=level, affiliation=affiliation, Foreign_University_Name=foreign_university_name, about=about,
            eligibility=eligibility, Admission_Criteria=admission_criteria, Job_Prospect=job_prospect, Prospect_Career=prospect_career)
        course.save()

        if offered_by_ids:
            institutions = Institution.objects.filter(id__in=offered_by_ids)
            course.Offered_by.set(institutions)
        
        return redirect("course")

    institutions = Institution.objects.all()
    return render(request, "course.html", {"institutions": institutions})

def feedback(request):
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
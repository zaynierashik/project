from app.models import *
from django.shortcuts import redirect, render
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

    return render(request, 'authentication.html')

def institution_dashboard(request):
    return render(request, 'institution_dashboard.html')

def institution_profile(request):
    AFFILIATION_CHOICES = [
        ('tribhuvan', 'Tribhuvan University'),
        ('pokhara', 'Pokhara University'),
        ('kathmandu', 'Kathmandu University'),
        ('gandaki', 'Gandaki University'),
        ('purbanchal', 'Purbanchal University'),
        ('foreign', 'Foreign University'),
    ]

    institution = Institution.objects.get(id=2)

    context = {'affiliation_choices': AFFILIATION_CHOICES, 'institution': institution}
    return render(request, 'institution_profile.html', context)
    
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
    # Check if the admin is logged in
    if 'admin_id' in request.session:
        del request.session['admin_id']
    
    # Redirect to the admin authentication page after logging out
    return redirect('admin_authentication')

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
    institutions = InstitutionAdmin.objects.all().order_by('-id')
    return render(request, 'institution.html', {'institutions': institutions})

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
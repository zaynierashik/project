from django.shortcuts import redirect, render
from django.contrib import messages
from django.contrib.auth.hashers import check_password, make_password
from app.models import *

# Website
def index(request):
    institutions = Institution.objects.all()

    context = {
        'institutions': institutions,
    }
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
    return render(request, 'about.html')

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
    institutions = Institution.objects.all()
    return render(request, 'userpage.html', {'institutions': institutions})

def profile(request):
    if 'user_id' not in request.session:
        return redirect('authentication')
    
    user_id = request.session.get('user_id')
    user = User.objects.get(id=user_id)

    return render(request, 'profile.html', {'user': user})

def institutions(request):
    institutions = Institution.objects.all().order_by('-id')
    return render(request, 'institutions.html', {'institutions': institutions})

def admissions(request):
    if 'user_id' not in request.session:
        return redirect('authentication')
    
    user_id = request.session.get('user_id')
    user = User.objects.get(id=user_id)

    institutions = Institution.objects.all().order_by('name')
    feedbacks = Feedback.objects.all().order_by('-id')

    context = {'institutions': institutions, 'feedbacks': feedbacks, 'user': user}
    return render(request, 'admissions.html', context)
    
# Admin
def dashboard(request):
    return render(request, 'dashboard.html')

def user(request):
    users = User.objects.all().order_by('-id')
    return render(request, 'user.html', {'users': users})

def institution(request):
    institutions = Institution.objects.all().order_by('-id')
    return render(request, 'institution.html', {'institutions': institutions})

def feedback(request):
    feedbacks = Feedback.objects.all().order_by('-id')
    return render(request, 'feedback.html', {'feedbacks': feedbacks})
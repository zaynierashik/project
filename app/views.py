from django.shortcuts import render
from app.models import *

def index(request):
    institutions = Institution.objects.all()

    context = {
        'institutions': institutions,
    }
    return render(request, 'index.html', context)

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

def dashboard(request):
    return render(request, 'dashboard.html')

def user(request):
    users = User.objects.all().order_by('-id')
    return render(request, 'user.html', {'users': users})

def institution(request):
    institutions = Institution.objects.all().order_by('-id')
    return render(request, 'institution.html', {'institutions': institutions})

  # Handle missing institutions gracefully

def feedback(request):
    feedbacks = Feedback.objects.all().order_by('-id')
    return render(request, 'feedback.html', {'feedbacks': feedbacks})
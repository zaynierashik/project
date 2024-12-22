from django.urls import path
from app import views

urlpatterns = [
    # Admin
    path('', views.index, name='index'),
    path('dashboard/', views.dashboard, name='dashboard'),
    path('user/', views.user, name='user'),
    path('institution/', views.institution, name='institution'),
    path('feedback/', views.feedback, name='feedback'),

    # User
    path('about-us/', views.about_us, name='about-us'),
    path('institution-details/<int:id>', views.institution_details, name='institution-details'),
]
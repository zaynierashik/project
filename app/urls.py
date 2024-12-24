from django.urls import path
from app import views

urlpatterns = [
    # Admin
    path('', views.index, name='index'),
    path('dashboard/', views.dashboard, name='dashboard'),
    path('user/', views.user, name='user'),
    path('institution/', views.institution, name='institution'),
    path('feedback/', views.feedback, name='feedback'),

    # Website
    path('authentication/', views.authentication, name='authentication'),
    path('signup/', views.signup, name='signup'),
    path('login/', views.login, name='login'),
    path('logout/', views.logout, name='logout'),
    path('about-us/', views.about_us, name='about-us'),
    path('institution-details/<int:id>', views.institution_details, name='institution-details'),

    # User
    path('userpage/', views.userpage, name='userpage'),
    path('profile/', views.profile, name='profile'),
    path('institutions/', views.institutions, name='institutions'),
    path('admissions/', views.admissions, name='admissions'),
]
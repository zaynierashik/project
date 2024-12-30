from django.urls import path
from app import views

urlpatterns = [
    path('', views.index, name='index'),

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
    path('courses/', views.courses, name='courses'),
    path('applications/', views.applications, name='applications'),
    path('feedbacks/', views.feedbacks, name='feedbacks'),

    path('send-feedback/', views.send_feedback, name='send-feedback'),

    # Institution
    path('institution-authentication', views.institution_authentication, name='institution-authentication'),
    path('institution-signup', views.institution_signup, name='institution-signup'),
    path('institution-login', views.institution_login, name='institution-login'),
    path('institution-dashboard/', views.institution_dashboard, name='institution-dashboard'),
    path('institution-profile/', views.institution_profile, name='institution-profile'),

    # Admin
    path('admin-authentication', views.admin_authentication, name='admin-authentication'),
    path('admin-login', views.admin_login, name='admin-login'),
    path('admin-logout', views.admin_logout, name='admin-logout'),
    path('dashboard/', views.dashboard, name='dashboard'),
    path('user/', views.user, name='user'),
    path('institution/', views.institution, name='institution'),
    path('feedback/', views.feedback, name='feedback'),

    # Ajax
    path('update-status/<int:institution_id>/', views.update_status, name='update-status'),
]
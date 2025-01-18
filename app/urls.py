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
    path('course-details/<int:id>', views.course_details, name='course-details'),

    # User
    path('userpage/', views.userpage, name='userpage'),
    path('profile/', views.profile, name='profile'),
    path('institutions/', views.institutions, name='institutions'),
    path('courses/', views.courses, name='courses'),
    path('applications/', views.applications, name='applications'),
    path('feedbacks/', views.feedbacks, name='feedbacks'),

    path('send-feedback/', views.send_feedback, name='send-feedback'),

    # Institution
    path('institution-authentication/', views.institution_authentication, name='institution-authentication'),
    path('institution-signup/', views.institution_signup, name='institution-signup'),
    path('institution-login/', views.institution_login, name='institution-login'),
    path('institution-logout/', views.institution_logout, name='institution-logout'),
    path('institution-dashboard/', views.institution_dashboard, name='institution-dashboard'),
    path('institution-profile/', views.institution_profile, name='institution-profile'),
    path('programs/', views.programs, name='programs'),

    path('add-institution/', views.add_institution, name='add-institution'),
    path('update-institution/<int:institution_id>/', views.update_institution, name='update-institution'),

    # Admin
    path('admin-authentication/', views.admin_authentication, name='admin-authentication'),
    path('admin-signup/', views.admin_signup, name='admin-signup'),
    path('admin-login/', views.admin_login, name='admin-login'),
    path('admin-logout/', views.admin_logout, name='admin-logout'),
    path('dashboard/', views.dashboard, name='dashboard'),
    path('system-user/', views.system_user, name='system-user'),
    path('user/', views.user, name='user'),
    path('institution/', views.institution, name='institution'),
    path('course/', views.course, name='course'),
    path('feedback/', views.feedback, name='feedback'),
    
    path('add-course/', views.add_course, name='add-course'),
    path('edit-course/<int:course_id>/', views.edit_course, name='edit-course'),
    path('update-course/<int:course_id>/', views.update_course, name='update-course'),

    # Optional for now. Can delete later
    path('password-setting/', views.password_setting, name='password-setting'),
    path('change-setting/', views.change_setting, name='change-setting'),
    
    path('request-otp/', views.request_otp, name='request-otp'),
    path('change-user-password/', views.change_user_password, name='change-user-password'),

    # Ajax
    path('update-status/<int:institution_id>/', views.update_status, name='update-status'),
]
from django.contrib import admin
from .models import *
from django.contrib.admin import TabularInline

@admin.register(SuperAdmin)
class SuperAdminAdmin(admin.ModelAdmin):
    list_display = ('name', 'email', 'status')

@admin.register(User)
class UserAdmin(admin.ModelAdmin):
    list_display = ('name', 'email', 'status')

@admin.register(InstitutionAdmin)
class InstitutionAdminAdmin(admin.ModelAdmin):
    list_display = ('name', 'email', 'status')

class InstitutionImageInline(admin.TabularInline):
    model = InstitutionImage
    extra = 1  # Default number of images to show when adding

@admin.register(Institution)
class InstitutionAdmin(admin.ModelAdmin):
    list_display = ('name', 'email', 'phone', 'affiliation')
    inlines = [InstitutionImageInline]  # Allow gallery images to be added inline

@admin.register(InstitutionImage)
class InstitutionImageAdmin(admin.ModelAdmin):
    list_display = ('institution', 'image', 'caption')
    search_fields = ('institution__name',)

@admin.register(Feedback)
class FeedbackAdmin(admin.ModelAdmin):
    list_display = ('user', 'review')

@admin.register(Course)
class CourseAdmin(admin.ModelAdmin):
    list_display = ('name', 'affiliation')

@admin.register(InstitutionCourse)
class InstitutionCourseAdmin(admin.ModelAdmin):
    list_display = ('institution', 'course', 'updated_at')

@admin.register(OTP)
class OTPAdmin(admin.ModelAdmin):
    list_display = ('email', 'created_at')

@admin.register(Application)
class ApplicationAdmin(admin.ModelAdmin):
    list_display = ('user', 'institution', 'program')

@admin.register(Rating)
class RatingAdmin(admin.ModelAdmin):
    list_display = ('user', 'institution', 'rating')

@admin.register(InstitutionView)
class InstitutionViewAdmin(admin.ModelAdmin):
    list_display = ('institution', 'timestamp')
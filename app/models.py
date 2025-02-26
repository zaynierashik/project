import os

from django.db import models
from django.db.models import Avg
from django.utils.timezone import now
from django.contrib.auth.hashers import make_password

class SuperAdmin(models.Model):
    STATUS_CHOICES = [
        ('active', 'Active'),
        ('inactive', 'In Active'),
    ]

    ROLES = [
        ('admin', 'Admin'),
        ('staff', 'Staff'),
    ]
    
    name = models.CharField(max_length=255)
    phone = models.CharField(max_length=10, unique=True)
    email = models.EmailField(unique=True)
    password = models.CharField(max_length=255)
    status = models.CharField(max_length=10, choices=STATUS_CHOICES, default='active')
    role = models.CharField(max_length=10, choices=ROLES, default='staff')
    created_at = models.DateField(default=now)
    
    def __str__(self):
        return self.name
    
    def save(self, *args, **kwargs):
        # Hash the password before saving if it's not already hashed
        if self.password and not self.password.startswith(('pbkdf2_sha256$', 'bcrypt')):
            self.password = make_password(self.password)
        super(SuperAdmin, self).save(*args, **kwargs)

class User(models.Model):
    STATUS_CHOICES = [
        ('active', 'Active'),
        ('suspended', 'Suspended'),
    ]

    PROVINCES = [
        ('province_1', 'Province No. 1'),
        ('province_2', 'Province No. 2'),
        ('bagmati', 'Bagmati Province'),
        ('gandaki', 'Gandaki Province'),
        ('lumbini', 'Lumbini Province'),
        ('karnali', 'Karnali Province'),
        ('sudurpashchim', 'Sudurpashchim Province'),
    ]
    
    name = models.CharField(max_length=255)
    phone = models.CharField(max_length=10, unique=True)
    email = models.EmailField(unique=True)
    password = models.CharField(max_length=255)
    province = models.CharField(max_length=100, choices=PROVINCES, blank=True, null=True)
    status = models.CharField(max_length=10, choices=STATUS_CHOICES, default='active')
    
    def __str__(self):
        return self.name
    
    def save(self, *args, **kwargs):
        # Hash the password before saving if it's not already hashed
        if self.password and not self.password.startswith(('pbkdf2_sha256$', 'bcrypt')):
            self.password = make_password(self.password)
        super(User, self).save(*args, **kwargs)

class InstitutionAdmin(models.Model):
    STATUS_CHOICES = [
        ('approved', 'Approved'),
        ('rejected', 'Rejected'),
        ('not_decided', 'Not Decided')
    ]
    
    name = models.CharField(max_length=255)
    institution = models.CharField(max_length=255)
    email = models.EmailField(unique=True)
    password = models.CharField(max_length=255)
    status = models.CharField(max_length=100, choices=STATUS_CHOICES, default='not_decided')
    
    def __str__(self):
        return self.name
    
    def save(self, *args, **kwargs):
        # Hash the password before saving if it's not already hashed
        if self.password and not self.password.startswith(('pbkdf2_sha256$', 'bcrypt')):
            self.password = make_password(self.password)
        super(InstitutionAdmin, self).save(*args, **kwargs)

def logo_upload_to(instance, filename):
    # Using the institution's name to create a folder structure
    institution_name = instance.name.lower().replace(" ", "-")
    return os.path.join(f'institution/{institution_name}/logo/{filename}')

def cover_upload_to(instance, filename):
    # Using the institution's name to create a folder structure
    institution_name = instance.name.lower().replace(" ", "-")
    return os.path.join(f'institution/{institution_name}/cover/{filename}')

def gallery_upload_to(instance, filename):
    # Using the institution's name to create a folder structure for gallery images
    institution_name = instance.institution.name.lower().replace(" ", "-")
    return os.path.join(f'institution/{institution_name}/gallery/{filename}')

class Institution(models.Model):
    AFFILIATION_CHOICES = [
        ('tribhuvan', 'Tribhuvan University'),
        ('pokhara', 'Pokhara University'),
        ('kathmandu', 'Kathmandu University'),
        ('gandaki', 'Gandaki University'),
        ('purbanchal', 'Purbanchal University'),
        ('foreign', 'Foreign University'),
    ]
    
    name = models.CharField(max_length=255, unique=True)
    overview = models.TextField()
    message = models.TextField(blank=True, null=True)
    program = models.TextField(help_text="List of programs offered, separated by commas.")
    phone = models.CharField(max_length=15)
    email = models.EmailField(unique=True)
    website = models.URLField(blank=True, null=True)
    address = models.CharField(max_length=255)
    map = models.TextField(blank=True, null=True, help_text="Embed map URL with width value 950 and height value 500.")
    logo = models.ImageField(upload_to=logo_upload_to, blank=True, null=True)
    Cover_Photo = models.ImageField(upload_to=cover_upload_to, blank=True, null=True)
    affiliation = models.CharField(max_length=50, choices=AFFILIATION_CHOICES)
    Foreign_University_Name = models.CharField(max_length=255, blank=True, null=True, help_text="If the affiliation is Foreign University, specify the university name here.")
    admin = models.OneToOneField(InstitutionAdmin, on_delete=models.CASCADE, related_name='managed_institution', blank=True, null=True)
    admission = models.BooleanField(default=False, help_text="Toggle to enable or disable the admission period.")

    last_admissions = models.IntegerField(default=0, help_text="Admissions count from the last admission period.")
    current_admissions = models.IntegerField(default=0, help_text="Current admissions count.")

    average_rating = models.DecimalField(max_digits=3, decimal_places=2, default=0.0)
    
    def increment_admission_count(self):
        """Increase admission count if admission period is active."""
        if self.admission:
            self.current_admissions += 1
            self.save()

    def reset_admissions(self):
        """Move current admissions to last_admissions and reset count."""
        self.last_admissions = self.current_admissions
        self.current_admissions = 0
        self.save()

    def save(self, *args, **kwargs):
        # Ensure foreign_university_name is only populated when affiliation is 'foreign'
        if self.affiliation != 'foreign':
            self.Foreign_University_Name = None
        super().save(*args, **kwargs)

    def update_average_rating(self):
        # Calculate the average rating using the reverse relationship
        average = self.ratings.aggregate(Avg('rating'))['rating__avg']
        self.average_rating = average if average else 0  # Set the average rating
        self.save()

    def __str__(self):
        return self.name
    
class Course(models.Model):
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
        ('foreign', 'Foreign University')
    ]
    
    name = models.CharField(max_length=255, unique=True)
    abbreviation = models.CharField(max_length=255, unique=True, blank=True, null=True)
    year = models.CharField(max_length=100)
    field = models.CharField(max_length=255, choices=FIELDS)
    level = models.CharField(max_length=255, choices=LEVELS)
    affiliation = models.CharField(max_length=50, choices=AFFILIATION_CHOICES)
    Foreign_University_Name = models.CharField(max_length=255, blank=True, null=True, help_text="If the affiliation is Foreign University, specify the university name here.")
    about = models.TextField()
    eligibility = models.TextField(blank=True, null=True)
    Admission_Criteria = models.TextField(blank=True, null=True)
    Job_Prospect = models.TextField(blank=True, null=True)
    Prospect_Career = models.TextField(blank=True, null=True)
    # Offered_by = models.ManyToManyField('Institution', blank=True, related_name='courses_offered')

    def save(self, *args, **kwargs):
        if not self.abbreviation:
            self.abbreviation = None
        if self.affiliation != 'foreign':
            self.Foreign_University_Name = None
        super().save(*args, **kwargs)

    def __str__(self):
        return self.name
    
class InstitutionCourse(models.Model):
    institution = models.ForeignKey(Institution, on_delete=models.CASCADE, related_name='institution_courses')
    course = models.ForeignKey(Course, on_delete=models.CASCADE, related_name='institution_courses')
    details = models.TextField(blank=True, null=True, help_text="Details specific to this course offered by the institution.")
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        unique_together = ('institution', 'course')  # Prevent duplicate institution-course pairs

    def __str__(self):
        return f"{self.institution.name} - {self.course.name}"

class InstitutionImage(models.Model):
    institution = models.ForeignKey(Institution, related_name="images", on_delete=models.CASCADE)
    image = models.ImageField(upload_to=gallery_upload_to)
    caption = models.CharField(max_length=255, blank=True)

    def __str__(self):
        return f"Image for {self.institution.name} - {self.caption}"
    
class Feedback(models.Model):
    STATUS_CHOICES = [
        ('show', 'Show'),
        ('hide', 'Hide'),
    ]

    user = models.ForeignKey('User', on_delete=models.CASCADE, related_name='feedbacks')
    email = models.EmailField()
    phone = models.CharField(max_length=10, blank=True, null=True)
    review = models.TextField()
    created_at = models.DateField(default=now)
    status = models.CharField(max_length=10, choices=STATUS_CHOICES, default='hide')

    def __str__(self):
        return self.user.name
    
class OTP(models.Model):
    email = models.EmailField()
    otp = models.CharField(max_length=6)
    created_at = models.DateTimeField(auto_now_add=True)

    def __str__(self):
        return f"OTP for {self.email}"
    
    def is_valid(self):
        return (now() - self.created_at).seconds < 300
    
class Application(models.Model):
    STATUS_CHOICES = [
        ('pending', 'Pending'),
        ('accepted', 'Accepted'),
        ('rejected', 'Rejected'),
    ]
    
    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='applications')
    institution = models.ForeignKey(Institution, on_delete=models.CASCADE, related_name='applications')
    program = models.ForeignKey(InstitutionCourse, on_delete=models.CASCADE, related_name='applications')
    phone = models.CharField(max_length=15)
    email = models.EmailField()
    query = models.TextField(blank=True, null=True, help_text="Optional user query or additional information.")
    status = models.CharField(max_length=10, choices=STATUS_CHOICES, default='pending')
    applied_at = models.DateTimeField(default=now)
    updated_at = models.DateTimeField(auto_now=True)
    
    class Meta:
        unique_together = ('user', 'institution', 'program')  # Prevent duplicate applications for the same user, institution, and program
    
    def save(self, *args, **kwargs):
        """Automatically update admission count when status changes to accepted."""
        if self.pk:
            old_application = Application.objects.get(pk=self.pk)
            if old_application.status != "accepted" and self.status == "accepted":
                self.institution.increment_admission_count()

        super().save(*args, **kwargs)

    def __str__(self):
        return f"Application by {self.user.name} for {self.program.course.name} at {self.institution.name}"

class Rating(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE)
    institution = models.ForeignKey(Institution, on_delete=models.CASCADE, related_name='ratings')
    rating = models.IntegerField(choices=[(i, i) for i in range(1, 6)])
    comment = models.TextField(blank=True, null=True)

    def save(self, *args, **kwargs):
        existing_rating = Rating.objects.filter(user=self.user, institution=self.institution).first()
        if existing_rating:
            # If a rating exists, delete the old one
            existing_rating.delete()
        # Now create a new rating
        super().save(*args, **kwargs)

    def __str__(self):
        return f"Rating by {self.user} for {self.institution}"

class InstitutionView(models.Model):
    institution = models.ForeignKey('Institution', on_delete=models.CASCADE, related_name='views')
    timestamp = models.DateTimeField(default=now)

    def __str__(self):
        return f"View for {self.institution.name} on {self.timestamp}"
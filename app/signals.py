from django.db.models.signals import post_save
from django.dispatch import receiver
from .models import Rating

@receiver(post_save, sender=Rating)
def update_institution_rating(sender, instance, **kwargs):
    """Update the institution's average rating after a new rating is saved."""
    instance.institution.update_average_rating()
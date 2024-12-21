// Website Scripts

// Mobile view
document.addEventListener('DOMContentLoaded', function() {
    const menuButton = document.querySelector('.lg\\:hidden button');
    const mobileMenu = document.querySelector('.lg\\:hidden[role="dialog"]');
    const closeButton = mobileMenu.querySelector('button');

    function toggleMenu() {
        mobileMenu.classList.toggle('hidden');
    }

    menuButton.addEventListener('click', toggleMenu);
    closeButton.addEventListener('click', toggleMenu);
});

// Scroll to section
function scrollToSection(sectionId){
    var section = document.getElementById(sectionId);
    if(section){
        section.scrollIntoView({ behavior: 'smooth' });
    }
}

// Back to top
window.addEventListener('scroll', function() {
    const button = document.getElementById('back-to-top');
    if (window.scrollY > 1400) { // Change the value to show back-to-top button from certain px
        button.classList.remove('hidden');
        button.classList.add('show');
    } else {
        button.classList.remove('show');
        button.classList.add('hidden');
    }
});

document.getElementById('back-to-top').addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
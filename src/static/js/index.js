// Toggle login/signup form
document.getElementById('toggleSignup').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('login-form').classList.add('hidden');
    document.getElementById('signup-form').classList.remove('hidden');
});

document.getElementById('toggleLogin').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('login-form').classList.remove('hidden');
    document.getElementById('signup-form').classList.add('hidden');
});

// Signup password toggle
var signupPasswordInput = document.getElementById('signup-password');
var passwordToggleContainer = document.getElementById('password-toggle-container');
var togglePasswordButton = document.getElementById('toggle-password');

var eyeIconClosed = document.getElementById('eye-icon-closed');
var eyeIconOpen = document.getElementById('eye-icon-open');

signupPasswordInput.addEventListener('input', function () {
    if (signupPasswordInput.value.length > 0) {
        passwordToggleContainer.style.display = 'flex';
    } else {
        passwordToggleContainer.style.display = 'none';
    }
});

togglePasswordButton.addEventListener('click', function () {
    if (signupPasswordInput.type === 'password') {
        signupPasswordInput.type = 'text';
        eyeIconClosed.classList.add('hidden');
        eyeIconOpen.classList.remove('hidden');
    } else {
        signupPasswordInput.type = 'password';
        eyeIconClosed.classList.remove('hidden');
        eyeIconOpen.classList.add('hidden');
    }
});

// Login password toggle
var loginPasswordInput = document.getElementById('login-password');
var loginPasswordToggleContainer = document.getElementById('login-password-toggle-container');
var loginTogglePasswordButton = document.getElementById('login-toggle-password');

var loginEyeIconClosed = document.getElementById('login-eye-icon-closed');
var loginEyeIconOpen = document.getElementById('login-eye-icon-open');

loginPasswordInput.addEventListener('input', function () {
    if (loginPasswordInput.value.length > 0) {
        loginPasswordToggleContainer.style.display = 'flex';
    } else {
        loginPasswordToggleContainer.style.display = 'none';
    }
});

loginTogglePasswordButton.addEventListener('click', function () {
    if (loginPasswordInput.type === 'password') {
        loginPasswordInput.type = 'text';
        loginEyeIconClosed.classList.add('hidden');
        loginEyeIconOpen.classList.remove('hidden');
    } else {
        loginPasswordInput.type = 'password';
        loginEyeIconClosed.classList.remove('hidden');
        loginEyeIconOpen.classList.add('hidden');
    }
});

// Check email dynamically
document.getElementById('signup-email').addEventListener('blur', async function () {
    const email = this.value;
    const emailField = this;
    const errorDiv = document.getElementById('email-error');

    emailField.classList.remove('border-red-500', 'focus:ring-red-500');
    emailField.classList.add('border-gray-400', 'focus:ring-white');
    errorDiv.classList.add('hidden');

    if (email) {
        try {
            const csrfToken = document.querySelector('[name=csrfmiddlewaretoken]').value;
            const response = await fetch('/check-email/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRFToken': csrfToken
                },
                body: new URLSearchParams({ email })
            });
            const data = await response.json();

            if (data.exists) {
                emailField.classList.remove('border-gray-400', 'focus:ring-white');
                emailField.classList.add('border-red-500', 'focus:ring-red-500');
                errorDiv.textContent = 'This email already exists.';
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error checking email:', error);
        }
    }
});

// Toast timeout
setTimeout(function() {
    let toastContainer = document.getElementById('toast-container');
    if (toastContainer) {
        toastContainer.querySelectorAll('.w-full').forEach(function(toast) {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 1s ease-out';
            setTimeout(function() {
                toast.remove();
            }, 1000);
        });
    }
}, 2500);
    // Show Password

    function showPassword(){
        var x = document.getElementById("password");
        if(x.type == "password"){
            x.type = "text";
        }else{
            x.type = "password";
        }
    }

    function showOrgPassword(){
        var x = document.getElementById("orgPassword");
        if(x.type == "password"){
            x.type = "text";
        }else{
            x.type = "password";
        }
    }

    // Toggle Form

    const modalBtn = document.getElementById('modal-btn');
    const loginModal = document.getElementById('loginModal');

    modalBtn.addEventListener('click', function() {
        loginModal.style.display = 'block';
    });

    const closeModal = document.querySelector('.fa-xmark');
    closeModal.addEventListener('click', function() {
        loginModal.style.display = 'none';
    });

    // Toggle Register Form

    function showRegisterForm() {
        var userForm = document.getElementById("register");
        var orgForm = document.getElementById("organization-register");

        userForm.style.display = "none";
        orgForm.style.display = "block";
    }
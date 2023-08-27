    // Back to Top

    const arrowLink = document.querySelector('.top');
    window.addEventListener('scroll', () => {
        if(window.scrollY > 750){
            arrowLink.classList.remove('hidden');
        }else{
            arrowLink.classList.add('hidden');
        }
    });

    // Show Password

    function showPassword(){
        var x = document.getElementById("password");
        if(x.type == "password"){
            x.type = "text";
        }else{
            x.type = "password";
        }
    }

    function showLoginPassword(){
        var x = document.getElementById("login-password");
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
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('btnTogglePassword');
    const password = document.getElementById('password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // toggle the eye icon
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        });
    }

    // Submit Loading Effect
    const loginForm = document.querySelector('form');
    const loginBtn = document.getElementById('btn-login');

    if (loginForm && loginBtn) {
        loginForm.addEventListener('submit', function() {
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
            loginBtn.style.opacity = '0.8';
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');
    const resetForm = document.getElementById('reset-form');
    const newPasswordForm = document.getElementById('new-password-form');
    
    // Validációs segédfüggvények
    function showValidationError(input, message) {
        const parent = input.parentElement;
        parent.classList.add('error');
        if (!parent.querySelector('.validation-message')) {
            const error = document.createElement('div');
            error.className = 'validation-message';
            error.textContent = message;
            parent.appendChild(error);
        }
    }

    function removeValidationError(input) {
        const parent = input.parentElement;
        parent.classList.remove('error');
        const error = parent.querySelector('.validation-message');
        if (error) {
            parent.removeChild(error);
        }
    }

    // Bejelentkezési űrlap validáció
    if (loginForm) {
        const emailInput = loginForm.querySelector('input[name="email"]');
        const passwordInput = loginForm.querySelector('input[name="password"]');

        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            removeValidationError(emailInput);
            removeValidationError(passwordInput);

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                showValidationError(emailInput, 'Érvénytelen e-mail cím.');
                isValid = false;
            }

            if (passwordInput.value.length < 6) {
                showValidationError(passwordInput, 'A jelszónak legalább 6 karakter hosszúnak kell lennie.');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Regisztrációs űrlap validáció
    if (signupForm) {
        const usernameInput = signupForm.querySelector('input[name="username"]');
        const emailInput = signupForm.querySelector('input[name="email"]');
        const passwordInput = signupForm.querySelector('input[name="password"]');
        const repeatPasswordInput = signupForm.querySelector('input[name="repeat_password"]');

        signupForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            removeValidationError(usernameInput);
            removeValidationError(emailInput);
            removeValidationError(passwordInput);
            removeValidationError(repeatPasswordInput);

            if (usernameInput.value.trim().length < 3) {
                showValidationError(usernameInput, 'A felhasználónévnek legalább 3 karakter hosszúnak kell lennie.');
                isValid = false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                showValidationError(emailInput, 'Érvénytelen e-mail cím.');
                isValid = false;
            }
            
            if (passwordInput.value.length < 6) {
                showValidationError(passwordInput, 'A jelszónak legalább 6 karakter hosszúnak kell lennie.');
                isValid = false;
            }
            
            if (passwordInput.value !== repeatPasswordInput.value) {
                showValidationError(repeatPasswordInput, 'A jelszavaknak egyezniük kell.');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Jelszó visszaállítási űrlap validáció
    if (resetForm) {
        const emailInput = resetForm.querySelector('input[name="email"]');

        resetForm.addEventListener('submit', function(e) {
            let isValid = true;
            removeValidationError(emailInput);

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                showValidationError(emailInput, 'Érvénytelen e-mail cím.');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Új jelszó űrlap validáció
    if (newPasswordForm) {
        const newPasswordInput = newPasswordForm.querySelector('input[name="new_password"]');
        const confirmPasswordInput = newPasswordForm.querySelector('input[name="confirm_password"]');

        newPasswordForm.addEventListener('submit', function(e) {
            let isValid = true;
            removeValidationError(newPasswordInput);
            removeValidationError(confirmPasswordInput);
            
            if (newPasswordInput.value.length < 6) {
                showValidationError(newPasswordInput, 'A jelszónak legalább 6 karakter hosszúnak kell lennie.');
                isValid = false;
            }
            
            if (newPasswordInput.value !== confirmPasswordInput.value) {
                showValidationError(confirmPasswordInput, 'A jelszavaknak egyezniük kell.');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

});
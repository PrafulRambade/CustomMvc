function validateAndSubmit() {
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var emailError = document.getElementById('emailError');
    var passwordError = document.getElementById('passwordError');
    var isValid = true;
    emailError.textContent = '';
    passwordError.textContent = '';
    // Validate email
    if (!email.trim()) {
        emailError.textContent = 'Email address is required';
        isValid = false;
    } else if (!isValidEmail(email)) {
        emailError.textContent = 'Invalid email address';
        isValid = false;
    }
    // Validate password
    if (!password.trim()) {
        passwordError.textContent = 'Password is required';
        isValid = false;
    }
    // If form is valid, submit the form
    if (isValid) {
        document.getElementById('loginForm').submit();
    }
}
function isValidEmail(email) {
    // Regular expression for email validation
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email);
}

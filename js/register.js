$(document).ready(function() {
    console.log("Register.js loaded");
    
    // Check if the user is already logged in
    if (localStorage.getItem('user_token')) {
        console.log("User already logged in, redirecting to profile");
        window.location.href = 'profile.html';
    }
    
    // Handle form submission
    $('#register-form').submit(function(e) {
        console.log("Form submitted");
        e.preventDefault();
        
        // Get form data
        const username = $('#username').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const confirmPassword = $('#confirm-password').val();
        
        console.log("Form data collected:", { username, email });
        
        // Simple validation
        if (password !== confirmPassword) {
            console.log("Passwords do not match");
            showError('Passwords do not match');
            return;
        }
        
        console.log("Sending AJAX request to php/register.php");
        
        // Send AJAX request
        $.ajax({
            url: 'php/register.php',
            type: 'POST',
            dataType: 'json',
            data: {
                username: username,
                email: email,
                password: password
            },
            success: function(response) {
                console.log("AJAX success response:", response);
                if (response.status === 'success') {
                    showSuccess('Registration successful! Redirecting to login...');
                    setTimeout(function() {
                        window.location.href = 'login.html';
                    }, 2000);
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX error:", { xhr, status, error });
                console.log("Response text:", xhr.responseText);
                showError('Something went wrong. Please try again later.');
            }
        });
    });
    
    // Helper functions
    function showError(message) {
        console.log("Showing error:", message);
        $('#register-error').text(message).removeClass('d-none');
        $('#register-success').addClass('d-none');
    }
    
    function showSuccess(message) {
        console.log("Showing success:", message);
        $('#register-success').text(message).removeClass('d-none');
        $('#register-error').addClass('d-none');
    }
});
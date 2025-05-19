$(document).ready(function() {
    // Check if the user is already logged in
    if (localStorage.getItem('user_token')) {
        window.location.href = 'profile.html';
    }
    
    // Handle form submission
    $('#login-form').submit(function(e) {
        e.preventDefault();
        
        // Get form data
        const email = $('#login-email').val();
        const password = $('#login-password').val();
        
        // Send AJAX request
        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            dataType: 'json',
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Store token in localStorage
                    localStorage.setItem('user_token', response.token);
                    localStorage.setItem('user_id', response.user_id);
                    
                    // Redirect to profile page
                    window.location.href = 'profile.html';
                } else {
                    showError(response.message);
                }
            },
            error: function() {
                showError('Something went wrong. Please try again later.');
            }
        });
    });
    
    // Helper function
    function showError(message) {
        $('#login-error').text(message).removeClass('d-none');
    }
});
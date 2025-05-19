$(document).ready(function() {
    // Check if the user is logged in
    const token = localStorage.getItem('user_token');
    const userId = localStorage.getItem('user_id');
    
    if (!token || !userId) {
        window.location.href = 'login.html';
        return;
    }
    
    // Load user profile
    loadProfile();
    
    // Handle form submission for profile update
    $('#profile-form').submit(function(e) {
        e.preventDefault();
        updateProfile();
    });
    
    // Handle logout
    $('#logout-btn').click(function() {
        logout();
    });
    
    // Load profile information
    function loadProfile() {
        $.ajax({
            url: 'php/profile.php',
            type: 'GET',
            dataType: 'json',
            data: {
                user_id: userId,
                token: token,
                action: 'get'
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Fill the form with user data
                    $('#profile-username').val(response.data.username);
                    $('#profile-email').val(response.data.email);
                    $('#fullname').val(response.data.fullname || '');
                    $('#dob').val(response.data.dob || '');
                    $('#age').val(response.data.age || '');
                    $('#contact').val(response.data.contact || '');
                    $('#address').val(response.data.address || '');
                } else {
                    showMessage('error', response.message);
                    if (response.message.includes('invalid token')) {
                        setTimeout(function() {
                            logout();
                        }, 2000);
                    }
                }
            },
            error: function() {
                showMessage('error', 'Failed to load profile. Please try again later.');
            }
        });
    }
    
    // Update profile information
    function updateProfile() {
        const profileData = {
            user_id: userId,
            token: token,
            action: 'update',
            fullname: $('#fullname').val(),
            dob: $('#dob').val(),
            age: $('#age').val(),
            contact: $('#contact').val(),
            address: $('#address').val()
        };
        
        $.ajax({
            url: 'php/profile.php',
            type: 'POST',
            dataType: 'json',
            data: profileData,
            success: function(response) {
                if (response.status === 'success') {
                    showMessage('success', 'Profile updated successfully!');
                } else {
                    showMessage('error', response.message);
                    if (response.message.includes('invalid token')) {
                        setTimeout(function() {
                            logout();
                        }, 2000);
                    }
                }
            },
            error: function() {
                showMessage('error', 'Failed to update profile. Please try again later.');
            }
        });
    }
    
    // Logout function
    function logout() {
        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'logout',
                token: token
            },
            success: function() {
                // Clear local storage and redirect to login
                localStorage.removeItem('user_token');
                localStorage.removeItem('user_id');
                window.location.href = 'login.html';
            },
            error: function() {
                // Even if the server request fails, clear local storage and redirect
                localStorage.removeItem('user_token');
                localStorage.removeItem('user_id');
                window.location.href = 'login.html';
            }
        });
    }
    
    // Helper function to show messages
    function showMessage(type, message) {
        const messageElement = $('#profile-message');
        messageElement.removeClass('d-none alert-success alert-danger');
        
        if (type === 'success') {
            messageElement.addClass('alert-success');
        } else {
            messageElement.addClass('alert-danger');
        }
        
        messageElement.text(message);
        
        // Auto hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(function() {
                messageElement.addClass('d-none');
            }, 3000);
        }
    }
});
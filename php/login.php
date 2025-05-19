<?php
require_once 'db_config.php';
header('Content-Type: application/json');

// Function to generate a random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'logout') {
        $token = $_POST['token'] ?? '';
        
        if (!empty($token)) {
            try {
                // Delete token from Redis
                $redis->del("token:$token");
                
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Logged out successfully'
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Logout failed'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid token'
            ]);
        }
        exit;
    }
    
    // Regular login flow
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email and password are required'
        ]);
        exit;
    }
    
    try {
        // Get user by email
        $stmt = $pdo->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() === 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ]);
            exit;
        }
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ]);
            exit;
        }
        
        // Generate token
        $token = generateToken();
        $user_id = $user['id'];
        
        // Store token in Redis with expiration (8 hours)
        $tokenData = [
            'user_id' => $user_id,
            'email' => $user['email'],
            'username' => $user['username']
        ];
        
        $redis->setex("token:$token", 8 * 60 * 60, json_encode($tokenData));
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful',
            'token' => $token,
            'user_id' => $user_id
        ]);
    } catch (PDOException $e) {
        // Log error (in a real-world scenario)
        // error_log($e->getMessage());
        
        echo json_encode([
            'status' => 'error',
            'message' => 'Login failed. Please try again later.'
        ]);
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}
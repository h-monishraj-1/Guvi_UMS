<?php
require_once 'db_config.php';
header('Content-Type: application/json');

// Function to validate token
function validateToken($token, $user_id, $redis) {
    if (empty($token) || empty($user_id)) {
        return false;
    }
    
    // Check if token exists in Redis
    $tokenData = $redis->get("token:$token");
    
    if (!$tokenData) {
        return false;
    }
    
    $tokenData = json_decode($tokenData, true);
    
    // Verify that token belongs to the user
    return $tokenData && isset($tokenData['user_id']) && $tokenData['user_id'] == $user_id;
}

// Handle GET request (fetch profile)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
    $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $token = $_GET['token'] ?? '';
    
    // Validate token
    if (!validateToken($token, $user_id, $redis)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Authentication failed: invalid token'
        ]);
        exit;
    }
    
    try {
        // Get user data
        $stmt = $pdo->prepare("
            SELECT u.username, u.email, p.fullname, p.dob, p.age, p.contact, p.address
            FROM users u
            LEFT JOIN user_profiles p ON u.id = p.user_id
            WHERE u.id = ?
        ");
        $stmt->execute([$user_id]);
        
        if ($stmt->rowCount() === 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'User not found'
            ]);
            exit;
        }
        
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'data' => $userData
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to fetch profile data'
        ]);
    }
}
// Handle POST request (update profile)
else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $token = $_POST['token'] ?? '';
    
    // Validate token
    if (!validateToken($token, $user_id, $redis)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Authentication failed: invalid token'
        ]);
        exit;
    }
    
    // Get profile data
    $fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING);
    $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    
    try {
        // Check if profile exists
        $stmt = $pdo->prepare("SELECT user_id FROM user_profiles WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        if ($stmt->rowCount() === 0) {
            // Create new profile
            $stmt = $pdo->prepare("
                INSERT INTO user_profiles (user_id, fullname, dob, age, contact, address)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$user_id, $fullname, $dob, $age, $contact, $address]);
        } else {
            // Update existing profile
            $stmt = $pdo->prepare("
                UPDATE user_profiles
                SET fullname = ?, dob = ?, age = ?, contact = ?, address = ?
                WHERE user_id = ?
            ");
            $stmt->execute([$fullname, $dob, $age, $contact, $address, $user_id]);
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Profile updated successfully'
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update profile'
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
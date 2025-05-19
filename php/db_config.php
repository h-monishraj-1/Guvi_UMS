<?php
// Database configuration
$db_host = 'localhost:3307';
$db_name = 'user_management';
$db_user = 'root'; 
$db_pass = ''; 

// Redis configuration
$redis_host = '127.0.0.1';
$redis_port = 6379;
$redis_pass = null; 

// Connect to MySQL using PDO
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Connect to Redis
try {
    $redis = new Redis();
    $redis->connect($redis_host, $redis_port);
    
    if ($redis_pass) {
        $redis->auth($redis_pass);
    }
} catch (Exception $e) {
    die('Redis connection failed: ' . $e->getMessage());
}
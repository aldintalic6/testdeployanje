<?php
require "../vendor/autoload.php";
require "./services/MidtermService.php";
require "./services/FinalService.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::register('midtermService', 'MidtermService');
Flight::register('finalService', 'FinalService');

require 'routes/MidtermRoutes.php';
require 'routes/FinalRoutes.php';

// Middleware to protect routes
Flight::before('start', function () {
    $protectedRoutes = ['/rest/final/share_classes', '/rest/final/share_class_categories'];
    $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

    // Verify token
    try {
        $decoded = JWT::decode($token, Key::getKey(), ['HS256']);
        // Proceed with the request
    } catch (Exception $e) {
        Flight::halt(401, 'Unauthorized');
    }
});

Flight::start();
?>

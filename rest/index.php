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


Flight::start();
?>

<?php
// IRMS_profile.php (Entry Point)

// Load Controller
require_once __DIR__ . '/controllers/ProfileController.php';

// Handle logic
$controller = new ProfileController();
$controller->handleRequest();

// Load View
require_once __DIR__ . '/views/ProfileView.php';

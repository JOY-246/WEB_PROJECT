<?php
// controllers/ProfileController.php
require_once __DIR__ . '/../models/SessionModel.php';

class ProfileController {
    public function handleRequest() {
        // Start session for 5 minutes
        SessionModel::startSession(300);

        // Always reset session to ensure a fresh state
        SessionModel::resetSession();

        // Handle "Apply for Job"
        if (isset($_GET['apply']) && $_GET['apply'] === 'true') {
            SessionModel::applyJob();
            header("Location: ../IRMS_Apply_job.php");
            exit();
        }

        // Handle "Login"
        if (isset($_GET['login']) && $_GET['login'] === 'true') {
            header("Location: ../IRMS_Access_system.php");
            exit();
        }
    }
}

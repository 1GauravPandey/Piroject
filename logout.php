<?php
session_start();
session_unset();     // Clear session variables
session_destroy();   // Destroy the session

// Redirect to login page (or home page)
header("Location: login.php");
exit();

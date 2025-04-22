<?php
/**
 * Handles user logout.
 * 
 * This script clears session data and cookies, then redirects the user to the homepage.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

setcookie('employee_id', '', time() - 3600, '/');
setcookie('employee_role', '', time() - 3600, '/');
session_start();
$_SESSION['compte'] = null;
session_destroy();

header("Location: /index.php");
exit();
?>
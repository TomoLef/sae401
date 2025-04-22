<?php
/**
 * Handles the addition of a new employee via a POST request.
 * 
 * This script validates the input fields, constructs the API URL, 
 * and sends a POST request to the API to add a new employee.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

session_start();
session_regenerate_id();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /**
     * Validate required fields and process the form submission.
     */
    if (isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmPassword'])) {
        if ($_POST['password'] !== $_POST['confirmPassword']) {
            $_SESSION['erreur'] = "Passwords do not match.";
            header("Location: /index.php");
            exit();
        }

        $store = $_SESSION["compte"]["store"]["store_id"];
        if (isset($_POST['store'], $_POST['role'])) {
            $store = $_POST['store'];
            $role = $_POST['role'];
        } else {
            $store = $_SESSION["compte"]["store"]["store_id"];
            $role = "employee";
        }

        $role = urlencode($role);
        $store = urlencode($store);
        $name = urlencode($_POST['nom']);
        $email = urlencode($_POST['email']);
        $password = urlencode($_POST['password']);

        /**
         * Construct the API URL for adding an employee.
         * 
         * @var string $url The API endpoint with query parameters.
         */
        $url = "https://saevelo.alwaysdata.net/api_request/api.php"
            . "?store=$store&employeeName=$name&employeeEmail=$email&employeePassword=$password&role=$role";

        /**
         * Initialize the cURL request to send data to the API.
         * 
         * @var resource $ch The cURL handle.
         */
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Api: e8f1997c763"
        ]);

        /**
         * Execute the cURL request and retrieve the response.
         * 
         * @var string $response The API response.
         * @var int $httpCode The HTTP status code of the response.
         */
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        /**
         * Handle the API response.
         * 
         * - If the request is successful (HTTP 200), redirect to the homepage.
         * - Otherwise, display an error message and redirect to the homepage.
         */
        if ($httpCode === 200) {
            $_SESSION['erreur'] = null;
            header("Location: /index.php");
            exit();
        } else {
            $_SESSION['erreur'] = "Error during the API request (HTTP Code: $httpCode).";
            header("Location: /index.php");
            exit();
        }
    } else {
        /**
         * Handle missing required fields.
         * 
         * Display an error message and redirect to the homepage.
         */
        $_SESSION['erreur'] = "Please fill in all the fields.";
        header("Location: /index.php");
        exit();
    }
}
?>
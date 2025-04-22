<?php
/**
 * Handles user login via a POST request.
 * 
 * This script validates the input fields, constructs the API URL, 
 * and sends a GET request to the API to authenticate the user.
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
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        /**
         * Construct the API URL for user authentication.
         * 
         * @var string $url The API endpoint with query parameters.
         */
        $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=employe&email=$email&password=$password";
        $data = array('email' => $email, 'password' => $password);
        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'GET',
                'content' => json_encode($data),
            ),
        );

        /**
         * Set cookies for the authenticated user.
         * 
         * @param array $employee The employee data retrieved from the API.
         * @return void
         */
        function cookie($employee) {
            $cookie_lifetime = 30 * 24 * 60 * 60; // 30 days
            setcookie(
                "employee_id", 
                $employee['employee_id'], 
                time() + $cookie_lifetime, 
                "/", 
                "", 
                false, 
                true
            );
            setcookie(
                "employee_role", 
                $employee['employee_role'], 
                time() + $cookie_lifetime, 
                "/", 
                "", 
                false, 
                true
            );
        }

        /**
         * Execute the API request and retrieve the response.
         * 
         * @var string $response The API response.
         * @var array|null $employee The decoded employee data from the API response.
         */
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $employee = json_decode($response, true);

        /**
         * Handle the API response.
         * 
         * - If the response is null, display an error message and redirect to the homepage.
         * - If the response contains employee data, set session and cookies, then redirect to the homepage.
         * - Otherwise, display an error message for incorrect credentials.
         */
        if ($employee === NULL) {
            $_SESSION['erreur'] = "Error connecting to the API.";
            header("Location: /index.php");
            exit();
        }

        if (!empty($employee)) {
            $_SESSION['compte'] = $employee;
            $_SESSION['erreur'] = null;
            cookie($employee);
            header("Location: /index.php");
            exit();
        } else {
            $_SESSION['erreur'] = "Incorrect email or password.";
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

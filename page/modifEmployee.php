<?php
/**
 * Handles the modification of an employee's information via a POST request.
 * 
 * This script validates the input fields, constructs the API URL, 
 * and sends a PUT request to the API to update the employee's information.
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

        $id = urlencode($_SESSION["compte"]["employee_id"]);
        $store = urlencode($_SESSION["compte"]["store"]["store_id"]);
        $name = urlencode($_POST['nom']);
        $email = urlencode($_POST['email']);
        $password = urlencode($_POST['password']);
        $role = urlencode($_SESSION["compte"]["employee_role"]);

        /**
         * Construct the API URL for modifying an employee.
         * 
         * @var string $url The API endpoint with query parameters.
         */
        $url = "https://saevelo.alwaysdata.net/api_request/api.php"
            . "?id=$id&store=$store&employeeName=$name&employeeEmail=$email&employeePassword=$password&role=$role";

        /**
         * Initialize the cURL request to send data to the API.
         * 
         * @var resource $ch The cURL handle.
         */
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
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
         * Retrieve updated employee information from the API.
         */
        $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=employe&id=" . $_SESSION["compte"]["employee_id"];
        $data = array('action' => 'employee', 'id' => $_SESSION["compte"]["employee_id"]);
        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json\r\n" . "Api: e8f1997c763\r\n",
                'method'  => 'GET',
                'content' => json_encode($data),
            ),
        );
        $context  = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);
        $employee = json_decode($response, true);

        /**
         * Update session and cookies with the new employee data.
         */
        function cookie($employee) {
            $cookie_lifetime = 30 * 24 * 60 * 60; // 30 days
            setcookie("employee_id", $employee['employee_id'], time() + $cookie_lifetime, "/", "", false, true);
            setcookie("employee_role", $employee['employee_role'], time() + $cookie_lifetime, "/", "", false, true);
        }

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
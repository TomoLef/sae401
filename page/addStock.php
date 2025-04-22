<?php
/**
 * Handles the addition of stock via a POST request.
 * 
 * This script validates the input fields, constructs the API URL, 
 * and sends a POST request to the API to add stock to the store.
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
    if (isset($_POST['product'], $_POST['quantity'])) {
        // Retrieve form data
        $product = urlencode($_POST['product']);
        $quantity = urlencode($_POST['quantity']);
        $store = $_SESSION["compte"]["store"]["store_id"];

        /**
         * Construct the API URL for adding stock.
         * 
         * @var string $url The API endpoint with query parameters.
         */
        $url = "https://saevelo.alwaysdata.net/api_request/api.php?"
            . "&store=$store&product=$product&quantity=$quantity";

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
            $_SESSION['erreur'] = "Error during stock addition.";
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
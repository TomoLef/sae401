<?php
/**
 * Handles the deletion of a product and its associated stock via a POST request.
 * 
 * This script retrieves the stock associated with the product, sends DELETE requests 
 * to the API to remove the stock and the product, and handles the API responses.
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
    if (isset($_POST['product'])) {
        $product = $_POST['product'];

        /**
         * Retrieve the stock associated with the product.
         */
        $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=stock";
        $data = array('action' => 'stock');
        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'GET',
                'content' => json_encode($data),
            ),
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $stock = json_decode($response, true);

        foreach ($stock as $item) {
            if ($item['product']['product_id'] == $product && $item['store']['store_id'] == $_SESSION["compte"]["store"]["store_id"]) {
                $itemId = $item['stock_id'];
                break;
            }
        }

        /**
         * Send DELETE request to remove the stock.
         */
        $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=stock&id=$itemId";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Api: e8f1997c763"
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        /**
         * Send DELETE request to remove the product.
         */
        $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=produit&id=$product";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Api: e8f1997c763"
        ]);
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
        $_SESSION['erreur'] = "Please select a product.";
        header("Location: /index.php");
        exit();
    }
}
?>
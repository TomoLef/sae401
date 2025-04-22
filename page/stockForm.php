<?php
/**
 * Displays forms for managing stock (add, modify, delete).
 * 
 * This script retrieves stock data from the API and generates forms for adding,
 * modifying, and deleting stock entries.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

session_start();

$url = "https://saevelo.alwaysdata.net/api_request/api.php?action=stock";
$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'GET',
    ),
);
$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$stocks = json_decode($response, true);

echo "<h2>Manage Stock</h2>";

// Form for adding stock
echo "
<form method='POST' action='page/addStock.php'>
    <label class='form-label' for='product'>Product:</label>
    <select class='form-input' name='product' id='product' required>
        <option disabled selected>Select a product</option>";
        foreach ($stocks as $stock) {
            if ($stock['store']['store_id'] === $_SESSION["compte"]["store"]["store_id"]) {
                echo "<option value='{$stock['product']['product_id']}'>{$stock['product']['product_name']}</option>";
            }
        }
echo "  </select>
    <label class='form-label' for='quantity'>Quantity:</label>
    <input class='form-input' type='number' id='quantity' name='quantity' required>
    <button class='form-button' type='submit'>Add</button>
</form>";

// Form for modifying stock
echo "
<form method='POST' action='page/modifyStock.php'>
    <label class='form-label' for='stock'>Stock:</label>
    <select class='form-input' name='stock' id='stock' required>
        <option disabled selected>Select a stock</option>";
        foreach ($stocks as $stock) {
            if ($stock['store']['store_id'] === $_SESSION["compte"]["store"]["store_id"]) {
                echo "<option value='{$stock['stock_id']}'>{$stock['product']['product_name']} - {$stock['quantity']}</option>";
            }
        }
echo "  </select>
    <label class='form-label' for='quantity'>New quantity:</label>
    <input class='form-input' type='number' id='quantity' name='quantity' required>
    <button class='form-button' type='submit'>Modify</button>
</form>";

// Form for deleting stock
echo "
<form method='POST' action='page/deleteStock.php'>
    <label class='form-label' for='stock'>Stock:</label>
    <select class='form-input' name='stock' id='stock' required>
        <option disabled selected>Select a stock</option>";
        foreach ($stocks as $stock) {
            if ($stock['store']['store_id'] === $_SESSION["compte"]["store"]["store_id"]) {
                echo "<option value='{$stock['stock_id']}'>{$stock['product']['product_name']} - {$stock['quantity']}</option>";
            }
        }
echo "  </select>
    <button class='form-button' type='submit'>Delete</button>
</form>";
?>
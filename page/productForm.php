<?php
/**
 * Displays forms for managing products (add, modify, delete).
 * 
 * This script retrieves product, category, and brand data from the API and generates
 * forms for adding, modifying, and deleting products.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

session_start();

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
$stocks = json_decode($response, true);

echo "<form class='filter-form' method='POST' action='page/supProduct.php'>";
    echo "<label class='form-label' for='product'>Select a product:</label>";
    echo "<select class='form-input' name='product' id='product'>";
        echo "<option disabled selected>Product</option>";
        foreach ($stocks as $stock) {
            if ($stock['store']['store_id'] === $_SESSION["compte"]["store"]["store_id"]) {
                echo "<option value='{$stock['product']['product_id']}'>{$stock['product']['product_name']}</option>";
            }
        }
    echo "</select>";
    echo "<button id='productSubmit' class='form-button' type='submit'>Delete</button>";
echo "</form>";

echo "<form class='filter-form' method='POST' action='page/modifyProduct.php'>";
    echo "<label class='form-label' for='product'>Select a product:</label>";
    echo "<select class='form-input' name='product' id='product'>";
        echo "<option disabled selected>Product</option>";
        foreach ($stocks as $stock) {
            if ($stock['store']['store_id'] === $_SESSION["compte"]["store"]["store_id"]) {
                echo "<option value='{$stock['product']['product_id']}'>{$stock['product']['product_name']}</option>";
            }
        }
    echo "</select>";
    echo "<button id='productSubmit' class='form-button' type='submit'>Modify</button>";
echo "</form>";

echo "
    <div class='form-header'>
        <h2>Add new product:</h2>
    </div>";
echo "
    <form method='POST' action='page/addProduct.php'>
        <label class='form-label' for='nom'>Name:</label>
        <input class='form-input' type='nom' id='nom' name='nom' required>
    ";
        $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=categorie";
        $data = array('action' => 'category');
        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'GET',
                'content' => json_encode($data),
            ),
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $categories = json_decode($response, true);
        echo "<label class='form-label' for='category'>Category:</label>";
        echo "<select class='form-input' name='category'>";
        echo "<option disabled selected>Category</option>";
        foreach ($categories as $category) {
            echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
        }
        echo "</select>";
        $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=marque";
        $data = array('action' => 'brand');
        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'GET',
                'content' => json_encode($data),
            ),
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $brands = json_decode($response, true);
        echo "<label class='form-label' for='brand'>Brand:</label>";
        echo "<select class='form-input' name='brand'>";
        echo "<option disabled selected>Brand</option>";
        foreach ($brands as $brand) {
            echo "<option value='{$brand['brand_id']}'>{$brand['brand_name']}</option>";
        }
        echo "</select>";
        $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=annee";
        $data = array('action' => 'year');
        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'GET',
                'content' => json_encode($data),
            ),
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $years = json_decode($response, true);
        echo "<label class='form-label' for='year'>Year:</label>";
        echo "<select class='form-input' name='year'>";
        echo "<option disabled selected>Model</option>";
        foreach ($years as $year) {
            echo "<option value='{$year}'>{$year}</option>";
        }
        echo "</select>";
echo "
        <label class='form-label' for='price'>Price:</label>
        <input class='form-input' type='price' id='price' name='price' required>

        <div class='btn-container'>
            <button id='close-btn' class='form-button' type='button'>Close</button>
            <button class='form-button' type='submit'>Add</button>
        </div>
    </form>
";
?>
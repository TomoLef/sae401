<?php
/**
 * Displays a form to modify an existing product.
 * 
 * This script retrieves product, category, and brand data from the API and generates
 * a form for modifying the product.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

include 'header.php';

if (isset($_POST['product'])) {
    $idProduit = $_POST['product'];
} else {
    echo "<h2>Error: Product ID not specified.</h2>";
    exit;
}

/**
 * Retrieve product information from the API.
 */
$url = "https://saevelo.alwaysdata.net/api_request/api.php?action=produit&id=$idProduit";
$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'GET',
    ),
);
$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$product = json_decode($response, true);

if (empty($product)) {
    echo "<h2>Error: Product not found.</h2>";
    exit;
}

/**
 * Retrieve category and brand data from the API.
 */
$url = "https://saevelo.alwaysdata.net/api_request/api.php?action=categorie";
$response = file_get_contents($url);
$categories = json_decode($response, true);

$url = "https://saevelo.alwaysdata.net/api_request/api.php?action=marque";
$response = file_get_contents($url);
$brands = json_decode($response, true);

echo "<h2>Modify Product</h2>";
echo "
<form method='POST' action='updateProduct.php'>
    <input type='hidden' name='product_id' value='{$product['product_id']}'>
    <div class='product-container'>
        <div class='product-image'>
            <img src='../img/velo.png' alt='{$product['category']['category_name']}'>
        </div>
        <div class='product-details'>
            <label class='form-label' for='product_name'>Name:</label>
            <input class='form-input' type='text' id='product_name' name='product_name' value='{$product['product_name']}' required>

            <label class='form-label' for='category'>Category:</label>
            <select class='form-input' name='category' id='category' required>";
                foreach ($categories as $category) {
                    $selected = $category['category_id'] == $product['category']['category_id'] ? "selected" : "";
                    echo "<option value='{$category['category_id']}' $selected>{$category['category_name']}</option>";
                }
echo "      </select>

            <label class='form-label' for='brand'>Brand:</label>
            <select class='form-input' name='brand' id='brand' required>";
                foreach ($brands as $brand) {
                    $selected = $brand['brand_id'] == $product['brand']['brand_id'] ? "selected" : "";
                    echo "<option value='{$brand['brand_id']}' $selected>{$brand['brand_name']}</option>";
                }
echo "      </select>

            <label class='form-label' for='year'>Year:</label>
            <select class='form-input' name='year' id='year' required>";
                for ($i = 2023; $i >= 2000; $i--) {
                    $selected = $i == $product['year'] ? "selected" : "";
                    echo "<option value='$i' $selected>$i</option>";
                }
echo "      </select>

            <label class='form-label' for='price'>Price:</label>
            <input class='form-input' type='number' id='price' name='price' value='{$product['list_price']}' step='0.01' required>

            <div class='btn-container'>
                <button class='form-button' type='submit'>Submit</button>
            </div>
        </div>
    </div>
</form>
";
?>
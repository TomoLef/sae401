<?php
/**
 * Displays detailed information about a specific product.
 * 
 * This script retrieves product data from the API and displays it on the page.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

include 'header.php';

if (isset($_GET['id'])) {
    $idProduit = $_GET['id'];
} else {
    echo "<h2>Error: Product ID not specified.</h2>";
    exit;
}

/**
 * Construct the API URL for retrieving product information.
 * 
 * @var string $url The API endpoint with query parameters.
 */
$url = "https://saevelo.alwaysdata.net/api_request/api.php?action=produit&id=$idProduit";
$data = array('action' => 'product', 'id' => $idProduit);
$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'GET',
        'content' => json_encode($data),
    ),
);
$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$product = json_decode($response, true);

if (empty($product)) {
    echo "<h2>Error: Product not found.</h2>";
    exit;
}

echo "<h2>{$product['category']['category_name']} by {$product['brand']['brand_name']}</h2>";
echo "
<div class='product-container'>
    <div class='product-image'>
        <img src='../img/velo.png' alt='{$product['category']['category_name']}'>
    </div>
    <div class='product-details'>
        <h2>{$product['product_name']}</h2>
        <h2 class='stars'>★★★★★</h2>
        <h2 class='price-tag'>{$product['list_price']} €</h2>
        <p class='description'>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut et massa mi. Aliquam in hendrerit urna.
        </p>
        <h2 class='btn btn-large btn-red'>Buy now</h2>
    </div>
</div>
";
?>
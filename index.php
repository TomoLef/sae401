<?php
/**
 * Displays the homepage with categories, products, and brands.
 * 
 * This script retrieves data from the API to display categories, featured products, 
 * and brands. It also includes a map and a carousel for navigation.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

include 'page/header.php';
?>

<!-- Login form container -->
<div id="form-container" class="cacher form-container">
    <div id="form-content"></div>
</div>

<section>
    <!-- Carousel for categories -->
    <div class="carousel-container">
        <div class="carousel-btn" id="leftBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: #E3E8EF;">
                <path d="m4.431 12.822 13 9A1 1 0 0 0 19 21V3a1 1 0 0 0-1.569-.823l-13 9a1.003 1.003 0 0 0 0 1.645z"></path>
            </svg>
        </div>
        <div class="carousel-track">
            <div class="carousel-items" id="carouselItems">
                <?php
                /**
                 * Retrieve categories from the API and display them in the carousel.
                 */
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
                foreach ($categories as $category) {
                    echo "
                        <a href='page/allProducts.php?min=0&max=12000&category={$category['category_id']}'>
                            <h2 class='genre'>{$category['category_name']}</h2>
                        </a>
                    ";
                }
                ?>
            </div>
        </div>
        <div class="carousel-btn" id="rightBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: #E3E8EF;">
                <path d="M5.536 21.886a1.004 1.004 0 0 0 1.033-.064l13-9a1 1 0 0 0 0-1.644l-13-9A1 1 0 0 0 5 3v18a1 1 0 0 0 .536.886z"></path>
            </svg>
        </div>
    </div>
</section>

<section>
    <!-- Map and featured products -->
    <div class="col s5 pull-s7">
        <h2>Our store in the world</h2>
        <div class="map" id="map" style="border-radius: 20px"></div>
    </div>
    <div class="col s7 push-s5">
        <h2>Our latest models</h2>
        <?php
        /**
         * Retrieve featured products from the API and display them.
         */
        $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=produit&limit=2";
        $data = array('action' => 'product', 'limit' => 2);
        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'GET',
                'content' => json_encode($data),
            ),
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $products = json_decode($response, true);
        foreach ($products as $product) {
            echo "
            <a href='page/produit.php?id={$product['product_id']}' class='div-product'>
                <div>
                    <img src='img/velo.png' alt='velo' class='img-velo'>
                    <div class='product-details'>
                        <h2>{$product['product_name']}</h2>
                        <div class='product-info'>
                            <h3>{$product['list_price']} €</h3>
                            <h3>{$product['category']['category_name']}</h3>
                        </div>
                    </div>
                </div>
            </a>
            ";
        }
        ?>
        <a href="page/allProducts.php"><p>All models...</p></a>
    </div>
</section>

<section>
    <!-- Brands section -->
    <h2>All the brands that accompany us in your journey</h2>
    <?php
    /**
     * Retrieve brands from the API and display them.
     */
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
    $limit = 0;
    echo "<div class='brand-ligne'>";
    foreach ($brands as $brand) {
        if ($limit % 4 == 0 && $limit != 0) {
            echo "</div>";
            echo "<div class='brand-ligne'>";
        }
        echo "
            <a href='page/allProducts.php?min=0&max=12000&brand={$brand['brand_id']}'>
                <div class='div-brand'>
                    <img src='img/velo.png' alt='brand' class='img-brand'>
                    <h3>{$brand['brand_name']}</h3>
                </div>
            </a>
        ";
        $limit++;
    }
    echo "</div>";
    ?>
</section>
</body>
</html>
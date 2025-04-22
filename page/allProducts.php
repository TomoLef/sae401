<?php include 'header.php'; ?>
    <!-- formulaire de connexion -->
    <div id="login-form-container" class="cacher form-container">
        <div id="login-form-content"></div>
    </div>
    <section>
        <div class="filtre">
            <h2>Products :</h2>
            <form class="filter-form" method="GET" action="">
                <div class="slider-box">
                    <span id="min-price">0 €</span>
                    <div class="slider-wrapper">
                    <input type="range" id="price-min" name="min" min="0" max="12000" value="0" step="10">
                    <input type="range" id="price-max" name="max" min="0" max="12000" value="12000" step="10">
                    </div>
                    <span id="max-price">12 000 €</span>
                </div>
                <select name="year">
                    <?php
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
                        echo "<option disabled selected>Year</option>";
                        foreach ($years as $year) {
                            echo "<option value='{$year}'>{$year}</option>";
                        }
                    ?>
                </select>
                <select name="category">
                    <?php
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
                        echo "<option disabled selected>Category</option>";
                        foreach ($categories as $category) {
                            echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
                        }
                    ?>
                </select>
                <select name="brand">
                    <?php
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
                        echo "<option disabled selected>Brand</option>";
                        foreach ($brands as $brand) {
                            echo "<option value='{$brand['brand_id']}'>{$brand['brand_name']}</option>";
                        }
                    ?>
                </select>

                <button type="submit">Go</button>
            </form>
        </div>
        <?php
                if (isset($_GET['min']) && isset($_GET['max'])) {
                    if (isset($_GET['year'])) {
                        $year = "&year={$_GET['year']}";
                    } else {
                        $year = "";
                    }
                    if (isset($_GET['category'])) {
                        $category = "&category={$_GET['category']}";
                    } else {
                        $category = "";
                    }
                    if (isset($_GET['brand'])) {
                        $brand = "&brand={$_GET['brand']}";
                    } else {
                        $brand = "";
                    }
                    $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=produit&priceMin={$_GET['min']}&priceMax={$_GET['max']}$year$category$brand";
                    $data = array(
                        'action' => 'produit',
                        'priceMin' => $_GET['min'],
                        'priceMax' => $_GET['max'],
                        'year' => isset($_GET['year']) ? $_GET['year'] : null,
                        'category' => isset($_GET['category']) ? $_GET['category'] : null,
                        'brand' => isset($_GET['brand']) ? $_GET['brand'] : null,
                    );
                } else {
                    $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=produit";
                    $data = array('action' => 'produit');
                }
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

                $elementParPage = 20;
                $total = 0;
                foreach ($products as $product) {
                    $total++;
                }
                $pagesTotales = ceil($total / $elementParPage);
                if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $pagesTotales) {
                    $pageCourante = intval($_GET['page']);
                } else {
                    $pageCourante = 1;
                }

                $nbprod = 0;
                echo "<div class='product-row'>";
                foreach ($products as $key => $product) {
                    if ($key < ($pageCourante-1)*$elementParPage || $key > $pageCourante*$elementParPage) {
                        continue;
                    }
                    if ($nbprod % 4 == 0) {
                        echo "</div>";
                        echo "<div class='product-row'>";
                    }
                    echo "
                        <a href='produit.php?id={$product['product_id']}' class=''>
                            <div class='product-card'>
                                <img src='../img/velo.png' alt='Vélo' class='product-image'>
                                <div class='product-info'>
                                    <div class='product-price'><h3>{$product['list_price']} €</h3></div>
                                    <div>
                                        <div class='product-title'><h3>{$product['brand']['brand_name']}</h3></div>
                                        <div class='product-rating'><h3>★★★★★</h3></div>
                                    </div>
                                    
                                </div>
                                <div class='product-description'>
                                    <p>{$product['product_name']}</p>
                                </div>
                            </div>
                        </a>
                    ";
                    $nbprod++;
                }
                echo "</div>";
            ?>
    </section>
    <section>
        <?php
            $queryParams = $_GET;
            echo 'Page : ';
            for ($i = 1; $i <= $pagesTotales; $i++) {
                $queryParams['page'] = $i;
                $queryString = http_build_query($queryParams);
                if ($i == $pageCourante) {
                    echo ' [ ' . $i . ' ] ';
                } else {
                    echo ' <a href="?' . $queryString . '">' . $i . '</a> ';
                }
            }
        ?>
    </section>
</body>
</html>
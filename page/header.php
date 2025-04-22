<?php
/**
 * Displays the header and navigation bar for the website.
 * 
 * This script dynamically generates the navigation bar based on the user's role 
 * and includes links to various pages, a search bar, and a dropdown menu for logged-in users.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    /**
     * Determine the relative path for navigation links based on the current URL.
     * 
     * @var string $url The current URL.
     * @var array $urlParts The parts of the URL split by '/'.
     * @var string $page The relative path for navigation links.
     */
    $url = $_SERVER['REQUEST_URI'];
    $urlParts = explode('/', $url);
    foreach ($urlParts as $part) {
        if ($part == 'page') {
            $page = "";
            break;
        } else {
            $page = "page/";
        }
    }
    ?>
    <!-- Import Google Icon Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Import Materialize CSS -->
    <link type="text/css" rel="stylesheet" href="../CSS/materialize.min.css" media="screen,projection"/>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/form.css">
    <link rel="stylesheet" href="../CSS/allProduct.css">
    <link rel="stylesheet" href="../CSS/product.css">
    <link rel="stylesheet" href="../CSS/carousel.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <script type="text/javascript" src="../JS/script.js"></script>
    <script type="text/javascript" src="../JS/materialize.min.js"></script>
    <script type="text/javascript" src="../JS/form.js"></script>
    <!-- Let browser know website is optimized for mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <?php
    session_start();
    session_regenerate_id();
    ?>
    <title>Bike Stores</title>
</head>
<body>
<header class="header">
    <div class="container">
        <div class="row valign-wrapper">
            <!-- Logo -->
            <a href="../index.php" class="col s3 logo">
                <img src="../img/logo.png" alt="Bike Logo">
                <span class="brand-name">Bike Stores</span>
            </a>
            <!-- Search bar -->
            <div class="col s6">
                <div class="input-field search-bar">
                    <input type="text" id="search" placeholder="Search a product...">
                </div>
            </div>

            <!-- Navigation icons -->
            <div class="col s3 nav-icons right-align">
                <a href="<?php echo $page; ?>allProducts.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                        <path d="M20 3H4a2 2 0 0 0-2 2v2a2 2 0 0 0 1 1.72V19a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.72A2 2 0 0 0 22 7V5a2 2 0 0 0-2-2zM4 5h16v2H4zm1 14V9h14v10z"></path>
                        <path d="M8 11h8v2H8z"></path>
                    </svg>
                    <br><h3>Products</h3>
                </a>
                <a href="<?php echo $page; ?>legalMention.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;">
                    <path d="M19 2.01H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.998 5 19.815 5 19.01c0-.101.009-.191.024-.273.112-.575.583-.717.987-.727H20c.018 0 .031-.009.049-.01H21V4.01c0-1.103-.897-2-2-2zm0 14H5v-11c0-.806.55-.988 1-1h7v7l2-1 2 1v-7h2v12z">
                    </path>
                </svg>
                    <br><h3>Legal mention</h3>
                </a>
                <?php
                /**
                 * Display dropdown menu for logged-in users based on their role.
                 * 
                 * @var string|null $employeeRole The role of the logged-in employee.
                 */
                $employeeRole = isset($_COOKIE['employee_role']) ? $_COOKIE['employee_role'] : null;

                if (isset($employeeRole)) {
                    echo "
                        <a class='dropdown-trigger btn' href='#' data-target='dropdown1'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' style='fill: rgba(0, 0, 0, 1);'>
                                <path d='M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z'></path>
                            </svg>
                        </a>
                        <ul id='dropdown1' class='dropdown-content'>
                            <li class='divider' tabindex='-1'></li>
                    ";
                    if ($employeeRole == "it") {
                        echo "
                            <li id='allEmployees-btn'><p>All Employees</p></li>
                        ";
                    }
                    if ($employeeRole == "chief" || $employeeRole == "it") {
                        echo "
                            <li id='storeEmployee-btn'><p>Your Employees</p></li>
                            <li id='newEmployee-btn'><p>New Employee</p></li>
                        ";
                    }
                    echo "
                            <li id='info-btn'><p>Info</p></li>
                            <li id='product-btn'><p>Product</p></li>
                            <li id='stock-btn'><p>Stock</p></li>
                            <li><a href='/page/deconnection.php'><p>Logout</p></a></li>
                        </ul>
                    ";
                } else {
                    echo '
                    <div id="login-btn" class="login-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                            <path d="M12 2A10.13 10.13 0 0 0 2 12a10 10 0 0 0 4 7.92V20h.1a9.7 9.7 0 0 0 11.8 0h.1v-.08A10 10 0 0 0 22 12 10.13 10.13 0 0 0 12 2zM8.07 18.93A3 3 0 0 1 11 16.57h2a3 3 0 0 1 2.93 2.36 7.75 7.75 0 0 1-7.86 0zm9.54-1.29A5 5 0 0 0 13 14.57h-2a5 5 0 0 0-4.61 3.07A8 8 0 0 1 4 12a8.1 8.1 0 0 1 8-8 8.1 8.1 0 0 1 8 8 8 8 0 0 1-2.39 5.64z"></path>
                            <path d="M12 6a3.91 3.91 0 0 0-4 4 3.91 3.91 0 0 0 4 4 3.91 3.91 0 0 0 4-4 3.91 3.91 0 0 0-4-4zm0 6a1.91 1.91 0 0 1-2-2 1.91 1.91 0 0 1 2-2 1.91 1.91 0 0 1 2 2 1.91 1.91 0 0 1-2 2z"></path>
                        </svg>
                        <br><h3>Login</h3>
                    </div>
                    ';
                }
                ?>
            </div>
        </div>
    </div>
</header>
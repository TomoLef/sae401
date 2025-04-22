<?php
/**
 * Displays a login form.
 * 
 * This script generates a form for user login and displays error messages if any.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

session_start();

echo '
    <div class="form-header">Write your email and password</div>';
    if (isset($_SESSION["erreur"])) {
        echo "<div class=\"error-message\">".$_SESSION["erreur"]."</div>";
        unset($_SESSION["erreur"]);
    }
echo '
    <form method="POST" action="/page/login.php">
        <label class="form-label" for="email">Email:</label>
        <input class="form-input" type="email" id="email" name="email" placeholder="JohnDoe@gmail.com" required>

        <label class="form-label" for="password">Password:</label>
        <input class="form-input" type="password" id="password" name="password" placeholder="Password123" required>

        <div class="btn-container">
            <button id="close-btn" class="form-button" type="button">Close</button>
            <button class="form-button" type="submit">Connect</button>
        </div>
    </form>
';
?>
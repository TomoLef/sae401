<?php
/**
 * Displays a form with the logged-in employee's information.
 * 
 * This script retrieves employee data from the API and generates a form 
 * for modifying the employee's information.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

session_start();

/**
 * Construct the API URL for retrieving employee information.
 * 
 * @var string $url The API endpoint with query parameters.
 */
$url = "https://saevelo.alwaysdata.net/api_request/api.php?action=employe&id=" . $_SESSION["compte"]["employee_id"];
$data = array('action' => 'employee', 'id' => $_SESSION["compte"]["employee_id"]);
$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'GET',
        'content' => json_encode($data),
    ),
);
$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$employee = json_decode($response, true);

/**
 * Display the employee's information in a form.
 */
echo "
    <div class='form-header'>
        <h2>{$employee['employee_role']} : {$employee['employee_name']}</h2><br>
        <h2>From : {$employee['store']['store_name']}</h2>
    </div>";
if (isset($_SESSION["erreur"])) {
    echo "<div class=\"error-message\">{$_SESSION["erreur"]}</div>";
    unset($_SESSION["erreur"]);
}
echo "
    <form method='POST' action='page/modifEmployee.php'>
        <label class='form-label' for='nom'>Name:</label>
        <input class='form-input' type='nom' id='nom' name='nom' value='{$employee['employee_name']}' required>

        <label class='form-label' for='email'>Email:</label>
        <input class='form-input' type='email' id='email' name='email' value='{$employee['employee_email']}' required>

        <label class='form-label' for='password'>Password:</label>
        <input class='form-input' type='password' id='password' name='password' required>

        <label class='form-label' for='confirmPassword'>Confirm Password:</label>
        <input class='form-input' type='confirmPassword' id='confirmPassword' name='confirmPassword' required>

        <div class='btn-container'>
            <button id='close-btn' class='form-button' type='button'>Close</button>
            <button class='form-button' type='submit'>Modify</button>
        </div>
    </form>
";
?>
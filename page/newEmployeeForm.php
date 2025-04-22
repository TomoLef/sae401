<?php
/**
 * Displays a form to add a new employee.
 * 
 * This script generates a form for adding a new employee and retrieves store data
 * from the API if the user has the appropriate role.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

session_start();

echo "
    <div class='form-header'>
        <h2>Add new employee:</h2>
    </div>";
echo "
    <form method='POST' action='page/newEmployee.php'>
        <label class='form-label' for='nom'>Name:</label>
        <input class='form-input' type='nom' id='nom' name='nom' required>";
        $employeeRole = isset($_COOKIE['employee_role']) ? $_COOKIE['employee_role'] : null;
        if ($employeeRole == 'it') {
            echo "
            <select class='form-input' name='store' id='store' required>
            <option disabled selected>Choose a store</option>
            ";
            $url = "https://saevelo.alwaysdata.net/api_request/api.php?action=magasin";
            $options = array(
                'http' => array(
                    'header'  => "Content-Type: application/json\r\n",
                    'method'  => 'GET',
                ),
            );
            $context  = stream_context_create($options);
            $response = file_get_contents($url, false, $context);
            $stores = json_decode($response, true);
            foreach ($stores as $store) {
                echo "<option value='{$store['store_id']}'>{$store['store_name']}</option>";
            }
            echo "
            </select>
            <select class='form-input' name='role' id='role' required>
            <option disabled selected>Choose a role</option>
            <option value='employee'>Employee</option>
            <option value='chief'>Chief</option>
            ";
        }
echo "  </select>

        <label class='form-label' for='email'>Email:</label>
        <input class='form-input' type='email' id='email' name='email' required>

        <label class='form-label' for='password'>Password:</label>
        <input class='form-input' type='password' id='password' name='password' required>

        <label class='form-label' for='confirmPassword'>Confirm Password:</label>
        <input class='form-input' type='confirmPassword' id='confirmPassword' name='confirmPassword' required>

        <div class='btn-container'>
            <button id='close-btn' class='form-button' type='button'>Close</button>
            <button class='form-button' type='submit'>Add</button>
        </div>
    </form>
";
?>
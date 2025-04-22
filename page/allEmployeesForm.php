<?php
/**
 * Displays a form to select an employee from all employees.
 * 
 * This script retrieves employee data from the API and generates a dropdown menu
 * for selecting an employee. The selected employee is submitted to `infoEmployee.php`.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

session_start();

$url = "https://saevelo.alwaysdata.net/api_request/api.php?action=employe";
$data = array('action' => 'employe');
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

echo "<form class='filter-form' method='POST' id='employeeForm' action='page/infoEmployee.php'>";
    echo "<label class='form-label' for='employee'>Select an employee:</label>";
    echo "<select class='form-input' name='employee' id='employee'>";
        echo "<option disabled selected>Employee</option>";
        foreach ($employee as $emp) {
            echo "<option value='{$emp['employee_id']}'>{$emp['employee_name']}</option>";
        }
    echo "</select>";
    echo "<button id='employeeSubmit' class='form-button' type='submit'>Select</button>";
echo "</form>";
?>
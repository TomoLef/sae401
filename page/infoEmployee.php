<?php
/**
 * Displays detailed information about a specific employee.
 * 
 * This script retrieves employee data from the API and displays it on the page.
 * 
 * @package SAE401
 * @version 1.0
 * @license MIT
 */

if (isset($_POST['employee'])) {
    $idEmployee = $_POST['employee'];
} else {
    echo "<h2>Error: Employee ID not specified.</h2>";
    exit;
}

/**
 * Construct the API URL for retrieving employee information.
 * 
 * @var string $url The API endpoint with query parameters.
 */
$url = "https://saevelo.alwaysdata.net/api_request/api.php?action=employe&id=$idEmployee";
$data = array('action' => 'employee', 'id' => $idEmployee);
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

if (empty($employee)) {
    echo "<h2>Error: Employee not found.</h2>";
    exit;
}
?>

<?php include 'header.php'; ?>
<section>
    <div class="employee-container">
        <div class="employee-header">
            <h2>Role: <?= $employee['employee_role'] ?></h2>
            <h2>Name: <?= $employee['employee_name'] ?></h2>
            <h2>Store: <?= $employee['store']['store_name'] ?></h2>
            <h2>Email: <?= $employee['employee_email'] ?></h2>
        </div>
    </div>
</section>
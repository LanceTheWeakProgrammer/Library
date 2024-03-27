<?php
session_start();

include('C:\xampp\htdocs\student_library_system\api\dbcon.php');
header("Content-type: application/json");

$action = isset($_GET['action']) ? $_GET['action'] : exit();
$output = array('error' => false);

if ($action == 'getDashboardData') {
    try {
        // Assuming you have the user ID stored in the session
        $userId = $_SESSION['user_id'];

        // Fetch dashboard data
        $stmt = $conn->prepare("CALL getDashboardData()");
        $stmt->execute();
        $dashboardData = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Fetch user data based on user ID using stored procedure
        $stmtUser = $conn->prepare("CALL GetUserById(:userId)");
        $stmtUser->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtUser->execute();
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
        $stmtUser->closeCursor();

        // Add user profile to dashboard data
        $dashboardData['userProfile'] = $userData;

        $output['data'] = $dashboardData;
    } catch (PDOException $e) {
        $output['error'] = true;
        $output['message'] = $e->getMessage();
    }
} else {
    $output['error'] = true;
    $output['message'] = 'Invalid action specified';
}

echo json_encode($output);
?>

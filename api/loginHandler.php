<?php
session_start();

include('C:\xampp\htdocs\student_library_system\api\dbcon.php');
header("Content-type: application/json");

$output = array('error' => false);

$user = $_POST['username'];
$pass = $_POST['password'];

if ($user == '') {
    $output['error'] = true;
    $output['message'] = "Username is required";
} else if ($pass == '') {
    $output['error'] = true;
    $output['message'] = "Password is required";
} else {

    try {
        $sql = "SELECT * FROM user WHERE Username = '" . $user . "' AND Password = '" . md5($pass) . "' AND Active_ind = 1";
        $stmt = $conn->prepare($sql);

        $stmt->execute();

        if ($stmt) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row == []) {
                $output['error'] = true;
                $output['message'] = "Incorrect username or password";
            } else {
                if (md5($pass) == $row['Password']) {
                    $_SESSION['user_id'] = $row['User_id'];
                } else {
                    $output['error'] = true;
                    $output['message'] = "Incorrect password";
                }
            }
        }
    } catch (PDOException $err) {
        echo "Error: " . $err->getMessage();
    }
}

echo json_encode($output);
die();

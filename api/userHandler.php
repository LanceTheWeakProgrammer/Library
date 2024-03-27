<?php
include('C:\xampp\htdocs\student_library_system\api\dbcon.php');
header("Content-type: application/json");

$action = isset($_GET['action']) ? $_GET['action'] : exit();
$output = array('error' => false);

switch ($action) {
    case "getAllUsers":
        try {
            $sql = "call getUser()";
            $stmt = $conn->prepare($sql);

            $stmt->execute();
            
            if ($stmt) {
                $users = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($users, $row);
                }
                $output['users'] = $users;
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in getAllUsers: ' . $err->getMessage();
        }
        break;

    case "saveUser":
        $username = isset($_POST['Username']) ? $_POST['Username'] : '';
        $password = isset($_POST['Password']) ? $_POST['Password'] : '';
        $role = isset($_POST['role']) ? $_POST['role'] : '';
        $first_name = isset($_POST['First_name']) ? $_POST['First_name'] : '';
        $last_name = isset($_POST['Last_name']) ? $_POST['Last_name'] : '';
        $email = isset($_POST['Email']) ? $_POST['Email'] : '';
        $contact_number = isset($_POST['Contact_number']) ? $_POST['Contact_number'] : '';

        try {
            $sql = "call insertUser('" . $username . "',
                                    '" . md5($password) . "',
                                    '" . $role . "',
                                    '" . $first_name . "', 
                                    '" . $last_name . "', 
                                    '" . $email . "', 
                                    '" . $contact_number . "')";

            $stmt = $conn->prepare($sql);

            $stmt->execute();

            if ($stmt) {
                $output['error'] = false;
                $output['message'] = "You've successfully added a new user: " . $first_name . " " . $last_name;
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in saveUser: ' . $err->getMessage();
        }
        break;

    case "updateUser":
        $user_id = isset($_POST['User_id']) ? $_POST['User_id'] : '';
        $role = isset($_POST['role']) ? $_POST['role'] : '';
        $first_name = isset($_POST['First_name']) ? $_POST['First_name'] : '';
        $last_name = isset($_POST['Last_name']) ? $_POST['Last_name'] : '';
        $email = isset($_POST['Email']) ? $_POST['Email'] : '';
        $contact_number = isset($_POST['Contact_number']) ? $_POST['Contact_number'] : '';

        try {
            $sql = "call updateUser('" . $user_id . "',
                                    '" . $role . "',
                                    '" . $first_name . "', 
                                    '" . $last_name . "', 
                                    '" . $email . "', 
                                    '" . $contact_number . "')";

            $stmt = $conn->prepare($sql);

            $stmt->execute();

            if ($stmt) {
                $output['error'] = false;
                $output['message'] = "You've successfully updated user ID: " . $user_id;
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in updateUser: ' . $err->getMessage();
        }
        break;

    case "updateUserStatus":
        $user_id = isset($_POST['User_id']) ? $_POST['User_id'] : '';
        $active_ind = isset($_POST['Active_ind']) ? $_POST['Active_ind'] : '';

        if ($active_ind == 1) {
            $msg = "inactive";
            $active_ind = 0;
        } else {
            $msg = "active";
            $active_ind = 1;
        }

        try {
            $sql = "call updateUserStatus('" . $user_id . "', '" . $active_ind . "')";
            $stmt = $conn->prepare($sql);

            $stmt->execute();

            if ($stmt) {
                $output['error'] = false;
                $output['message'] = "You've successfully set to " . $msg . " user ID: " . $user_id;
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in updateUserStatus: ' . $err->getMessage();
        }
        break;

    default:
        $output['error'] = true;
        $output['message'] = "Invalid action specified.";
}

echo json_encode($output);

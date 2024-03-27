<?php
include('C:\xampp\htdocs\student_library_system\api\dbcon.php');
header("Content-type: application/json");

$action = isset($_GET['action']) ? $_GET['action'] : exit();
$output = array('error' => false);

switch ($action) {
    case "getAllStudents":
        try {
            $sql = "call getStudents()";
            $stmt = $conn->prepare($sql);

            $stmt->execute();

            if ($stmt) {
                $students = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($students, $row);
                }
                $output['students'] = $students;
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in getAllStudents: ' . $err->getMessage();
        }
        break;

    case "insertStudent":
        $first_name = isset($_POST['First_name']) ? $_POST['First_name'] : '';
        $last_name = isset($_POST['Last_name']) ? $_POST['Last_name'] : '';
        $birthday = isset($_POST['Birthday']) ? $_POST['Birthday'] : '';
        $gender = isset($_POST['Gender']) ? $_POST['Gender'] : '';
        $contact_number = isset($_POST['Contact_number']) ? $_POST['Contact_number'] : '';
        $email = isset($_POST['Email']) ? $_POST['Email'] : '';
        $year = isset($_POST['Year']) ? $_POST['Year'] : '';
        $section = isset($_POST['Section']) ? $_POST['Section'] : '';
        $course = isset($_POST['Course']) ? $_POST['Course'] : '';
        $address = isset($_POST['Address']) ? ($_POST['Address'] == '' ? 'N/A' : $_POST['Address']) : 'N/A';

        try {
            $sql = "call insertStudent('" . $first_name . "', 
                                        '" . $last_name . "', 
                                        '" . $birthday . "', 
                                        '" . $gender . "', 
                                        '" . $contact_number . "', 
                                        '" . $email . "', 
                                        '" . $year . "', 
                                        '" . $section . "', 
                                        '" . $course . "', 
                                        '" . $address . "')";

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            if ($stmt) {
                $output['error'] = false;
                $output['message'] = "You've successfully added a new student: " . $first_name . " " . $last_name;
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in insertStudent: ' . $err->getMessage();
        }
        break;

    case "updateStudent":
        $student_id = isset($_POST['Student_id']) ? $_POST['Student_id'] : '';
        $first_name = isset($_POST['First_name']) ? $_POST['First_name'] : '';
        $last_name = isset($_POST['Last_name']) ? $_POST['Last_name'] : '';
        $birthday = isset($_POST['Birthday']) ? $_POST['Birthday'] : '';
        $gender = isset($_POST['Gender']) ? $_POST['Gender'] : '';
        $contact_number = isset($_POST['Contact_number']) ? $_POST['Contact_number'] : '';
        $email = isset($_POST['Email']) ? $_POST['Email'] : '';
        $year = isset($_POST['Year']) ? $_POST['Year'] : '';
        $section = isset($_POST['Section']) ? $_POST['Section'] : '';
        $course = isset($_POST['Course']) ? $_POST['Course'] : '';
        $address = isset($_POST['Address']) ? ($_POST['Address'] == '' ? 'N/A' : $_POST['Address']) : 'N/A';
    
        try {
            $sql = "call updateStudent('" . $student_id . "',
                                        '" . $first_name . "', 
                                        '" . $last_name . "', 
                                        '" . $birthday . "', 
                                        '" . $gender . "', 
                                        '" . $contact_number . "', 
                                        '" . $email . "', 
                                        '" . $year . "', 
                                        '" . $section . "', 
                                        '" . $course . "', 
                                        '" . $address . "')";
    
            $stmt = $conn->prepare($sql);
    
            $stmt->execute();
    
            if ($stmt) {
                $output['error'] = false;
                $output['message'] = "You've successfully updated student id: " . $student_id . "";
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in updateStudent: ' . $err->getMessage();
        }
        break;        

    case "updateStudentStatus":
        $student_id = isset($_POST['Student_id']) ? $_POST['Student_id'] : '';
        $active_ind = isset($_POST['Active_ind']) ? $_POST['Active_ind'] : '';

        if ($active_ind == 1) {
            $msg = "inactive";
            $active_ind = 0;
        } else {
            $msg = "active";
            $active_ind = 1;
        }

        try {
            $sql = "call updateStudentStatus('" . $student_id . "', '" . $active_ind . "')";
            $stmt = $conn->prepare($sql);

            $stmt->execute();

            if ($stmt) {
                $output['error'] = false;
                $output['message'] = "You've successfully set to " . $msg . " student ID: " . $student_id . "";
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in updateStudentStatus: ' . $err->getMessage();
        }
        break;

    case "searchStudent":
        try {
            $first_name = isset($_GET['First_name']) ? $_GET['First_name'] : null;
            $last_name = isset($_GET['Last_name']) ? $_GET['Last_name'] : null;

            $sql = "CALL searchStudent(:first_name, :last_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt) {
                $students = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($students, $row);
                }
                $output['students'] = $students;
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in searchStudent: ' . $err->getMessage();
        }
        break;

    default:
        $output['error'] = true;
        $output['message'] = "Invalid action specified.";
}

echo json_encode($output);
?>

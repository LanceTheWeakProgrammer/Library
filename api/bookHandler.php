<?php
include('C:\xampp\htdocs\student_library_system\api\dbcon.php');
header("Content-type: application/json");

$action = isset($_GET['action']) ? $_GET['action'] : exit();
$output = array('error' => false);

switch ($action) {
    case "searchBookRequest":
        $requested_by = isset($_GET['RequestedBy']) ? $_GET['RequestedBy'] : null;

        try {
            $sql = "CALL searchBookRequest(:requested_by)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':requested_by', $requested_by, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $requests = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($requests, $row);
                }
                $output['requests'] = $requests;
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in searchBookRequest: ' . $err->getMessage();
        }
        break;

    case "getAllBooks":
        try {
            $sql = "call getBooks()";
            $stmt = $conn->prepare($sql);

            $stmt->execute();
            if ($stmt) {
                $books = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($books, $row);
                }
                $output['books'] = $books;
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in getAllBooks: ' . $err->getMessage();
        }
        break;

    case "saveBook":
        $name = isset($_POST['Title']) ? $_POST['Title'] : '';
        $qty_stock = isset($_POST['Qty_stock']) ? $_POST['Qty_stock'] : '';
        $pub_date = isset($_POST['Pub_date']) ? $_POST['Pub_date'] : '';

        try {
            $sql = "call insertBook('" . $name . "',
                                    '" . $qty_stock . "', 
                                    '" . $pub_date . "')";

            $stmt = $conn->prepare($sql);

            $stmt->execute();
            if ($stmt) {
                $output['error'] = false;
                $output['message'] = "You've successfully added a new book " . $name;
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in saveBook: ' . $err->getMessage();
        }
    break;

    case "updateBook":
        $book_id = isset($_POST['Book_id']) ? $_POST['Book_id'] : '';
        $name = isset($_POST['Title']) ? $_POST['Title'] : '';
        $new_qty_stock = isset($_POST['Qty_stock']) ? $_POST['Qty_stock'] : '';
        $pub_date = isset($_POST['Pub_date']) ? $_POST['Pub_date'] : '';
    
        try {

            if ($new_qty_stock < 0) {
                throw new Exception("Invalid quantity entered.");
            }

            $sqlGetCurrentQty = "SELECT Qty_stock, Qty_issued FROM bookinventory WHERE Book_id = :book_id";
            $stmtGetCurrentQty = $conn->prepare($sqlGetCurrentQty);
            $stmtGetCurrentQty->bindParam(':book_id', $book_id, PDO::PARAM_INT);
            $stmtGetCurrentQty->execute();
            $currentQty = $stmtGetCurrentQty->fetch(PDO::FETCH_ASSOC);
            $stmtGetCurrentQty->closeCursor();
    
            if ($currentQty) {
                $current_qty_stock = $currentQty['Qty_stock'];
                $current_qty_issued = $currentQty['Qty_issued'];

                $qty_difference = $new_qty_stock - $current_qty_stock;

                $sqlUpdateBook = "CALL updateBook(:book_id, :name, :new_qty_stock, :pub_date)";
                $stmtUpdateBook = $conn->prepare($sqlUpdateBook);
                $stmtUpdateBook->bindParam(':book_id', $book_id, PDO::PARAM_INT);
                $stmtUpdateBook->bindParam(':name', $name, PDO::PARAM_STR);
                $stmtUpdateBook->bindParam(':new_qty_stock', $new_qty_stock, PDO::PARAM_INT);
                $stmtUpdateBook->bindParam(':pub_date', $pub_date, PDO::PARAM_STR);
                $stmtUpdateBook->execute();

                $new_qty_issued = max(0, $current_qty_issued - $qty_difference);

                $sqlUpdateQtyIssued = "UPDATE bookinventory SET Qty_issued = :new_qty_issued WHERE Book_id = :book_id";
                $stmtUpdateQtyIssued = $conn->prepare($sqlUpdateQtyIssued);
                $stmtUpdateQtyIssued->bindParam(':new_qty_issued', $new_qty_issued, PDO::PARAM_INT);
                $stmtUpdateQtyIssued->bindParam(':book_id', $book_id, PDO::PARAM_INT);
                $stmtUpdateQtyIssued->execute();
    
                $output['error'] = false;
                $output['message'] = "You've successfully updated book: " . $name;
            } else {
                $output['error'] = true;
                $output['message'] = "Book not found.";
            }
        } catch (Exception $ex) {
            $output['error'] = true;
            $output['message'] = $ex->getMessage();
        }
    break;
        

    case "saveRequest":
        $student_id = isset($_POST['Student_id']) ? $_POST['Student_id'] : '';
        $book_id = isset($_POST['Book_id']) ? $_POST['Book_id'] : '';
        $requestedby = isset($_POST['RequestedBy']) ? $_POST['RequestedBy'] : '';
        $role = isset($_POST['Role']) ? $_POST['Role'] : '';
        $requestedfor = isset($_POST['RequestedFor']) ? $_POST['RequestedFor'] : '';
        $requeststatus = isset($_POST['Requeststatus']) ? $_POST['Requeststatus'] : '';
        $qty_stock = isset($_POST['Qty_stock']) ? $_POST['Qty_stock'] : '';
        $qty_issued = isset($_POST['Qty_issued']) ? $_POST['Qty_issued'] : '';
        $qty_requested = isset($_POST['Qty_requested']) ? $_POST['Qty_requested'] : '';
    
        try {
            if ($role == 'Librarian') {
                $requeststatus = 'Approved';
                $sql = "call insertBookRequest('" . $student_id . "',
                                                '" . $book_id . "',
                                                '" . $requestedby . "',
                                                '" . $requestedfor . "',
                                                '" . $qty_requested . "',
                                                '" . $requeststatus . "',
                                                '" . $requestedby . "')";
    
                $stmt = $conn->prepare($sql);
                $stmt->execute();
    
                if ($stmt) {
                    $sql2 = "call getRequestBook('" . $book_id . "')";
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->execute();
    
                    if ($stmt2) {
                        $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                        $newQtyStock = ($row['Qty_stock'] - $qty_requested);
                        $newQtyIssued = ($row['Qty_issued'] + $qty_requested);
                        $stmt2->closeCursor();
    
                        $sql3 = "call updateBookStock('" . $book_id . "','" . $newQtyStock . "','" . $newQtyIssued . "')";
                        $stmt3 = $conn->prepare($sql3);
                        $stmt3->execute();
                    }
    
                    $output['error'] = false;
                    $output['message'] = "You've successfully requested a book.";
                } else {
                    $output['error'] = true;
                }
            } else {
                if ($action == 'decline') {
                    $requeststatus = 'Declined';
                    $output['error'] = false;
                    $output['message'] = "Request has been declined.";
                } else {
                    $requeststatus = 'Pending For Approval';
                    $sql = "call insertBookRequest('" . $student_id . "', '" . $book_id . "', '" . $requestedby . "', '" . $requestedfor . "', '" . $qty_requested . "', '" . $requeststatus . "', '" . $requestedby . "')";
    
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
    
                    if ($stmt) {
                        $output['error'] = false;
                        $output['message'] = "You've successfully requested a book.";
                    } else {
                        $output['error'] = true;
                    }
                }
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in saveRequest: ' . $err->getMessage();
        }
    break;         

    case "getAllRequest":
        try {
            $sql = "SELECT * 
                    FROM bookrequest mr 
                    LEFT JOIN student st USING(Student_id) 
                    LEFT JOIN bookinventory mi USING(Book_id)";
            $stmt = $conn->prepare($sql);

            $stmt->execute();
            if ($stmt) {
                $reqs = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($reqs, $row);
                }
                $output['request'] = $reqs;
            } else {
                $output['error'] = true;
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in getAllRequest: ' . $err->getMessage();
        }
    break;

    case "updateRequestStatus":
        $request_id = isset($_POST['Request_id']) ? $_POST['Request_id'] : '';
        $book_id = isset($_POST['Book_id']) ? $_POST['Book_id'] : '';
        $requestedby = isset($_POST['RequestedBy']) ? $_POST['RequestedBy'] : '';
        $role = isset($_POST['Role']) ? $_POST['Role'] : '';
        $requeststatus = isset($_POST['Requeststatus']) ? $_POST['Requeststatus'] : '';
        $qty_requested = isset($_POST['Qty_requested']) ? $_POST['Qty_requested'] : '';
    
        try {
            if ($role == 'Librarian') {
                if ($requeststatus == 'Declined') {
                    $sql = "call updateBookRequest('" . $request_id . "','" . $requeststatus . "','" . $requestedby . "')";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
    
                    if ($stmt) {
                        $output['error'] = false;
                        $output['message'] = "You've successfully declined the request ID: " . $request_id;
                    } else {
                        $output['error'] = true;
                    }
                } elseif ($requeststatus == 'Approved') {
                    $requeststatus = "Approved";
                    $sql = "call updateBookRequest('" . $request_id . "','" . $requeststatus . "','" . $requestedby . "')";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
    
                    if ($stmt) {
                        $sql2 = "call getRequestBook('" . $book_id . "')";
                        $stmt2 = $conn->prepare($sql2);
                        $stmt2->execute();
    
                        if ($stmt2) {
                            $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $newQtyStock = ($row['Qty_stock'] - $qty_requested);
                            $newQtyIssued = ($row['Qty_issued'] + $qty_requested);
                            $stmt2->closeCursor();
    
                            $sql3 = "call updateBookStock('" . $book_id . "','" . $newQtyStock . "','" . $newQtyIssued . "')";
                            $stmt3 = $conn->prepare($sql3);
                            $stmt3->execute();
                        }
    
                        $output['error'] = false;
                        $output['message'] = "You've successfully approved the request ID: " . $request_id;
                    } else {
                        $output['error'] = true;
                    }
                }
            }
        } catch (PDOException $err) {
            $output['error'] = true;
            $output['message'] = 'Error in updateRequestStatus: ' . $err->getMessage();
        }
    break;
    default:
        $output['error'] = true;
        $output['message'] = "Invalid action specified.";
}

echo json_encode($output);
?>

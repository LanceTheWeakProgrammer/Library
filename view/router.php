<?php
session_start();
include('C:\xampp\htdocs\student_library_system\api\dbcon.php');


if (!isset($_SESSION['user_id']) || (trim($_SESSION['user_id']) == '')) {
    header('location: ../index.php');
} else {
    $navbarTitle = 'INVENTORY';
    $url = $_SERVER['REQUEST_URI'];

    try {
        $sql = "SELECT * FROM user WHERE User_id ='" . $_SESSION['user_id'] . "'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if ($stmt) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user = $row['First_name'];
            $role = $row['role'];
            $user_id = $row['User_id'];
            $requestor_name = $row['First_name'] . " " . $row['Last_name'];
        }
    } catch (PDOException $err) {
        echo "Error: " . $err->getMessage();
    }

    switch ($url) {
        case '/student_library_system/view/dashboard.php':
            $dashboardPage = true;
            $studentPage = false;
            $userPage = false;
            $bookPage = false;
            $inventoryPage = false;
            $requestPage = false;
            $issuedLogsPage = false;
            break;
        case '/student_library_system/view/student.php':
            $dashboardPage = false;
            $studentPage = true;
            $userPage = false;
            $bookPage = false;
            $inventoryPage = false;
            $requestPage = false;
            $issuedLogsPage = false;
            break;

        case '/student_library_system/view/user.php':
            $dashboardPage = false;
            $studentPage = false;
            $userPage = true;
            $bookPage = false;
            $inventoryPage = false;
            break;

        case '/student_library_system/view/inventory.php':
            $dashboardPage = false;
            $studentPage = false;
            $userPage = false;
            $bookPage = true;
            $inventoryPage = true;
            $requestPage = false;
            $issuedLogsPage = false;
            break;

        case '/student_library_system/view/request.php':
            $dashboardPage = false;
            $studentPage = false;
            $userPage = false;
            $bookPage = true;
            $inventoryPage = false;
            $requestPage = true;
            $issuedLogsPage = false;
            break;

        case '/student_library_system/view/issuedLogs.php':
            $dashboardPage = false;
            $studentPage = false;
            $userPage = false;
            $bookPage = true;
            $inventoryPage = false;
            $requestPage = false;
            $issuedLogsPage = true;
            break;

        default:
            # code...
            break;
    }

}



?>
<link rel="stylesheet" href="/student_library_system/essentials/design.css">

<div class="container-fluid custom-bg text-light p-4 d-flex align-items-center justify-content-between sticky-top">
    <a class="navbar-brand me-5 fs-3 f-font" href="dashboard.php">
    &nbsp;<?= $navbarTitle ?></a>
    <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Hi, <?= $user; ?>!
        </button>
        <ul class="dropdown-menu">
            <li class="dropdown-item disabled"><?= $role; ?></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
    </div>
</div>
<div class="col-lg-2 bg-dark border-top border-3 border-light" id="dashboard-menu">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid flex-lg-column align-items-stretch">
        <h3 class="mt-2"><?= $role === 'Librarian' ? 'Librarian Panel' : 'Clerk Panel' ?></h3>
        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link text-white fs-5 <?= $dashboardPage ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white fs-5 <?= $studentPage ? 'active' : '' ?>" href="student.php">Student</a>
            </li>
            <?php ?>
            <?php if ($role == "Librarian") { ?>
                <li class="nav-item">
                    <a class="nav-link text-white fs-5 <?= $userPage ? 'active' : '' ?>" href="user.php">User</a>
                </li>
            <?php } ?>
            <li class="nav-item dropdown me-0">
                <a class="nav-link text-white fs-5 dropdown-toggle <?= $bookPage ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">Books</a>
                <ul class="dropdown-menu dropdown-menu-dark larger-dropdown">
                    <li><a class="dropdown-item <?= $inventoryPage ? 'active' : '' ?>" href="inventory.php">Inventory</a></li>
                    <?php if ($role == "Librarian") { ?>
                        <li><a class="dropdown-item <?= $requestPage ? 'active' : '' ?>" href="request.php">Request</a></li>
                    <?php } ?>
                </ul>
            </li>
        </ul>
        </div>
    </div>
    </nav>
</div>




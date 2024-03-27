<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imgs/icon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Dashboard</title>

    <?php include('C:\xampp\htdocs\student_clinic_information\lib\link.php'); ?>
</head>

<body>

        <?php require_once 'router.php'; ?>

        <div class="container-fluid" id="main-content">
            <div class="row">
                <div class="col-lg-10 ms-auto overflow-hidden p-3">
                    <h3 class="mb-4 fw-bold">Dashboard</h3>

                    <!-- Vue.js App -->
                    <div id="app">
                        <div v-if="loading">Loading...</div>

                        <div v-else>
                            <div v-if="error" style="color: red;">{{ errorMessage }}</div>

                            <div v-else>
                                <div class="row">
                                    <div class="col-md-8 mb-4">
                                        <div class="card border-secondary shadow-sm">
                                            <div class="card-body">
                                                <form>
                                                <div style="font-size: 70px; position: absolute; top: 0; left: 0;" class="text-black ms-4">
                                                    #{{ dashboardData.userProfile.User_id }}
                                                </div>
                                                    <div class="text-center mb-3 mt-5">
                                                        <div>
                                                            <i class="fas fa-user-circle fa-10x text-secondary"></i>
                                                        </div>
                                                        <h1 class="mt-3">{{ dashboardData.userProfile.Username }}</h1>
                                                    </div>
                                                    <div>
                                                        <div class="col-md-7 ms-5 me-3">
                                                        </div>
                                                    <div>
                                                             <div class="mb-3">
                                                                <label for="completeName" class="form-label">Complete Name</label>
                                                                <input type="text" class="form-control fs-4" id="completeName" v-model="dashboardData.userProfile.First_name + ' ' + dashboardData.userProfile.Last_name" disabled>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="role" class="form-label">Role</label>
                                                                <input type="text" class="form-control fs-4" id="role" v-model="dashboardData.userProfile.role" disabled>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="email" class="form-label">Email</label>
                                                                <input type="email" class="form-control fs-4" id="email" v-model="dashboardData.userProfile.Email" disabled>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="contactNumber" class="form-label">Contact Number</label>
                                                                <input type="text" class="form-control fs-4" id="contactNumber" v-model="dashboardData.userProfile.Contact_number" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card border-primary shadow-sm mb-4">
                                            <div class="card-body d-flex align-items-center">
                                                <div class="col-8">
                                                    <h5 class="card-title">Total Students</h5>
                                                    <p class="card-text fs-1">
                                                        {{ dashboardData.total_students }}
                                                    </p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="card-text fs-1">
                                                        <i class="fas fa-users fa-2x text-primary me-3"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-success shadow-sm mb-4">
                                            <div class="card-body d-flex align-items-center">
                                                <div class="col-8">
                                                    <h5 class="card-title">Total Books Requested</h5>
                                                    <p class="card-text fs-1 me-5">
                                                        {{ dashboardData.total_books_requested }}
                                                    </p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="card-text fs-1">
                                                        <i class="fas fa-book fa-2x text-success ms-3"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-danger shadow-sm mb-4">
                                            <div class="card-body d-flex align-items-center">
                                                <div class="col-8">
                                                    <h5 class="card-title">Total Books Approved</h5>
                                                    <p class="card-text fs-1">
                                                        {{ dashboardData.total_books_approved }}
                                                    </p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="card-text fs-1">
                                                        <i class="fas fa-check-circle fa-2x text-danger ms-3"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-info shadow-sm mb-4">
                                            <div class="card-body d-flex align-items-center">
                                                <div class="col-8">
                                                    <h5 class="card-title">Overall Total Books</h5>
                                                    <p class="card-text fs-1">
                                                        {{ dashboardData.overall_total_books }}
                                                    </p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="card-text fs-1">
                                                        <i class="fas fa-book-open fa-2x text-info ms-3"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="card border-warning shadow-sm mb-4">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="col-8">
                                                <h5 class="card-title">Pending Requests</h5>
                                                <p class="card-text fs-1">
                                                    {{ dashboardData.total_pending_requests }}
                                                </p>
                                            </div>
                                            <div class="col-4">
                                                <p class="card-text fs-1">
                                                    <i class="fas fa-exclamation-triangle fa-2x text-warning ms-3"></i>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="../vue/dashboard.js"></script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imgs/icon.ico">
    <title>Users</title>

    <?php include('C:\xampp\htdocs\student_clinic_information\lib\link.php'); ?>
</head>

<body>


<div id="user">

    <?php require_once 'router.php'; ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto overflow-hidden p-3">
                <h3 class="mb-4 fw-bold">Manage Users</h3>
                <div class="card border-0 shadow-sm mb-4 bg-light">
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <button class="btn btn-sm btn-primary add-user float-end mb-4" data-bs-toggle="modal" data-bs-target="#addUserModal" @click="clearFields()">ADD<i class="bi bi-plus-square"></i></button>
                            </div>
                            <div class="table-responsive-md col-lg-12">
                                <table class="table table-light table-hover border border-0 border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">User Role</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Contact No.</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <tr v-for="user in visibileUsers" v-bind:key="user.User_id">
                                            <td>{{ user.User_id }}</td>
                                            <td>{{ user.role }}</td>
                                            <td>{{ `${user.First_name} ${user.Last_name}` }}</td>
                                            <td>{{ user.Email }}</td>
                                            <td>+63{{ user.Contact_number }}</td>
                                            <td>
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal" @click="updateUserDetails(user)">Edit</button> |
                                                <button v-if="user.Active_ind == 1" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#userStatusModal" @click="updateUserDetails(user)">Inactive</button>
                                                <button v-else class="btn btn-success" data-bs-toggle="modal" data-bs-target="#userStatusModal" @click="updateUserDetails(user)">Active</button>
                                            </td>
                                        </tr>
                                        <tr v-if="noData">
                                            <td colspan="7" style="text-align: center; border-collapse: collapse;">No Data Found
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    <nav>
                        <ul class="pagination justify-content-end mt-3">
                            <li class="page-item">
                                <a class="page-link" href="#" @click="onPageChange(currentPage = 1)" :class="{ disabled: totalPages === 1 || currentPage === 1 }"><<</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#" @click="onPageChange(currentPage - 1)" :class="{ disabled: totalPages === 1 || currentPage === 1 }"><</a>
                            </li>
                            <li class="page-item" v-for="pageNumber in visiblePageNumbers" :key="pageNumber" :class="{ active: currentPage == pageNumber }">
                                <a class="page-link" href="#" @click="onPageChange(pageNumber)">{{ pageNumber }}</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#" @click="onPageChange(currentPage + 1)" :class="{ disabled: totalPages === 1 || currentPage === totalPages }">></a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#" @click="onPageChange(currentPage = totalPages)" :class="{ disabled: totalPages === 1 || currentPage === totalPages }">>></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>


    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button id="closeAdd" type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" placeholder="Username" name="Username" v-model="userModel.Username" autocomplete="username">
                                    <label for="floatingInput">Username</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" placeholder="Password" name="Password" v-model="userModel.Password" autocomplete="current-password">
                                    <label for="floatingInput">Password</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select" id="floatingSelect" name="role" v-model="userModel.role">
                                <option selected value="">Choose role</option>
                                <option value="Librarian">Librarian</option>
                                <option value="Clerk">Clerk</option>
                            </select>
                            <label for="floatingSelect">Role</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" placeholder="First Name" name="First_name" v-model="userModel.First_name">
                            <label for="floatingInput">First Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" placeholder="Last Name" name="Last_name" v-model="userModel.Last_name">
                            <label for="floatingInput">Last Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" placeholder="Email" name="Email" v-model="userModel.Email">
                            <label for="floatingInput">Email</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" placeholder="Contact Number" name="Contact_number" v-model="userModel.Contact_number">
                            <label for="floatingInput">Contact No. (+63)</label>
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="submit" @click="saveUser">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button id="closeUpdt" type="button" class="btn-close" data-bs-dismiss="modal" @click="clearFields(); getUser()"></button>
                </div>

                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="floatingSelect" name="role" v-model="userModel.role">
                            <option selected value="">Choose role</option>
                            <option value="Librarian">Librarian</option>
                            <option value="Clerk">Clerk</option>
                        </select>
                        <label for="floatingSelect">Role</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="First Name" name="First_name" v-model="userModel.First_name">
                        <label for="floatingInput">First Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Last Name" name="Last_name" v-model="userModel.Last_name">
                        <label for="floatingInput">Last Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="Email" v-model="userModel.Email">
                        <label for="floatingInput">Email</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" placeholder="Contact Number" name="Contact_number" v-model="userModel.Contact_number">
                        <label for="floatingInput">Contact No. (+63)</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="submit" @click="updateUser()">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearFields(); getUser()">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Active/Inactive Modal -->
    <div class="modal fade" id="userStatusModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 v-if="userModel.Active_ind == 1" class="modal-title fs-5" id="exampleModalLabel">Inactive User</h1>
                    <h1 v-else class="modal-title fs-5" id="exampleModalLabel">Active User</h1>
                    <button id="closeStatus" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 v-if="userModel.Active_ind == 1" class="text-center">Are you sure you want to set <b>{{ `${userModel.First_name} ${userModel.Last_name}` }}</b> as inactive?</h6>
                    <h6 v-else class="text-center">Are you sure you want to set <b>{{ `${userModel.First_name} ${userModel.Last_name}` }}</b> as active?</h6>
                </div>
                <div class="modal-footer">
                    <button v-if="userModel.Active_ind == 1" type="button" class="btn btn-success" @click="updateUserStatus(userModel.User_id, userModel.Active_ind)">Confirm</button>
                    <button v-else type="button" class="btn btn-success" @click="updateUserStatus(userModel.User_id, userModel.Active_ind)">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../vue/user.js"></script>
</body>

</html>

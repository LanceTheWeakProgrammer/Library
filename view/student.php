<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imgs/icon.ico">
    <title>Student Information</title>

    <?php include('C:\xampp\htdocs\student_clinic_information\lib\link.php'); ?>
</head>

<body>
    <div id="student">
        <?php require_once 'router.php'; ?>

            <div class="container-fluid" id="main-content">
                <div class="row">
                    <div class="col-lg-10 ms-auto overflow-hidden p-3">
                    <h3 class="mb-4 fw-bold">Student Information</h3>

                    <div class="col-lg-6">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search by name" v-model="searchQuery" @input="searchStudent">
                            <button class="btn btn-outline-secondary" type="button" @click="searchStudent">Search</button>
                        </div>
                    </div>
                <div class="card border-0 shadow-sm mb-4 bg-light">
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col-lg-12 mb-4">
                                <button class="btn btn-sm btn-primary add-student float-end" data-bs-toggle="modal" data-bs-target="#addStudentModal" @click="clearFields()">ADD<i class="bi bi-plus-square ms-1"></i></button>
                            </div>
                            <div class="table-responsive-md col-lg-12">
                                <table class="table table-light table-hover border border-0 border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Birthday</th>
                                            <th scope="col">Gender</th>
                                            <th scope="col">Contact no.</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="student in visibileStudents" v-bind:key="student.Student_id">
                                            <td>{{ student.Student_id }}</td>
                                            <td>
                                                <a class="text-black" href="#" data-bs-toggle="modal" data-bs-target="#viewInformationModal" @click="getStudentDetails(student)">
                                                    {{ `${student.First_name} ${student.Last_name}` }}
                                                </a>
                                            </td>
                                            <td>{{ student.Birthday }}</td>
                                            <td>{{ student.Gender }}</td>
                                            <td>+63{{ student.Contact_number }}</td>
                                            <td>{{ student.Email }}</td>
                                            <td>
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editStudentModal" @click="getStudentDetails(student)">Edit</button>
                                                <?php if ($role == "Librarian") { ?> |
                                                <button v-if="student.Active_ind == 1" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#studentStatusModal" @click="getStudentDetails(student)">Inactive</button>
                                                <button v-else class="btn btn-success" data-bs-toggle="modal" data-bs-target="#studentStatusModal" @click="getStudentDetails(student)">Active</button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr v-if="noData">
                                            <td colspan="8" style="text-align: center; border-collapse: collapse;">No Data Found
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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


        <!-- Add Student Information Modal -->
        <div class="modal fade" id="addStudentModal">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Add New Student Information</h5>
                        <button id="closeAdd" type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <h6 class="mt-3">Personal Information:</h6>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="First Name" name="First_name" v-model="studentModel.First_name">
                                    <label for="floatingInput">First Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Last Name" name="Last_name" v-model="studentModel.Last_name">
                                    <label for="floatingInput">Last Name</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="floatingInput" placeholder="Birthday" name="Birthday" v-model="studentModel.Birthday">
                                    <label for="floatingInput">Birthday</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="floatingSelect" name="Gender" v-model="studentModel.Gender">
                                        <option selected value="">Choose gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <label for="floatingSelect">Gender</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="floatingInput" placeholder="Contact No." name="Contact_number" v-model="studentModel.Contact_number">
                                    <label for="floatingInput">Contact No. (+63)</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="floatingInput" placeholder="Email" name="Email" v-model="studentModel.Email">
                                    <label for="floatingInput">Email</label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Year" name="Year" v-model="studentModel.Year">
                                    <label for="floatingInput">Year</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Section" name="Section" v-model="studentModel.Section">
                                    <label for="floatingInput">Section</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Course" name="Course" v-model="studentModel.Course">
                                    <label for="floatingInput">Course</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Address(N/A if none)" id="floatingTextarea" style="height: 100px" name="Address" v-model="studentModel.Address"></textarea>
                                    <label for="floatingTextarea">Address(N/A if none)</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="submit" @click="saveStudent()">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Student Information Modal -->
        <div class="modal fade" id="editStudentModal">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Student Information</h5>
                        <button id="closeUpdt" type="button" class="btn-close" data-bs-dismiss="modal" @click="clearFields(); getStudent()"></button>
                    </div>

                    <div class="modal-body">
                        <h6 class="mt-3">Personal Information:</h6>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="First Name" name="First_name" v-model="studentModel.First_name">
                                    <label for="floatingInput">First Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Last Name" name="Last_name" v-model="studentModel.Last_name">
                                    <label for="floatingInput">Last Name</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="floatingInput" placeholder="Birthday" name="Birthday" v-model="studentModel.Birthday">
                                    <label for="floatingInput">Birthday</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="floatingSelect" name="Gender" v-model="studentModel.Gender">
                                        <option selected value="">Choose gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <label for="floatingSelect">Gender</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="floatingInput" placeholder="Contact No." name="Contact_number" v-model="studentModel.Contact_number">
                                    <label for="floatingInput">Contact No. (+63)</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="floatingInput" placeholder="Email" name="Email" v-model="studentModel.Email">
                                    <label for="floatingInput">Email</label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Year" name="Year" v-model="studentModel.Year">
                                    <label for="floatingInput">Year</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Section" name="Section" v-model="studentModel.Section">
                                    <label for="floatingInput">Section</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Course" name="Course" v-model="studentModel.Course">
                                    <label for="floatingInput">Course</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Address(N/A if none)" id="floatingTextarea" style="height: 100px" name="Address" v-model="studentModel.Address"></textarea>
                                    <label for="floatingTextarea">Address(N/A if none)</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="submit" @click="updateStudent()">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearFields(); getStudent()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Student Information Modal -->
        <div class="modal fade" id="viewInformationModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">{{ `${studentModel.First_name} ${studentModel.Last_name}` }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <h3 class="text-center">Information</h3>
                        <hr>

                        <div class="row">
                            <div class="col-md-8 text-end">
                                <h5>Course,Year, & Section:</h5>
                            </div>
                            <div class="col-md-4">
                                <h5>{{ studentModel.Course }} {{ studentModel.Year }}-{{ studentModel.Section }}</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 text-end">
                                <h5>Address:</h5>
                            </div>
                            <div class="col-md-6">
                                <h5>{{ studentModel.Address }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Enable/Disable Modal -->
        <div class="modal fade" id="studentStatusModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 v-if="studentModel.Active_ind == 1" class="modal-title fs-5" id="exampleModalLabel">Confirm</h1>
                        <h1 v-else class="modal-title fs-5" id="exampleModalLabel">Confirm</h1>
                        <button id="closeStatus" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 v-if="studentModel.Active_ind == 1" class="text-center">Are you sure you want to set <b>{{ `${studentModel.First_name} ${studentModel.Last_name}` }}</b>  as inactive?</h6>
                        <h6 v-else class="text-center">Are you sure you want to set <b>{{ `${studentModel.First_name} ${studentModel.Last_name}` }}</b>  as active? </h6>
                    </div>
                    <div class="modal-footer">
                        <button v-if="studentModel.Active_ind == 1" type="button" class="btn btn-success" @click="updateStudentStatus(studentModel.Student_id, studentModel.Active_ind)">Confirm</button>
                        <button v-else type="button" class="btn btn-success" @click="updateStudentStatus(studentModel.Student_id, studentModel.Active_ind)">Confirm</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Script -->
    <script src="../vue/student.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imgs/icon.ico">
    <title>Book | Inventory</title>

    <?php include('C:\xampp\htdocs\student_clinic_information\lib\link.php'); ?>
</head>



<body>
    <div id="book">
        <?php require_once 'router.php'; ?>
            <div class="container-fluid" id="main-content">
                <div class="row">
                    <div class="col-lg-10 ms-auto overflow-hidden p-3">
                    <h3 class="mb-4 fw-bold">Inventory</h3>
                    <div class="card border-0 shadow-sm mb-4 bg-light">
                        <div class="card-body">
                    <div class="row mt-4">
                        <div class="col-lg-12 mb-4">
                            <button class="btn btn-sm btn-primary add-student float-end" data-bs-toggle="modal" data-bs-target="#adddBooksModal" @click="clearFields()">ADD<i class="bi bi-plus-square ms-1"></i></button>
                        </div>
                        <div class="row mt-4">
                        <div class="table-responsive-md col-lg-12">
                                <table class="table table-light table-hover border border-0 border-dark">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Title</th>
                                      <th scope="col">Qty. Stock</th>
                                    <th scope="col">Qty. Issued</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Published Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="book in visibleBooks" v-bind:key="book.Book_id">
                                    <td>{{ book.Book_id }}</td>
                                    <td>{{ book.Title }}</td>
                                    <td v-if="book.Qty_stock == 0" class="text-danger"><b>{{ book.Qty_stock }}</b></td>
                                    <td v-else>{{ book.Qty_stock }}</td>
                                    <td>{{ book.Qty_issued }}</td>
                                    <td>{{ book.Total }}</td>
                                    <td>{{ book.Pub_date }}</td>
                                    <td>
                                    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editdBooksModal" @click="getBookDetails(book)">Edit</button>

                                        <button v-if="book.Qty_stock != 0" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reqdBooksModal" @click="getRequestDetails(book, '<?= $requestor_name ?>', '<?= $role ?>')">Request</button>
                                        <button v-else class="btn btn-success disabled">Request</button>
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


                    

        <!-- Add Book Modal -->
        <div class="modal fade" id="adddBooksModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Add New Book</h5>
                        <button id="closeAdd" type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" placeholder="First Title" name="Title" v-model="bookModel.Title">
                            <label for="floatingInput">Book Title</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" placeholder="First Title" name="Qty_stock" v-model="bookModel.Qty_stock">
                            <label for="floatingInput">Stock</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" placeholder="First Title" name="Pub_date" v-model="bookModel.Pub_date">
                            <label for="floatingInput">Published Date</label>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="submit" @click="saveBook()">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Book Modal -->
        <div class="modal fade" id="editdBooksModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Book</h5>
                        <button id="closeUpdt" type="button" class="btn-close" data-bs-dismiss="modal" @click="clearFields(); getBook()"></button>
                    </div>

                    <div class="modal-body">

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" placeholder="First Title" name="Title" v-model="bookModel.Title">
                            <label for="floatingInput">Book Title</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" placeholder="First Title" name="Qty_stock" v-model="bookModel.Qty_stock">
                            <label for="floatingInput">Stock</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" placeholder="First Title" name="Pub_date" v-model="bookModel.Pub_date">
                            <label for="floatingInput">Published Date</label>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="submit" @click="updateBook()">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearFields(); getBook()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Book Modal -->
        <div class="modal fade" id="reqdBooksModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Request Book</h5>
                        <button id="closeReq" type="button" class="btn-close" data-bs-dismiss="modal" @click="clearFields(); getBook()"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" placeholder="Book Title" name="Book_name" v-model="requestModel.Title" disabled>
                            <label for="floatingInput">Book Title</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select" id="floatingSelect" name="student_id" v-model="requestModel.Student_id">
                                <!-- <option selected value="">Choose student</option> -->
                                <option v-for="stud in students" :value="stud.Student_id" :v-bind:key="stud.Student_id">{{ `${stud.First_name} ${stud.Last_name}` }}</option>
                            </select>
                            <label for="floatingSelect">Requested For</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" placeholder="Quantity" name="Qty_issued" v-model="requestModel.Qty_requested">
                            <label for="floatingInput">Quantity</label>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="submit" @click="requestBook()">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearFields(); getBook()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Script -->
    <script src="../vue/inventory.js"></script>
</body>

</html>
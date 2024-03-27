<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imgs/icon.ico">
    <title>Book | Request</title>

    <?php include('C:\xampp\htdocs\student_clinic_information\lib\link.php'); ?>
</head>

<body>
    <div id="request">
        <?php require_once 'router.php'; ?>

            <div class="container-fluid" id="main-content">
                <div class="row">
                    <div class="col-lg-10 ms-auto overflow-hidden p-3">
                    <h3 class="mb-4 fw-bold">Book Requests</h3>
                    <div class="col-lg-6 container mt-3">
                        <form class="d-flex">
                            <input class="form-control me-2 mb-4" type="search" placeholder="Search for requestor" aria-label="Search" v-model="searchQuery">
                            <button class="btn btn-outline-primary mb-4" type="button" @click="searchBookRequest">Search</button>
                        </form>
                    </div>

            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">
                        <div class="row mt-4">
                        <div class="table-responsive-md col-lg-12">
                                <table class="table table-light table-hover border border-0 border-dark">
                                    <thead>
                                        <tr>
                                            <th scope="col">Request ID</th>
                                            <th scope="col">Book Title</th>
                                            <th scope="col">Qty.</th>
                                            <th scope="col">Requested By</th>
                                            <th scope="col">Requested For</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Requested Date</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="req in visibileReqs" v-bind:key="req.Request_id">
                                            <td>{{ req.Request_id }}</td>
                                            <td>
                                                {{ isSearching ? req.BookTitle : req.Title }}
                                            </td>    
                                            <td>{{ req.Qty_requested }}</td>
                                            <td>{{ req.RequestedBy }}</td>
                                            <td>{{ req.RequestedFor }}</td>
                                            <td>{{ req.Requeststatus }}</td>
                                            <td>{{ req.Requestdttm }}</td>
                                            <td>
                                                <button v-if="req.Requeststatus == 'Pending For Approval'" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#requestApprovalModal" @click="getRequestDetails(req, 'approve', '<?= $requestor_name ?>', '<?= $role ?>')"><i class="bi bi-hand-thumbs-up"></i></button>
                                                <button v-else class="btn btn-secondary me-2 disabled"><i class="bi bi-hand-thumbs-up"></i></button>

                                                <button v-if="req.Requeststatus == 'Pending For Approval'" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#requestApprovalModal" @click="getRequestDetails(req, 'decline', '<?= $requestor_name ?>', '<?= $role ?>')"><i class="bi bi-hand-thumbs-down"></i></button>
                                                <button v-else class="btn btn-secondary me-2 disabled"><i class="bi bi-hand-thumbs-down"></i></button>
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

        <!-- Request Approve/Decline Modal -->
        <div class="modal fade" id="requestApprovalModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 v-if="forApproval" class="modal-title fs-5" id="requestApprovalModal">Approve Request</h1>
                        <h1 v-else class="modal-title fs-5" id="requestApprovalModal">Decline Request</h1>
                        <button id="closeApprv" type="button" class="btn-close" data-bs-dismiss="modal" @click="clearFields(); getRequest()"></button>
                    </div>
                    <div class="modal-body">
                        <h6 v-if="forApproval" class="text-center">Are you sure you want to approve this request?</h6>
                        <h6 v-else class="text-center">Are you sure you want to decline this request?</h6>
                    </div>
                    <div class="modal-footer">
                        <button v-if="forApproval" type="button" class="btn btn-success" @click="updateRequest('approve')">Approve</button>
                        <button v-else type="button" class="btn btn-danger" @click="updateRequest('decline')">Decline</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="clearFields(); getRequest()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Script -->
    <script src="../vue/request.js"></script>
</body>

</html>
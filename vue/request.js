const { createApp } = Vue

createApp({
    data() {
        return {
            request: [],
            requestModel: {},
            forApproval: false,
            noData: false,
            successMsg: "",
            isSuccess: false,
            currentPage: 1,
            itemsPerPage: 10,
            searchQuery: "",
        };
    },
    computed: {
        visibileReqs() {
            const startPage = (this.currentPage - 1) * this.itemsPerPage;
            const endPage = startPage + this.itemsPerPage;
            return this.request.slice(startPage, endPage);
        },
        totalPages() {
            return Math.ceil(this.request.length / this.itemsPerPage);
        },
        visiblePageNumbers() {
            let pageNumbers = [];
            if (this.totalPages <= 7) {
                for (let i = 1; i <= this.totalPages; i++) {
                    pageNumbers.push(i);
                }
            } else {
                if (this.currentPage <= 4) {
                    pageNumbers = [1, 2, 3, 4, 5, "...", this.totalPages];
                } else if (this.currentPage >= this.totalPages - 3) {
                    pageNumbers = [1, "...", this.totalPages - 4, this.totalPages - 3, this.totalPages - 2, this.totalPages - 1, this.totalPages];
                } else {
                    pageNumbers = [1, "...", this.currentPage - 1, this.currentPage, this.currentPage + 1, "...", this.totalPages];
                }
            }
            return pageNumbers;
        }
    },
    methods: {
        searchBookRequest() {
            const searchParams = new URLSearchParams();
        
            if (this.searchQuery) {
                searchParams.append('RequestedBy', this.searchQuery);
            }
        
            fetch(`../api/bookHandler.php?action=searchBookRequest&${searchParams.toString()}`, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    this.request = data.requests;
                } else {
                    this.request = [];
                }
        
                this.noData = data.error;
                this.currentPage = 1;
                this.isSearching = !!this.searchQuery.trim();
            })
            .catch(error => {
                console.error('Error in searchBookRequest:', error);
            });
        },        
        getRequest() {
            fetch('../api/bookHandler.php?action=getAllRequest', {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                let newData = data;
                if (newData.error) {
                    this.noData = newData.error;
                } else {
                    this.noData = newData.error;
                    this.request = newData.request.sort((a, b) => {
                        if (a.Requeststatus === 'Pending For Approval') return -1;
                        if (b.Requeststatus === 'Pending For Approval') return 1;
                        return 0;
                    });
                    this.isSearching = false;
                }
            })
            .catch(error => {
                console.error('Error in getRequest:', error);
            });
        },
        getRequestDetails(request, action, requestor, role) {
            this.requestModel = {
                Request_id: request.Request_id,
                Student_id: request.Student_id,
                Book_id: request.Book_id,
                Title: this.isSearching ? request.BookTitle : request.Title,
                Qty_stock: request.Qty_stock,
                Qty_issued: request.Qty_issued,
                Qty_requested: request.Qty_requested,
                Total: request.Total,
                Pub_date: request.Pub_date,
                RequestedBy: requestor,
                Role: role,
                RequestedFor: `${request.First_name} ${request.Last_name}`,
                Requeststatus: 'Pending For Approval' 
            };
        
            if (action === 'approve') {
                this.forApproval = true;
            } else if (action === 'decline') {
                this.forApproval = false;
            }
        },
        updateRequest(action) {
            if (this.forApproval || !this.forApproval) { 
                let request = this.requestModel;
        
                if (action === 'approve') {
                    request.Requeststatus = 'Approved';
                    this.showAlert('success', 'Request approved successfully.');
                } else if (action === 'decline') {
                    request.Requeststatus = 'Declined';
                    this.showAlert('danger', 'Request declined successfully.');
                }
        
                let requestApprovalForm = this.convertToFormData(request);
                for (var pair of requestApprovalForm.entries()) {
                    console.log(pair[0] + ' = ' + pair[1]);
                }
        
                fetch('../api/bookHandler.php?action=updateRequestStatus', {
                    method: 'POST',
                    body: requestApprovalForm
                }).then(response => {
                    return response.text();
                }).then(data => {
                    let newData = JSON.parse(data);
                    console.log('newData: ', newData);
                    if (!newData.error) {
                        this.getRequest();
        
                        document.getElementById('closeApprv').click();
                        this.isSuccess = !newData.error;
                        this.successMsg = newData.message;
                    }
                });
            }
        },
        showAlert(type, msg) {
            let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
            let element = document.createElement('div');
            element.innerHTML = `
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999; top: 20px;">
                <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
                    <strong class="me-3">${msg}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>`;
            
            document.body.append(element);
            setTimeout(() => {
                element.remove();
            }, 2000);
        },
        onPageChange(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
        convertToFormData(data) {
            let formData = new FormData();

            for (let value in data) {
                formData.append(value, data[value]);
            }

            return formData;
        },
        clearFields() {
            this.bookModel = {};
        }
    },
    mounted() {
        this.getRequest();
    }
}).mount('#request');

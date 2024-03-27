const { createApp } = Vue

createApp({
    data() {
        return {
            book: [],
            students: [],
            bookModel: {
                Title: "",
                Qty_stock: "",
                Qty_issued: "",
                Total: "",
                Pub_date: ""
            },
            requestModel: {
                Student_id: "",
                Qty_requested: 0
            },
            noData: false,
            successMsg: "",
            isSuccess: false,
            currentPage: 1,
            itemsPerPage: 10,

        }
    },
    computed: {
        visibleBooks() {
            const startPage = (this.currentPage - 1) * this.itemsPerPage;
            const endPage = startPage + this.itemsPerPage;
            return this.book.slice(startPage, endPage);
        },
        totalPages() {
            return Math.ceil(this.book.length / this.itemsPerPage)
        },
        visiblePageNumbers() {
            let pageNumbers = [];
            if (this.totalPages <= 7) {
                for (let i = 1; i <= this.totalPages; i++) {
                    pageNumbers.push(i)
                }
            } else {
                if (this.currentPage <= 4) {
                    pageNumbers = [1, 2, 3, 4, 5, "...", this.totalPages];
                } else if (this.currentPage >= this.totalPages - 3) {
                    pageNumbers = [1, "...", this.totalPages - 4, this.totalPages - 3, this.totalPages - 2, this.totalPages - 1, this.totalPages]
                } else {
                    pageNumbers = [1, "...", this.currentPage - 1, this.currentPage, this.currentPage + 1, "...", this.totalPages];
                }
            }
            return pageNumbers;
        }

    },
    methods: {
        getBook() {
            fetch('../api/bookHandler.php?action=getAllBooks', { method: 'GET' })
                .then(response => response.text())
                .then(data => {
                    let newData = JSON.parse(data);
                    if (newData.error) {
                        this.noData = newData.error;
                    } else {
                        this.noData = newData.error;
                        this.book = JSON.parse(data).books;
                    }
                })
        },
        getBookDetails(book_details) {
            this.bookModel = book_details;
        },
        getStudent() {
            fetch('../api/studentHandler.php?action=getAllStudents',
                {
                    method: 'GET'
                }).then(response => {
                    return response.text();
                }).then(data => {
                    let newData = JSON.parse(data);

                    if (newData.students.length == 0) {
                        this.noData = true;
                    } else {
                        this.noData = newData.error;
                        this.students = JSON.parse(data).students;
                    }
                });
        },
        getRequestDetails(book, requestor, role) {
            this.requestModel = {
                Book_id: book.Book_id,
                Title: book.Title,
                Qty_stock: book.Qty_stock,
                Qty_issued: book.Qty_issued,
                Total: book.Total,
                Pub_date: book.Pub_date,
                RequestedBy: requestor,
                Role: role
            };
        },
        requestBook() {
            console.log('requestBook', this.requestModel);
            let stud = this.students.filter((item) => item.Student_id == this.requestModel.Student_id);

            let request = this.requestModel;

            if (request.Student_id != "" && request.Book_id != "") {
                let reqBookForm = this.convertToFormData(request);
                reqBookForm.append('RequestedFor',  `${stud[0].First_name} ${stud[0].Last_name}`);
                for (var pair of reqBookForm.entries()) {
                    console.log(pair[0] + ' = ' + pair[1]);
                }
                fetch('../api/bookHandler.php?action=saveRequest',
                    {
                        method: 'POST',
                        body: reqBookForm
                    }
                ).then(response => {
                    return response.text();
                }).then(data => {
                    let newData = JSON.parse(data);
                    if (!newData.error) {
                        document.getElementById('closeReq').click();
                        this.getBook();
                        this.isSuccess = !newData.error;
                        this.successMsg = newData.message;

                        this.showAlert('success', newData.message);
                    } else {
                        this.showAlert('danger', 'Failed to request book.');
                    }
                });
            }
        },
        saveBook() {
            let book = this.bookModel;

            if (book.Title != ""
                && book.Qty_stock != ""
                && book.Pub_date != ""
            ) {
                let addBookForm = this.convertToFormData(book);
                fetch('../api/bookHandler.php?action=saveBook',
                    {
                        method: 'POST',
                        body: addBookForm
                    }
                ).then(response => {
                    return response.text();
                }).then(data => {
                    let newData = JSON.parse(data);
                    if (!newData.error) {
                        document.getElementById('closeAdd').click();
                        this.getBook();
                        this.isSuccess = !newData.error;
                        this.successMsg = newData.message;

                        this.showAlert('success', newData.message);
                    } else {
                        this.showAlert('danger', 'Failed to save book.');
                    }
                });
            }
        },
        updateBook() {
            let book = this.bookModel;
        
            if (book.Qty_stock < 0) {
                this.showAlert('danger', 'Invalid quantity entered.');
                return;
            }
        
            if (book.Title != "" && book.Qty_stock != "" && book.Pub_date != "") {
                let updateBookForm = this.convertToFormData(book);
                for (var pair of updateBookForm.entries()) {
                    console.log(pair[0] + ' = ' + pair[1]);
                }
                fetch('../api/bookHandler.php?action=updateBook', {
                    method: 'POST',
                    body: updateBookForm
                }).then(response => {
                    return response.text();
                }).then(data => {
                    let newData = JSON.parse(data);
                    if (!newData.error) {
                        document.getElementById('closeUpdt').click();
                        this.getBook();
                        this.isSuccess = !newData.error;
                        this.successMsg = newData.message;
        
                        this.showAlert('success', newData.message);
                    } else {
                        this.showAlert('danger', newData.message);
                    }
                });
            }
        },        
        updateUserStatus(user_details) {
            let user = this.userModel = user_details;
            console.log('user: ', user);
            if (user.id != "") {
                let data = this.convertToFormData(user);

                fetch('../api/userHandler.php?action=updateUserStatus', { method: 'POST', body: data })
                    .then(response => response.text())
                    .then(data => {
                        let newData = JSON.parse(data);
                        if (!newData.error) {
                            this.getUser();
                            this.isSuccess = !newData.error;
                            this.successMsg = newData.message;

                            this.showAlert('success', newData.message);
                        } else {
                            this.showAlert('danger', 'Failed to update user status.');
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
            this.bookModel = {
                Title: "",
                Qty_stock: "",
                Qty_issued: "",
                Total: "",
                Pub_date: ""
            };
        }
    },
    mounted() {
        this.getBook();
        this.getStudent();
    }
}).mount('#book')
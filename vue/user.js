const { createApp } = Vue

createApp({
    data() {
        return {
            users: [],
            userModel: {
                Username: "",
                Password: "",
                role: "",
                First_name: "",
                Last_name: "",
                Email: "",
                Contact_number: ""
            },
            noData: false,
            successMsg: "",
            isSuccess: false,
            currentPage: 1,
            itemsPerPage: 10,
        }
    },
    computed: {
        visibileUsers() {
            const startPage = (this.currentPage - 1) * this.itemsPerPage;
            const endPage = startPage + this.itemsPerPage;
            return this.users.slice(startPage, endPage);
        },
        totalPages() {
            return Math.ceil(this.users.length / this.itemsPerPage)
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
        getUser() {
            fetch('../api/userHandler.php?action=getAllUsers',
                {
                    method: 'GET'
                }
            ).then(response => {
                return response.text();
            }).then(data => {
                let newData = JSON.parse(data);

                if (newData.error) {
                    this.noData = newData.error;
                } else {
                    this.noData = newData.error;
                    this.users = JSON.parse(data).users;
                }
            })
        },
        updateUserDetails(user_details) {
            this.userModel = user_details;
        },
        saveUser() {
            let user = this.userModel;

            if (user.Username != ""
                && user.Password != ""
                && user.role != ""
                && user.First_name != ""
                && user.Last_name != ""
                && user.Email != ""
                && user.Contact_number != ""
            ) {
                let addUserForm = this.convertToFormData(user);

                fetch('../api/userHandler.php?action=saveUser', {
                    method: 'POST',
                    body: addUserForm
                }).then(response => {
                    return response.text();
                }).then(data => {
                    let newData = JSON.parse(data);
                    if (!newData.error) {
                        document.getElementById('closeAdd').click();
                        this.getUser();
                        this.isSuccess = !newData.error;
                        this.successMsg = newData.message;

                        this.showAlert('success', newData.message);
                    } else {
                        this.showAlert('danger', 'Failed to save user.');
                    }
                });
            }
        },
        updateUser() {
            let user = this.userModel;

            if (user.role != ""
                && user.First_name != ""
                && user.Last_name != ""
                && user.Email != ""
                && user.Contact_number != ""
            ) {
                let updateUserForm = this.convertToFormData(user);
                fetch('../api/userHandler.php?action=updateUser', {
                    method: 'POST',
                    body: updateUserForm
                }).then(response => {
                    return response.text();
                }).then(data => {
                    let newData = JSON.parse(data);
                    if (!newData.error) {
                        document.getElementById('closeUpdt').click();
                        this.getUser();
                        this.isSuccess = !newData.error;
                        this.successMsg = newData.message;
                        this.showAlert('success', newData.message);
                    } else {
                        this.showAlert('danger', 'Failed to update user.');
                    }
                });
            }
        },
        updateUserStatus(user_id, active_ind) {
            this.clearFields();

            let data = new FormData();
            data.append('User_id', user_id);
            data.append('Active_ind', active_ind);
            if (user_id != "") {
                fetch('../api/userHandler.php?action=updateUserStatus', {
                    method: 'POST',
                    body: data
                }).then(response => {
                    return response.text();
                }).then(data => {
                    let newData = JSON.parse(data);
                    console.log(newData);
                    if (!newData.error) {
                        document.getElementById('closeStatus').click();
                        this.getUser();
                        this.isSuccess = newData.error;
                        this.successMsg = newData.message;
                        this.showAlert('success', newData.message);
                    } else {
                        this.showAlert('danger', 'Failed to update user.');
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
            this.userModel = {
                Username: "",
                Password: "",
                role: "",
                First_name: "",
                Last_name: "",
                Email: "",
                Contact_number: ""
            };
        }
    },
    mounted() {
        this.getUser();
    }
}).mount('#user')
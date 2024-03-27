const { createApp } = Vue

createApp({
    data() {
        return {
            students: [],
            studentModel: {
                First_name: "",
                Last_name: "",
                Birthday: "",
                Gender: "",
                Contact_number: "",
                Email: "",
                Year: "",
                Section: "",
                Course: "",
                Address: ""
            },
            currentPage: 1,
            itemsPerPage: 10,
            successMsg: "",
            isSuccess: false,
            noData: false,
            searchQuery: "",
        }
    },
    computed: {
        visibileStudents() {
            const startPage = (this.currentPage - 1) * this.itemsPerPage;
            const endPage = startPage + this.itemsPerPage;
            return this.students.slice(startPage, endPage);
        },
        totalPages() {
            return Math.ceil(this.students.length / this.itemsPerPage)
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
        searchStudent() {
            const searchParams = new URLSearchParams();
        
            if (this.searchQuery) {
                searchParams.append('First_name', this.searchQuery);
            }
        
            fetch(`../api/studentHandler.php?action=searchStudent&${searchParams.toString()}`, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    this.students = data.students;
                } else {
                    this.students = [];
                }
            })
            .catch(error => {
                console.error('Error in searchStudent:', error);
            });
        },
        getStudentDetails(student_details) {
            this.studentModel = student_details;
            console.log('studentModel: ', this.studentModel);
        },
        saveStudent() {
            let stud = this.studentModel;

            if (stud.First_name != ""
                && stud.Last_name != ""
                && stud.Birthday != ""
                && stud.Gender != ""
                && stud.Contact_number != ""
                && stud.Email != ""
                && stud.Year != ""
                && stud.Section != ""
                && stud.Course != ""
            ) {

                let addStudForm = this.convertToFormData(stud);

                fetch('../api/studentHandler.php?action=insertStudent', {
                    method: 'POST',
                    body: addStudForm
                }).then(response => {
                    return response.text()
                }).then(data => {
                    let newData = JSON.parse(data);
                    if (!newData.error) {
                        document.getElementById('closeAdd').click();
                        this.getStudent();
                        this.isSuccess = !newData.error;
                        this.successMsg = newData.message;

                        this.showAlert('success', newData.message);
                    } else {
                        this.showAlert('danger', 'Failed to save student.');
                    }
                });
            }
        },

        updateStudent() {
            let stud = this.studentModel;

            if (stud.First_name != ""
                && stud.Last_name != ""
                && stud.Birthday != ""
                && stud.Gender != ""
                && stud.Contact_number != ""
                && stud.Email != ""
                && stud.Year != ""
                && stud.Section != ""
                && stud.Course != ""
            ) {

                let updateStudForm = this.convertToFormData(stud);
                fetch('../api/studentHandler.php?action=updateStudent', {
                    method: 'POST',
                    body: updateStudForm
                }).then(response => {
                    return response.text();
                }).then(data => {
                    let newData = JSON.parse(data);
                    if (!newData.error) {
                        document.getElementById('closeUpdt').click();
                        this.getStudent();
                        this.isSuccess = !newData.error;
                        this.successMsg = newData.message;

                        this.showAlert('success', newData.message);
                    } else {
                        this.showAlert('danger', 'Failed to update student.');
                    }
                });
            }
        },

        updateStudentStatus(student_id, active_ind) {
            this.clearFields();

            let data = new FormData();
            data.append('Student_id', student_id);
            data.append('Active_ind', active_ind);
            
            if (student_id != "") {
                fetch('../api/studentHandler.php?action=updateStudentStatus', {
                    method: 'POST',
                    body: data
                }).then(response => {
                    return response.text();
                }).then(data => {
                    console.log('data: ', JSON.parse(data));

                    let newData = JSON.parse(data);
                    if (!newData.error) {
                        document.getElementById('closeStatus').click();
                        this.getStudent();
                        this.isSuccess = !newData.error;
                        this.successMsg = newData.message;

                        this.showAlert('success', newData.message);
                    } else {
                        this.showAlert('danger', 'Failed to update student status.');
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
            this.studentModel = {
                First_name: "",
                Last_name: "",
                Birthday: "",
                Gender: "",
                Contact_number: "",
                Email: "",
                Year: "",
                Section: "",
                Course: "",
                Address: ""
            }
        }
    },
    mounted() {
        this.getStudent();
    }
}).mount('#student')
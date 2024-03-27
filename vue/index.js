
const { createApp } = Vue;

createApp({
    data() {
        return {
            loginDetails: {
                username: '',
                password: ''
            },
            showErrMsg: false,
            errMsg: "",
            showBtnLoading: false
        };
    },
    methods: {
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
        keymonitor(event) {
            if (event.key == "Enter") {
                this.checkLogin();
            }
        },
        checkLogin() {
            let loginForm = this.convertToFormData(this.loginDetails);
            fetch('./api/loginHandler.php', {
                method: 'POST',
                body: loginForm
            }).then(response => {
                return response.text();
            }).then(data => {
                if (JSON.parse(data).error) {
                    this.showErrMsg = JSON.parse(data).error;
                    this.errMsg = JSON.parse(data).message;

                    this.showAlert('danger', 'Failed to log in. ' + this.errMsg);
                } else {
                    console.log('Logging in...');
                    this.showBtnLoading = true;
                    setTimeout(() => {
                        this.showBtnLoading = false;
                        window.location.href = "view/dashboard.php";
                    }, 2000);
                }
            });
        },

        convertToFormData(data) {
            let formData = new FormData();

            for (let value in data) {
                formData.append(value, data[value]);
            }

            return formData;
        },
    },
}).mount('#login');

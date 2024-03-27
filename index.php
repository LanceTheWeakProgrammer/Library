<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../imgs/icon.ico">
  <title>Login</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="./lib/bootstrap@5.3.2/css/bootstrap.min.css">
  <script src="./lib/bootstrap@5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Vue.js -->
  <script src="./lib/vue@3.3.9/vue.global.js"></script>

  <style>
    .form-signin {
      width: 100%;
      max-width: 360px;
      padding: 15px;
      margin: auto;
    }

    .form-signin-title {
      margin-top: 60px;
      text-align: center;
    }
  </style>

</head>

<body>
  <div id="login" class="form-signin">
    <div class="form-signin-title mb-3">
      <img class="mb-3 mt-5" src="./imgs/cpc_logo.png" width="150" height="150">
      <h2 class="mb-5">Library System</h2>
    </div>
    <div class="form-floating mb-3">
      <input type="text" class="form-control" id="floatingInput" placeholder="Username" v-model="loginDetails.username" v-on:keyup="keymonitor">
      <label for="floatingInput">Username</label>
    </div>

    <div class="form-floating mb-3">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Password" v-model="loginDetails.password" v-on:keyup="keymonitor">
      <label for="floatingPassword">Password</label>
    </div>

    <div class="d-grid gap-2 col-6 mx-auto float-end">
      <button v-if="showBtnLoading == false" type="button" class="btn btn-primary btn-lg" @click="checkLogin">Login</button>

    </div>


  </div>

  <!-- Login Script -->
  <script src="vue/index.js"></script>
</body>

</html>
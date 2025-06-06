<?php
@ob_start();
session_start();
require 'config.php'; // <-- TAMBAHKAN BARIS INI

if (isset($_POST['proses'])) {
    // Ambil data dari form
    $user = $_POST['user'];
    $pass = md5($_POST['pass']);

    // 1. Cek kredensial di tabel 'login'
    $sql = "SELECT * FROM login WHERE user = ? AND pass = ?";
    $row = $config->prepare($sql);
    $row->execute(array($user, $pass));
    $jum = $row->rowCount();

    if ($jum > 0) {
        // Jika login berhasil, ambil data login
        $login_data = $row->fetch();

        // 2. Ambil data profil dari tabel 'member' menggunakan id_member
        $sql_member = "SELECT * FROM member WHERE id_member = ?";
        $row_member = $config->prepare($sql_member);
        $row_member->execute(array($login_data['id_member']));
        $member_data = $row_member->fetch();

        // 3. Gabungkan data yang diperlukan ke dalam session
        $session_data = [
            'id_member' => $login_data['id_member'],
            'id_login' => $login_data['id_login'],
            'user' => $login_data['user'],
            'role' => $login_data['role'], // <-- Role dari tabel login
            'nm_member' => $member_data['nm_member'],
            'gambar' => $member_data['gambar']
        ];

        $_SESSION['admin'] = $session_data;
        echo '<script>alert("Login Sukses");window.location="index.php"</script>';
    } else {
        echo '<script>alert("Login Gagal");history.go(-1);</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login - POS Codekop</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="sb-admin/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-md-5 mt-5">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="p-5">
                            <div class="text-center">
                                <h4 class="h4 text-gray-900 mb-4"><b>Login Kasir Shin Vape</b></h4>
                            </div>
                            <form class="form-login" method="POST">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="user"
                                        placeholder="User ID" autofocus>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="pass"
                                        placeholder="Password">
                                </div>
                                <button class="btn btn-primary btn-block" name="proses" type="submit"><i
                                        class="fa fa-lock"></i>
                                    SIGN IN</button>
                            </form>
                            <!-- <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="register.html">Create an Account!</a>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="sb-admin/vendor/jquery/jquery.min.js"></script>
    <script src="sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="sb-admin/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="sb-admin/js/sb-admin-2.min.js"></script>
</body>

</html>
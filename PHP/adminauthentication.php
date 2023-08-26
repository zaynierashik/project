<?php
    session_start();
    include 'connect.php';

    if(isset($_POST['register-submit'])){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM admin_data WHERE phone = ? AND email = ?";
        $stmt = $conn->prepare($sql);
        $stmt ->execute([$phone, $email]);
        $result = $stmt->fetch();

        if($result){
            
        }else{
            $sql = "INSERT INTO admin_data (name, phone, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt->execute([$name, $phone, $email, $hashed_password])){
                $success = 1;
            }
        }
    }

    if(isset($_POST['login-submit'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM admin_data WHERE email = :email");
        $stmt ->bindParam(':email', $email);
        $stmt ->execute();
        $adminauth = $stmt->fetch();

        if($adminauth){
            if(password_verify($password, $adminauth['password'])){
                session_start();
                $_SESSION['adminname'] = $adminauth['email'];
                header("location: adminpage.php");
                exit();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamro College</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/Favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/Favicon/favicon-16x16.png">
    <link rel="manifest" href="/Favicon/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/admin.css">
</head>
<body>

    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="adminauthentication.html">
                <img src="../Images/logo.png" alt="hamrocollege" width="200" height="50">
            </a>
        </div>
    </nav>

    <div class="background-color container mt-5">
    <div class="row">
        <div class="col-md-6">
        <div class="container register-container">
            <form action="" method="POST" class="form py-4">
                <div>
                    <input type="text" class="form-control mb-3" name="name" id="name" placeholder="Name" required>
                    <input type="phone" class="form-control mb-3" name="phone" id="phone" placeholder="Phone number" required>
                    <input type="email" class="form-control mb-3" name="email" id="email" placeholder="Email address" required>
                    <input type="password" class="form-control mb-2" name="password" id="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z]).{7,}" title="Must contain at least one number and one lowercase letter, and at least 7 or more characters" required>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="" id="showpassword" onclick="showPassword()">
                    <label class="form-check-label" for="showpassword">Show Password</label>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" name="register-submit" id="register-submit" value="Register" style="background-color: #082465;">Register</button>
                </div>
            </form>
        </div>
        </div>
            
        <div class="col-md-5">
        <div class="container login-container">
            <form action="" method="POST" class="form py-4">
                <div>
                    <input type="email" class="form-control mb-3" name="email" id="email" placeholder="Email address" required>
                    <input type="password" class="form-control mb-2" name="password" id="adminpassword" placeholder="Password" required>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="" id="showpassword" onclick="showAdminPassword()">
                    <label class="form-check-label" for="showpassword">Show Password</label>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" name="login-submit" id="login-submit" value="Login" style="background-color: #082465;">Login</button>
                </div>
            </form>
        </div>
        </div>  
    </div>
    </div>

    <!-- Success Message -->

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="userSuccessToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Registration Successful</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">Your account has been created.</div>
    </div>
    </div>

    <!-- Error Message -->

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="userErrorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Registration Error</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="errorToastBody">User already exists.</div>
    </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="adminErrorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Login Error</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="adminErrorToastBody"></div>
    </div>
    </div>

    <script>
    <?php
        if(isset($success) && $success === 1){
            echo 'document.addEventListener("DOMContentLoaded", function() {
                var successToast = new bootstrap.Toast(document.getElementById("userSuccessToast"));
                successToast.show();
            });';
        }
    ?>
    </script>

    <script>
    <?php
        if(isset($result) && $result){
            echo 'document.addEventListener("DOMContentLoaded", function() {
                var errorToast = new bootstrap.Toast(document.getElementById("userErrorToast"));
                errorToast.show();
            });';
        }
    ?>
    </script>

    <script>
    <?php
        if(!$adminauth || !password_verify($password, $adminauth['password'])){
            echo 'document.addEventListener("DOMContentLoaded", function() {
                var errorToast = new bootstrap.Toast(document.getElementById("adminErrorToast"));
                document.getElementById("adminErrorToastBody").innerText = "Incorrect email or password.";
                errorToast.show();
            });';
        }
    ?>
    </script>

    <script src="../JS/adminscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
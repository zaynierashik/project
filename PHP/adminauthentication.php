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
                            <input type="password" class="form-control mb-2" name="password" id="adminpassword" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z]).{7,}" title="Must contain at least one number and one lowercase letter, and at least 7 or more characters" required>
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

    <script src="../JS/adminscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
<?php
    session_start();
    include 'connect.php';

    // $i_id = $_GET['userId'];

    if(!isset($_SESSION['username'])){
        header('location: homepage.php');
    }

    if(isset($_POST['update-submit'])){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE user_data SET name = :name, phone = :phone, email = :email, password = :password WHERE userId = :userId");
        $stmt ->bindParam(':name', $name);
        $stmt ->bindParam(':phone', $phone);
        $stmt ->bindParam(':email', $email);
        $stmt ->bindParam(':password', $hashed_password);
        $stmt ->bindParam(':userId', $i_id);
        $stmt ->execute();
        header('location: userpage.php');
    }

    if(isset($_POST['delete'])){
        $stmt = $conn->prepare("DELETE FROM user_data WHERE userId = :userId");
        $stmt->bindParam(':userId', $i_id);
        $stmt->execute();

        session_destroy();
        echo '<script>alert("Account deleted successfully."); window.location.href = "homepage.php";</script>';
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamro College</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../Favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../Favicon/favicon-16x16.png">
    <link rel="manifest" href="../Favicon/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/user.css">
</head>
<body>

    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="userpage.php">
                <img src="../Images/logo.png" alt="hamrocollege" width="200" height="50">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="usernavbar navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"><?php echo $_SESSION['username'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link logout-nav" aria-current="page">logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="link-container navbar navbar-expand-lg" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="userpage.php" class="nav-link" aria-current="page">Home</a>
            </li>
            <li class="nav-item ps-1">
                <a href="college.php" class="nav-link" aria-current="page">Colleges</a>
            </li>
            <li class="nav-item ps-1">
                <a href="course.php" class="nav-link" aria-current="page">Courses</a>
            </li>
            <li class="nav-item ps-1">
                <a href="admission.php" class="nav-link" aria-current="page">Admissions</a>
            </li>
        </ul>
    </div>

    <!-- Applied College and Course -->

    <div class="background-color">
    <div id="admission">
        <div class="container admission-container">
        <div class="row">
            <div class="col">
            <p class="admission-title">APPLIED COLLEGE & COURSE</p>
            <table class="table table-striped admission-table">
                <tr class="table-dark">
                    <td class="table-SN">SN</td>
                    <td class="table-body">College Name</td>
                    <td class="table-body">Course</td>
                </tr>

                <?php
                    $stmt = $conn->prepare("SELECT a.*, c.name FROM admission_data a JOIN college_data c ON a.collegeId = c.collegeId WHERE a.email = :username");
                    $stmt->bindParam(':username', $_SESSION['username']);
                    $stmt->execute();
    
                    $count = 1;
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                ?>
                <tr>
                    <td class="table-SN"><?= $count++; ?></td>
                    <td class="table-body"><?= $row['name']; ?></td>
                    <td class="table-body"><?= $row['title']; ?></td>
                </tr>

                <?php } ?>
            </table>
            </div>

            <div class="col-4">
                <p class="manage-title">MANAGE ACCOUNT</p>
                <div class="manage-container" id="register">
            <form action="" method="POST">
                <div>
                    <input type="text" class="form-control mb-3" name="name" id="name" placeholder="Name" readonly>
                    <input type="phone" class="form-control mb-3" name="phone" id="phone" placeholder="Phone number" required>
                    <input type="email" class="form-control mb-3" name="email" id="email" placeholder="Email address" required>
                    <input type="password" class="form-control mb-2" name="password" id="password" placeholder="New Password" pattern="(?=.*\d)(?=.*[a-z]).{7,}" title="Must contain at least one number and one lowercase letter, and at least 7 or more characters" required>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="" id="showpassword" onclick="showPassword()">
                    <label class="form-check-label" for="showpassword">Show Password</label>
                </div>
                
                <div class="d-grid">
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary w-75" name="register-submit" id="register-submit" value="Register" style="background-color: #082465;">Save</button>
                        </div>
                        <div class="col">
                            <i class="fa-solid fa-trash" style="color: black;"></i>
                        </div>

                    </div>
                </div>
            </form>
        </div>
            </div>
        </div>
        </div>
    </div>
    </div>

    <script src="../JS/userscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
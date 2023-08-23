<?php
    session_start();
    include 'connect.php';

        if(isset($_SESSION['username'])){
            header("location: userpage.php");
            exit;
        }elseif(isset($_SESSION['institutionemail'])){
            header("location: institution.php");
            exit;
        }

    if(isset($_POST['login-submit'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        $stmt = $conn->prepare("SELECT * FROM authentication WHERE email=:email");
        $stmt ->bindParam(':email', $email);
        $stmt ->execute();
        $userauth = $stmt->fetch();
    
        if($userauth){
            if($userauth['role'] == 'institution' && password_verify($password, $userauth['password'])){
                $_SESSION['institutionemail'] = $userauth['email'];
                $_SESSION['institutionName'] = $userauth['name'];
    
                $stmt2 = $conn->prepare("SELECT * FROM institution_data WHERE email=:email");
                $stmt2 ->bindParam(':email', $userauth['email']);
                $stmt2 ->execute();
                $userauthentication = $stmt2->fetch();
    
                $_SESSION['institutionId'] = $userauthentication['institutionId'];
                header("location: institution.php");
                exit;
            }
            elseif($userauth['role'] == 'user' && password_verify($password, $userauth['password'])){
                $_SESSION['username'] = $userauth['email'];
    
                $stmt3 = $conn->prepare("SELECT * FROM user_data WHERE email=:email");
                $stmt3 ->bindParam(':email', $userauth['email']);
                $stmt3 ->execute();
                $userauthentication = $stmt3->fetch();
    
                $_SESSION['userId'] = $userauthentication['userId'];
                header("location: userpage.php");
                exit;
            }
        }else{
            echo "<div class='usererror' id='userError'>Incorrect email or password.<div>";
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
    <link rel="stylesheet" href="/CSS/homepage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>
    <!-- Navbar -->
    
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="homepage.php">
                <img src="/Images/logo.png" alt="hamrocollege" width="200" height="50">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="register.php" class="nav-link me-3 register-btn">REGISTER</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn rounded-pill px-4 py-2 login-btn" id="modal-btn" role="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">LOGIN</a>
                    </li>
                </ul>
            </div>
    </div>
    </nav>
    
    <div class="container explore">
        <div class="row">
            <div class="col">
                <div class="display-5 fw-medium">Explore. Enroll.</div>
            <div class="my-1 search">Searching for colleges to expand your study? Explore some of the best colleges around you.</div>
        </div>
        <div class="col text-center">
            <img src="/Images/icon.png" class="img-fluid icon-one" alt="Illustration">
        </div>
    </div>
</div>

<!-- Modal -->

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="homepage-login-container">
                    <form action="" method="POST" class="form">
                        <div>
                            <input type="email" class="form-control mb-3" name="email" id="email" placeholder="Email address" required>
                            <input type="password" class="form-control mb-2" name="password" id="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z]).{7,}" title="Must contain at least one number and one lowercase letter, and at least 7 or more characters" required>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="" id="password" onclick="showPassword()">
                            <label class="form-check-label" for="password">Show Password</label>
                        </div>
                        
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary" name="login-submit" id="login-submit" value="Login" style="background-color: #082465;">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="background-color">
    
    <!-- Colleges -->
    
    <div class="container college-container">
        <p class="college-title">COLLEGES</p>
        <div class="container text-center college-card">
            <?php
                $sql = "SELECT * FROM college_data LIMIT 7";
                $stmt = $conn->query($sql);
                if($stmt->rowCount() > 0){
                    while($row = $stmt->fetch()){
                        echo '<div class="row row-gap-4">
                        <div class="col">
                        <a href="collegedetails.php?collegeId=' .$row['collegeId'] . '" class="card-link">
                        <div class="card" style="width: 18.75rem; border-radius: 15px;">
                        <img src="/Images/' . $row['logo'] . '" class="card-img-top college-logo" alt="...">
                        <div class="card-body">
                        <p class="college-name">' . $row['name'] . '</p>
                        <p class="address">' . $row['address'] . '</p>
                        </div>
                        </div>
                            </a>
                            </div>
                            </div>';
                        }
                }else{
                    echo "No colleges found.";
                }
                ?>
        </div>
    </div>

    
    
    <div class="container course-container">
        <p class="course-title">COURSES</p>
        <div class="container text-center course-card">
            <?php
                $sql = "SELECT * FROM course_data LIMIT 7";
                $stmt = $conn->query($sql);
                if($stmt->rowCount() > 0){
                    while($row = $stmt->fetch()){
                        echo '<div class="row row-gap-3">
                        <div class="col-md-3">
                        <a href="coursedetails.php?courseId=' . $row['courseId'] . '" class="card-link">
                        <div class="card" style="width: 18.7rem; height: 11vh; border-radius: 15px;">
                        <div class="card-body">
                        <p class="course-name">'.$row['title'].'</p>
                        </div>
                        </div>
                        </a>
                        </div>
                        </div>';
                    }
                }else{
                    echo "No courses found.";
                }
            ?>
        </div>
    </div>
    </div>
    
    
    
    <!-- Footer -->
    
    <footer class="container mt-5 footer">
        <div class="container">
            <div class="row container">
                <div class="col-lg-9">
                    <h5 class="text-uppercase">About Us</h5>
                    <p class="lh-lg" style="text-align: justify;">
                        Hamrocollege is an extensive search engine for the students, parents, and education industry players who are seeking information on higher education sector in Nepal. 
                        One can rely on Hamrocollege for getting most brief and relevant data on colleges and universities.
                    </p>
                </div>
                
                <div class="col ms-5 lh-lg">
                    <h5 class="text-uppercase">Contact</h5>
                <ul class="list-unstyled">
                    <li><i class="fa-solid fa-building" style="color: black; font-size: 0.87rem; padding-left: 0.1vw; padding-right: 0.60vw;"></i> Hamro College Pvt. Ltd.</li>
                    <li><i class="fa-solid fa-location-dot" style="color: black; font-size: 0.87rem; padding-left: 0.1vw; padding-right: 0.60vw;"></i> Gwarko, Lalitpur</li>
                    <li><i class="fa-solid fa-phone" style="color: black; font-size: 0.87rem; padding-right: 0.75vw;"></i>+977 98XXXXXXXX</li>
                    <li><i class="fa-solid fa-envelope" style="color: black; font-size: 0.87rem; padding-right: 0.75vw;"></i>info@hamrocollege.com</li>
                </ul>
            </div>
        </div>
    </div>
      
    <div class="p-1" style="margin-left: 1.25vw ; font-size: 0.87rem;">© Hamro College, All rights reserved 2023.</div>
    </footer>
    
    <div class="top hidden">
        <a href="#top" class="top"><i class="fa-solid fa-arrow-right fa-rotate-270 fa-lg" style="color: black;"></i></a>
    </div>

    <script>
        function showPassword(){
            var x = document.getElementById("password");
            if(x.type == "password"){
                x.type = "text";
            }else{
            x.type = "password";
        }
    }
    </script>
    
    <script src="script.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    
</body>
</html>
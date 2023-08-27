<?php
    session_start();
    include 'connect.php';

    if(!isset($_SESSION['username'])){
        header('location: homepage.php');
    }

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $feedback = $_POST['feedback'];
        
        $sql = "INSERT INTO feedback_data (username, email, feedback) VALUES (:username, :email, :feedback)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':feedback', $feedback);

        if($stmt->execute()){
            $success = 1;
        }
    }

    $courseId;
    $i = 0;
    $searchValue;
    @$count = $_GET['count'];
    @$status = $_GET['status'];

    do{
        @$courseId = $_GET[$i];
        @$collegeId = $_GET[$i];

        if($courseId != null){
            if($status == 1){
                $stmt = $conn->prepare("SELECT * FROM course_data WHERE courseId = :courseId");
                $stmt ->bindParam(':courseId', $courseId);
            }else{
                $stmt = $conn->prepare("SELECT * FROM college_data WHERE collegeId = :collegeId");
                $stmt ->bindParam(':collegeId', $collegeId);
            }

            $stmt ->execute();
            $value[$i] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $searchValue = true;
        }else{
            $searchValue = false;
        }
        $i++;
    }while($i<$count);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hamro College</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
    <link rel="manifest" href="../favicon/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/user.css">
</head>
<body>
    
    <!-- Search Result -->

    <?php
    if($searchValue){?>
        <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="userpage.php">
                <img src="../images/logo.png" alt="hamrocollege" width="200" height="50">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="usernavbar navbar-nav ms-auto">
                <li class="nav-item">
                    <form action="datasearch.php" method="POST" id="search" class="d-flex" role="search">
                        <input type="search" name="search" class="form-control d-flex rounded-pill search-bar" placeholder="Search colleges, courses . . ." aria-label="Search">
                    </form>
                </li>
                <li class="nav-item">
                    <a href="account.php" class="nav-link" aria-current="page"><i class="fa-solid fa-user" style="color: #000000;"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page">user@gmail.com</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link logout-nav" aria-current="page">logout</a>
                </li>
            </ul>
            </div>
        </div>
        </nav>

        <div class="background-color" style="min-height: 82.95vh;">
        <div class="container search-container">
        <p class="search-title">Found results:</p>
        <div class="text-center course-card">
            <div class="row row-gap-3">
            <?php
            foreach($value as $item){
            if($status == 1){
                echo '<div class="col">
                    <a href="coursedetails.php?courseId=' . $item[0]['courseId'] . '" class="card-link">
                    <div class="card" style="width: 18.7rem; height: 11vh; border-radius: 15px;">
                        <div class="card-body">
                            <p class="course-name">'.$item[0]['title'].'</p>
                        </div>
                    </div>
                    </a>
                </div>';
            }
            else{
                echo '<div class="col">
                    <a href="collegedetails.php?collegeId=' .$item[0]['collegeId'] . '" class="card-link">
                    <div class="card" style="width: 18.75rem; border-radius: 15px; min-height: 34vh">
                        <img src="../images/' . $item[0]['logo'] . '" class="card-img-top college-logo" alt="...">
                        <div class="card-body">
                            <p class="college-name">' . $item[0]['name'] . '</p>
                            <p class="address">' . $item[0]['address'] . '</p>
                        </div>
                     </div>
                    </a>
                </div>';
            }
            }
        ?>
        </div>
        </div>
        </div>
        <?php 
    }else{ 
    ?>

    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="userpage.php">
                <img src="../images/logo.png" alt="hamrocollege" width="200" height="50">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="usernavbar navbar-nav ms-auto">
                    <li class="nav-item">
                        <form action="datasearch.php" method="POST" id="search" class="d-flex" role="search">
                            <input type="search" name="search" class="form-control d-flex rounded-pill search-bar" placeholder="Search colleges, courses . . ." aria-label="Search">
                        </form>
                    </li>
                    <li class="nav-item">
                        <a href="account.php?userId=<?php echo $_SESSION['userId']; ?>" class="nav-link" aria-current="page"><i class="fa-solid fa-user" style="color: #000000;"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page">user@gmail.com</a>
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
                <a href="userpage.php" class="nav-link active" aria-current="page">Home</a>
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

    <!-- About Us -->

    <div class="background-color">
    <div>
        <div class="container about-container">
            <p class="about-title">ABOUT US</p>
            <p class="lh-lg" style="text-align: justify;">
                Hamrocollege was established in 2023 A.D. by a group of students for helping all Nepali students in informed decision-making. The response and feedback received from invaluable readers and customers persuaded the team to keep going forward.
                Hamrocollege has now become a leading educational portal for Nepal. It has revolutionized the way students search and get enrolled in various programs offered by academic institutions in Nepal. This site will help you search colleges, academic programs, locations and their facilities. 
                You will be able to extract the required information about these academic institutions and their services in no time. With Hamrocollege webpage you can compare and choose the best education services and meet your requirements in a short period of time. 
                Suggesting this website to your colleagues can prove as a blessing for them to meet their demands. Today, most of the students browse Hamrocollege to find out courses and colleges fit for them. 
                Our efforts were recognized at the international level as well. We were awarded the 'World Summit Youth Award' for promoting education in Nepal in the category "Education for All". 
            </p>
            <br>

            <p class="about-title">OUR APPROACH</p>
            <p class="lh-lg" style="text-align: justify;">
                Since its very inception Hamrocollege is helping millions of students by providing comprehensive, accurate, timely, and unbiased information regarding education like courses, results, scholarships, careers, colleges, news.
                Apart from that Hamrocollege also collaborates with colleges and organizations for the promotion of education through campaigns and events.
            </p>
        </div>

        <div class="container feedback-container">
            <p class="feedback-title">FEEDBACK</p>
            <form action="" method="POST">
                <div>
                    <label for="username" class="form-label">Name</label>
                    <input type="text" class="form-control mb-3" name="username" id="username" required>
                    
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control mb-3" name="email" id="email" required>

                    <label for="feedback" class="form-label">Feedback</label>
                    <textarea class="form-control mb-4" name="feedback" id="feedback" rows="3"></textarea>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" name="submit" id="submit" style="background-color: #082465;">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Successful Message -->

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="userSuccessToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Successful</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">Your feedback has been submitted.</div>
    </div>
    </div>

    <!-- Footer -->

    <footer class="container footer pb-3">
    <div class="row">
        <div class="col" style="margin-left: 0.55vw;">© Hamro College, All rights reserved 2023.</div>
        <div class="col">
            <i class="fa-solid fa-building" style="color: black; margin-left: -2.32vw;"></i> Hamro College Pvt. Ltd.
            <i class="fa-solid fa-location-dot" style="color: black; margin-left: 11px;"></i> Gwarko, Lalitpur
            <i class="fa-solid fa-phone" style="color: black; margin-left: 11px;"></i> +977 9876543210
            <i class="fa-solid fa-envelope" style="color: black; margin-left: 11px;"></i> info@hamrocollege.com
        </div>
    </div>
    </footer>

    </div>

    <?php } ?>

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

    <script src="../js/userscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
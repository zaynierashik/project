<?php
    session_start();
    include 'connect.php';

    if(!isset($_SESSION['username'])){
        header('location: homepage.php');
    }

    if(isset($_POST['application-submit'])){
        $username = $_POST['username'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $collegeId = $_POST['collegeId'];
        $title = $_POST['title'];
        $message = $_POST['message'];
    
        $sql = "INSERT INTO admission_data (username, phone, email, collegeId, title, message) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if($stmt->execute([$username, $phone, $email, $collegeId, $title, $message])){
            $success = 1;
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
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
    <link rel="manifest" href="../favicon/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/user.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    
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
                        <a href="account.php?userId=<?php echo $_SESSION['userId']; ?>" class="nav-link" aria-current="page"><i class="fa-solid fa-user" style="color: #000000;"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"><?php echo $_SESSION['username']; ?></a>
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
                <a href="admission.php" class="nav-link active" aria-current="page">Admissions</a>
            </li>
        </ul>
    </div>

    <!-- Admission -->

    <div class="background-color" style="min-height: 73.5vh;">
    <div class="container application-container">
        <div class="row">
            <div class="col">
            <p class="application-title">ADMISSION APPLICATION</p>
            <form action="" method="POST">
                <div>
                    <label for="username" class="form-label">Full name</label>
                    <input type="text" class="form-control mb-3" name="username" id="username" required>
                    
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control mb-3" name="email" id="email" required>

                    <label for="phone" class="form-label">Phone number</label>
                    <input type="number" class="form-control mb-3" name="phone" id="phone" required>

                    <label for="collegeId" class="form-label">Apply to</label>
                    <select class="form-select mb-3" name="collegeId" id="collegeId" required>
                        <option value=""></option>
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM college_data");
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($result as $row) {
                                $selected = ($row['collegeId'] == $_POST['collegeId']) ? "selected" : "";
                                echo "<option value='" . $row['collegeId'] . "' " . $selected . ">" . $row['name'] . "</option>";
                            }
                        ?>
                    </select>

                    <label for="title" class="form-label">Program interested in</label>
                    <select class="form-select mb-3" name="title" id="title" required>
                        <option value=""></option>
                    </select>

                    <label for="message" class="form-label">Queries (Optional)</label>
                    <textarea class="form-control mb-4" name="message" id="message" rows="3"></textarea>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" name="application-submit" id="application-submit" value="Register" style="background-color: #082465;">Submit Application</button>
                </div>
            </form>
            </div>

            <div class="col-4">
                <p class="admission-title mb-5" style="color: #e6e9ef;">BANNER</p>
                <div class="mb-2">
                    <a href="https://kathford.edu.np/" target="_blank"><img src="../images/kathfordImg.jpg" class="d-block w-100" alt="Kathford International College of Engineering and Management"></a>
                </div>
                
                <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <a href="https://softwarica.edu.np/" target="_blank"><img src="../images/softwaricaImg.jpg" class="d-block w-100" alt="Softwarica College of IT & E-commerce"></a>
                    </div>
                    <div class="carousel-item">
                        <a href="https://islington.edu.np/" target="_blank"><img src="../images/islingtonImg.png" class="d-block w-100" alt="Islington College"></a>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <div>
        <p class="admission-application-title">FEATURED ADMISSION</p>
        <div class="text-center admission-card">
            <div class="row row-gap-4">
            <?php
                $collegeIds = array(15, 5, 7, 1, 3);
                foreach ($collegeIds as $collegeId){
                    $sql = "SELECT * FROM college_data WHERE collegeId = $collegeId";
                    $stmt = $conn->query($sql);
                    
                    if($stmt->rowCount() > 0){
                        $row = $stmt->fetch();
                        echo '<div class="col">
                            <a href="collegedetails.php?collegeId=' .$row['collegeId'] . '" class="card-link">
                            <div class="card" style="width: 15rem; border-radius: 15px; min-height: 34vh">
                                <img src="../images/' . $row['logo'] . '" class="card-img-top college-logo" alt="...">
                                <div class="card-body">
                                    <p class="college-name">' . $row['name'] . '</p>
                                    <p class="address">' . $row['address'] . '</p>
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
    </div>
    </div>

    <!-- Success Message -->

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="userSuccessToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="successToastHead"></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="successToastBody"></div>
    </div>
    </div>

    <script>
    <?php
        if(isset($success) && $success === 1){
            echo 'document.addEventListener("DOMContentLoaded", function(){
                var successToast = new bootstrap.Toast(document.getElementById("userSuccessToast"));
                document.getElementById("successToastHead").innerHTML = "Form Submission Successful";
                document.getElementById("successToastBody").innerHTML = "Your application form has been submitted.";
                successToast.show();
            });';
        }
    ?>
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function(){
            const collegeSelect = document.getElementById("collegeId");
            const courseSelect = document.getElementById("title");

            collegeSelect.addEventListener("change", function(){
                const selectedCollegeId = collegeSelect.value;

                if(!selectedCollegeId){
                    courseSelect.innerHTML = "<option value=''></option>";
                    return;
                }

                fetch("getcourse.php?collegeId=" + selectedCollegeId)
                    .then(response => response.json())
                    .then(data => {
                        courseSelect.innerHTML = "<option value=''></option>";
                        data.forEach(course => {
                            courseSelect.innerHTML += `<option value='${course.abbreviation}'>${course.title}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error("Error fetching courses: " + error);
                    });
            });
        });
    </script>

    <script src="../js/userscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
<?php
    session_start();
    include 'connect.php';

    if(!isset($_SESSION['institutionemail'])){
        header('location: homepage.php');
    }

    if(isset($_POST['submit'])){
        $affiliation = $_POST['affiliation'];
        $field = $_POST['field'];
        $title = $_POST['title'];
        $abbreviation = $_POST['abbreviation'];
        $content = nl2br($_POST['content']);
        $eligibility = nl2br($_POST['eligibility']);
        $job = nl2br($_POST['job']);

        $sql = "SELECT * FROM course_data WHERE affiliation = ? && title = ?";
        $stmt = $conn->prepare($sql);
        $stmt ->execute([$affiliation, $title]);
        $result = $stmt->fetch();

        if($result){
            echo '<script> alert("Course already exists in database.") </script>';
        }else{
            if(empty($_POST['affiliation']) || empty($_POST['field']) || empty($_POST['title']) || empty($_POST['abbreviation']) || empty($_POST['content']) || empty($_POST['eligibility']) || empty($_POST['job'])){
                echo '<script> alert("Please fill all the fields."); window.location.href = "managecourse.php"; </script>';
            }else{
                $sql = "INSERT INTO course_data (affiliation, field, title, abbreviation, content, eligibility, job) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt ->execute([$affiliation, $field, $title, $abbreviation, $content, $eligibility, $job]);
                echo '<script> alert("Course added successfully."); window.location.href = "managecourse.php"; </script>';
            }
        }
    }

    if(isset($_POST['courseId'])){
        $courseId = $_POST['courseId'];
    }

    $stmt = $conn->prepare("SELECT * FROM course_data WHERE courseId = :courseId");
    $stmt ->bindParam(":courseId",$courseId);
    $stmt ->execute();
    $value = $stmt->fetch(PDO::FETCH_ASSOC);

    $courseId = isset($value['courseId']) ? $value['courseId'] : '';
    $title = isset($value['title']) ? $value['title'] : '';
    $abbreviation = isset($value['abbreviation']) ? $value['abbreviation'] : '';
    $content = isset($value['content']) ? $value['content'] : '';
    $eligibility = isset($value['eligibility']) ? $value['eligibility'] : '';
    $job = isset($value['job']) ? $value['job'] : '';

    if(isset($_POST['update-submit'])){
        $courseId = $_POST['courseId'];
        $title = $_POST['title'];
        $abbreviation = $_POST['abbreviation'];
        $content = nl2br($_POST['content']);
        $eligibility = nl2br($_POST['eligibility']);
        $job = nl2br($_POST['job']);

        if(empty($_POST['courseId']) || empty($_POST['title']) || empty($_POST['abbreviation']) || empty($_POST['content']) || empty($_POST['eligibility']) || empty($_POST['job'])){
            echo '<script> alert("Please fill all the fields."); window.location.href = "managecourse.php"; </script>';
        }else{
            $stmt = $conn->prepare("UPDATE course_data SET courseId = :courseId, title = :title, abbreviation = :abbreviation, content = :content, eligibility = :eligibility, job = :job WHERE courseId = :courseId");
            $stmt ->bindParam(":courseId", $courseId);
            $stmt ->bindParam(":title", $title);
            $stmt ->bindParam(":abbreviation", $abbreviation);
            $stmt ->bindParam(":content", $content);
            $stmt ->bindParam(":eligibility", $eligibility);
            $stmt ->bindParam(":job", $job);
            $stmt ->execute();
    
            echo '<script> alert("Course updated successfully."); window.location.href = "managecourse.php"; </script>';
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
    <link rel="apple-touch-icon" sizes="180x180" href="../Favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../Favicon/favicon-16x16.png">
    <link rel="manifest" href="../Favicon/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/institution.css">
</head>
<body>
    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="institution.php">
                <img src="../Images/logo.png" alt="hamrocollege" width="200" height="50">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="usernavbar navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"><?php echo $_SESSION['institutionemail'] ?></a>
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
                <a href="institution.php" class="nav-link" aria-current="page">Dashboard</a>
            </li>
            <li class="nav-item ps-1">
                <a href="managecollege.php?institutionId=<?php echo $_SESSION['institutionId'] ?>" class="nav-link" aria-current="page">Manage College</a>
            </li>
            <li class="nav-item ps-1">
                <a href="managecourse.php" class="nav-link active" aria-current="page">Manage Courses</a>
            </li>
        </ul>
    </div>

    <!-- Course Details Form - Update Course-->

    <div class="background-color">
        <div class="container update-container">
        <p class="update-title">COURSE DETAILS <span class="add-course fs-6">Add Course</span></p>
        <form action="" method="POST">
            <div class="row mb-3">
                <div class="col-2">
                    <select class="form-select" name="courseId" id="courseId" onchange="this.form.submit()">
                        <option value="">Course ID</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
                <div class="col-2">
                    <input type="text" class="form-control" name="abbreviation" id="abbreviation" placeholder="Abbreviation" value="Abbreviation">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="title" id="title" placeholder="Course Name" value="Course Name" readonly>
                </div>
            </div>
        
            <div>
                <textarea class="form-control mb-2" name="content" id="content" rows="7" placeholder="Course Details"></textarea>
                <textarea class="form-control mb-2" name="eligibility" id="eligibility" rows="7" placeholder="Eligibility"></textarea>
                <textarea class="form-control mb-4" name="job" id="job" rows="7" placeholder="Job Prospects"></textarea>
            </div>
        
            <div class="d-grid">
                <button type="submit" class="btn btn-primary" name="update-submit" id="update-submit" style="background-color: #082465;">Update</button>
            </div>
        </form>
        </div>

        <!-- Course Details Form - Add Course-->

        <div class="container add-container">
            <p class="add-title">ADD COURSE <span class="update-course fs-6">Update Course</span></p>
            <form action="" method="POST" class="form">
                <div class="row mb-3">
                    <div class="col-2">
                        <select class="form-select" name="affiliation" id="affiliation">
                            <option value="">Affiliation</option>
                            <option value="TU">TU</option>
                            <option value="KU">KU</option>
                            <option value="PU">PU</option>
                            <option value="International">International</option>
                        </select>
                    </div>

                    <div class="col-2">
                        <select class="form-select" name="field" id="field">
                            <option value="">Field of Study</option>
                            <option value="TU">TU</option>
                            <option value="KU">KU</option>
                            <option value="PU">PU</option>
                            <option value="International">International</option>
                        </select>
                    </div>

                    <div class="col-2">
                        <input type="text" class="form-control" name="abbreviation" id="abbreviation" placeholder="Abbreviation" value="Abbreviation">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Course Name" value="Course Name">
                    </div>
                </div>
            
                <div>
                    <textarea class="form-control mb-2" name="content" id="content" rows="7" placeholder="Course Details"></textarea>
                    <textarea class="form-control mb-2" name="eligibility" id="eligibility" rows="7" placeholder="Course Eligibility"></textarea>
                    <textarea class="form-control mb-4" name="job" id="job" rows="7" placeholder="Job Prospects"></textarea>
                </div>
            
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" name="submit" id="submit" style="background-color: #082465;">Add</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addCourseSpan = document.querySelector(".add-course");
            const updateCourseSpan = document.querySelector(".update-course");
            const updateContainer = document.querySelector(".update-container");
            const addContainer = document.querySelector(".add-container");
    
            addContainer.style.display = "none";
    
            addCourseSpan.addEventListener("click", function() {
                updateContainer.style.display = "none";
                addContainer.style.display = "block";
            });
    
            updateCourseSpan.addEventListener("click", function() {
                addContainer.style.display = "none";
                updateContainer.style.display = "block";
            });
        });
    </script>

    <script src="../JS/institutionscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
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
        $career = nl2br($_POST['career']);

        $sql = "SELECT * FROM course_data WHERE affiliation = ? && title = ?";
        $stmt = $conn->prepare($sql);
        $stmt ->execute([$affiliation, $title]);
        $result = $stmt->fetch();

        if($result){
            $error = 2;
        }else{
            if(empty($_POST['affiliation']) || empty($_POST['field']) || empty($_POST['title']) || empty($_POST['abbreviation']) || empty($_POST['content']) || empty($_POST['eligibility']) || empty($_POST['job']) || empty($_POST['career'])){
                $error = 0;
            }else{
                $sql = "INSERT INTO course_data (affiliation, field, title, abbreviation, content, eligibility, job, career) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                if($stmt ->execute([$affiliation, $field, $title, $abbreviation, $content, $eligibility, $job, $career])){
                    $error = 1;
                }
            }
        }
    }

    if(isset($_POST['courseId'])){
        $courseId = $_POST['courseId'];
    }

    $collegeId = $_SESSION['institutionId'];

    $stmt = $conn->prepare("SELECT * FROM course_data WHERE courseId = :courseId");
    $stmt ->bindParam(":courseId", $courseId);
    $stmt ->execute();
    $value = $stmt->fetch(PDO::FETCH_ASSOC);

    $courseId = isset($value['courseId']) ? $value['courseId'] : '';
    $title = isset($value['title']) ? $value['title'] : '';
    $abbreviation = isset($value['abbreviation']) ? $value['abbreviation'] : '';
    $content = isset($value['content']) ? $value['content'] : '';
    $eligibility = isset($value['eligibility']) ? $value['eligibility'] : '';
    $job = isset($value['job']) ? $value['job'] : '';
    $career = isset($value['career']) ? $value['career'] : '';

    if(isset($_POST['update-submit'])){
        $courseId = $_POST['courseId'];
        $title = $_POST['title'];
        $abbreviation = $_POST['abbreviation'];
        $content = nl2br($_POST['content']);
        $eligibility = nl2br($_POST['eligibility']);
        $job = nl2br($_POST['job']);
        $career = nl2br($_POST['career']);

        if(empty($_POST['courseId']) || empty($_POST['title']) || empty($_POST['abbreviation']) || empty($_POST['content']) || empty($_POST['eligibility']) || empty($_POST['job']) || empty($_POST['career'])){
            $success = 0;
        }else{
            $stmt = $conn->prepare("UPDATE course_data SET courseId = :courseId, title = :title, abbreviation = :abbreviation, content = :content, eligibility = :eligibility, job = :job, career = :career WHERE courseId = :courseId");
            $stmt ->bindParam(":courseId", $courseId);
            $stmt ->bindParam(":title", $title);
            $stmt ->bindParam(":abbreviation", $abbreviation);
            $stmt ->bindParam(":content", $content);
            $stmt ->bindParam(":eligibility", $eligibility);
            $stmt ->bindParam(":job", $job);
            $stmt ->bindParam(":career", $career);
    
            if($stmt->execute()){
                $success = 1;
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
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
    <link rel="manifest" href="../favicon/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/institution.css">
</head>
<body>
    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="institution.php">
                <img src="../images/logo.png" alt="hamrocollege" width="200" height="50">
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
                <select class="form-select" name="courseId" id="courseId" style="font-size: 0.87rem;" onchange="this.form.submit()">
                    <option value="">Course ID</option>
                    <?php
                        $stmt = $conn->prepare("SELECT c.courseId, c.title FROM course_data c INNER JOIN college_course cc ON c.courseId = cc.courseId WHERE cc.collegeId = :collegeId");
                        $stmt->bindParam(":collegeId", $collegeId);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($result as $row) {
                            $selected = ($row['courseId'] == $courseId) ? "selected" : "";
                            echo "<option value='".$row['courseId']."' ".$selected.">".$row['title']."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-2">
                <input type="text" class="form-control" name="abbreviation" id="abbreviation" placeholder="Abbreviation" value="<?php echo $abbreviation ?>" readonly>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="title" id="title" placeholder="Course Name" value="<?php echo $title ?>" readonly>
            </div>
            </div>
        
            <div>
                <textarea class="form-control mb-2" name="content" id="content" rows="10" placeholder="Course Details"><?php echo $content ?></textarea>
                <textarea class="form-control mb-2" name="eligibility" id="eligibility" rows="5" placeholder="Course Eligibility"><?php echo $eligibility ?></textarea>
                <textarea class="form-control mb-2" name="job" id="job" rows="5" placeholder="Job Prospects"><?php echo $job ?></textarea>
                <textarea class="form-control mb-4" name="career" id="career" rows="10" placeholder="Prospect Careers"><?php echo $career ?></textarea>
            </div>
        
            <div class="d-grid">
                <button type="submit" class="btn btn-primary" name="update-submit" id="update-submit" style="background-color: #082465;">Update</button>
            </div>
        </form>
        </div>

        <!-- Course Details Form - Add Course-->

        <div class="container add-container">
            <p class="add-title">ADD COURSE <span class="update-course fs-6">Update Course</span></p>
            <form action="" method="POST">
                <div class="row mb-3">
                    <div class="col-2">
                        <select class="form-select" name="affiliation" id="affiliation" style="font-size: 0.87rem;">
                            <option value="">Affiliation</option>
                            <option value="TU">TU</option>
                            <option value="KU">KU</option>
                            <option value="PU">PU</option>
                            <option value="International">International</option>
                        </select>
                    </div>

                    <div class="col-2">
                        <select class="form-select" name="field" id="field" style="font-size: 0.87rem;">
                            <option value="">Field of Study</option>
                            <option value="Computer and Information Technology">Computer and Information Technology</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Management">Management</option>
                            <option value="Science and Technology">Science and Technology</option>
                            <option value="Humanities and Social Sciences">Humanities and Social Sciences</option>
                            <option value="Agriculture, Forestry and Animal Sciences">Agriculture, Forestry and Animal Sciences</option>
                            <option value="Economics">Economics</option>
                            <option value="Health Professional Education">Health Professional Education</option>
                            <option value="Law">Law</option>
                        </select>
                    </div>

                    <div class="col-2">
                        <input type="text" class="form-control" name="abbreviation" id="abbreviation" placeholder="Abbreviation">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Course Name">
                    </div>
                </div>
            
                <div>
                    <textarea class="form-control mb-2" name="content" id="content" rows="7" placeholder="Course Details"></textarea>
                    <textarea class="form-control mb-2" name="eligibility" id="eligibility" rows="7" placeholder="Course Eligibility"></textarea>
                    <textarea class="form-control mb-2" name="job" id="job" rows="7" placeholder="Job Prospects"></textarea>
                    <textarea class="form-control mb-4" name="career" id="career" rows="7" placeholder="Prospect Careers"></textarea>
                </div>
            
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" name="submit" id="submit" style="background-color: #082465;">Add</button>
                </div>
            </form>
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

    <!-- Error Message -->

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="userErrorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="errorToastHead"></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="errorToastBody"></div>
    </div>
    </div>

    <script>
    <?php
        if(isset($success) && $success === 0){
            echo 'document.addEventListener("DOMContentLoaded", function(){
                var errorToast = new bootstrap.Toast(document.getElementById("userErrorToast"));
                document.getElementById("errorToastHead").innerHTML = "Update Error";
                document.getElementById("errorToastBody").innerHTML = "Fill up all the fields.";
                errorToast.show();
            });';
        }
    ?>
    </script>

    <script>
    <?php
        if(isset($success) && $success === 1){
            echo 'document.addEventListener("DOMContentLoaded", function(){
                var successToast = new bootstrap.Toast(document.getElementById("userSuccessToast"));
                document.getElementById("successToastHead").innerHTML = "Update Successful";
                document.getElementById("successToastBody").innerHTML = "Course data has been updated.";
                successToast.show();
            });';
        }
    ?>
    </script>

    <script>
    <?php
        if(isset($error) && $error === 2){
            echo 'document.addEventListener("DOMContentLoaded", function(){
                var errorToast = new bootstrap.Toast(document.getElementById("userErrorToast"));
                document.getElementById("errorToastHead").innerHTML = "Course Insertion Error";
                document.getElementById("errorToastBody").innerHTML = "Course already exists.";
                errorToast.show();
            });';
        }
    ?>
    </script>

    <script>
    <?php
        if(isset($error) && $error === 0){
            echo 'document.addEventListener("DOMContentLoaded", function(){
                var errorToast = new bootstrap.Toast(document.getElementById("userErrorToast"));
                document.getElementById("errorToastHead").innerHTML = "Course Insertion Error";
                document.getElementById("errorToastBody").innerHTML = "Fill up all the fields.";
                errorToast.show();
            });';
        }
    ?>
    </script>

    <script>
    <?php
        if(isset($error) && $error === 1){
            echo 'document.addEventListener("DOMContentLoaded", function(){
                var successToast = new bootstrap.Toast(document.getElementById("userSuccessToast"));
                document.getElementById("successToastHead").innerHTML = "Course Insertion Successful";
                document.getElementById("successToastBody").innerHTML = "Course added successfully.";
                successToast.show();
            });';
        }
    ?>
    </script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            const addCourseSpan = document.querySelector(".add-course");
            const updateCourseSpan = document.querySelector(".update-course");
            const updateContainer = document.querySelector(".update-container");
            const addContainer = document.querySelector(".add-container");
    
            addContainer.style.display = "none";
    
            addCourseSpan.addEventListener("click", function(){
                updateContainer.style.display = "none";
                addContainer.style.display = "block";
            });
    
            updateCourseSpan.addEventListener("click", function(){
                addContainer.style.display = "none";
                updateContainer.style.display = "block";
            });
        });
    </script>

    <script src="../js/institutionscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
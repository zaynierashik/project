<?php
    session_start();
    include 'connect.php';

    if(isset($_GET['affiliation'])){
        $affiliation = $_GET['affiliation'];
    
        $sql = "SELECT * FROM college_data WHERE affiliation = :affiliation";
        $stmt = $conn->prepare($sql);
        $stmt ->bindParam(':affiliation', $affiliation);
        $stmt ->execute();
        $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    if(isset($_GET['field'])){
        $field = $_GET['field'];
    
        $sql = "SELECT * FROM course_data WHERE field = :field";
        $stmt = $conn->prepare($sql);
        $stmt ->bindParam(':field', $field);
        $stmt ->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
</head>
<body>

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

    <!-- Colleges -->

    <?php
        if(isset($affiliation) && !empty($colleges)): ?>
        <div class="background-color" style="min-height: 82.95vh;">
        <div class="container college-container">
            <p class="college-title">COLLEGES</p>
            <div class="text-center college-card">
            <div class="row row-gap-4">
            <?php
                foreach($colleges as $college):
            ?>
                <div class="col">
                    <a href="collegedetails.php?collegeId=<?php echo $college['collegeId']; ?>" class="card-link">
                        <div class="card" style="width: 18.75rem; border-radius: 15px; min-height: 34vh">
                            <img src="../images/<?php echo $college['logo']; ?>" class="card-img-top college-logo" alt="<?php echo $college['name']; ?>">
                            <div class="card-body">
                                <p class="college-name"><?php echo $college['name']; ?></p>
                                <p class="address"><?php echo $college['address']; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        
            </div>
            </div>
        </div>
        </div>

    <?php endif; ?>

    <!-- Courses -->

    <?php
        if(isset($field) && !empty($courses)): ?>
        <div class="background-color" style="min-height: 82.95vh;">
        <div class="container course-container">
            <p class="course-title">COURSES</p>
            <div class="text-center course-card">
            <div class="row row-gap-3">
            <?php
                foreach($courses as $course):
            ?>
                <div class="col">
                    <a href="coursedetails.php?courseId=<?php echo $course['courseId']; ?>" class="card-link">
                        <div class="card" style="width: 18.7rem; height: 11vh; border-radius: 15px;">
                        <div class="card-body">
                            <p class="course-name"><?php echo $course['title']; ?></p>
                        </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

            </div>
            </div>
        </div>
        </div>

    <?php endif; ?>

</body>
</html>
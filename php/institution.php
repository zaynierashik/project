<?php
    session_start();
    include 'connect.php';

    if (!isset($_SESSION['institutionemail'])) {
        header('location: homepage.php');
        exit();
    }

    $institutionId = $_SESSION['institutionId'];
    $stmt = $conn->prepare("SELECT * FROM admission_data WHERE collegeId = :institutionId");
    $stmt ->bindParam(':institutionId', $institutionId);
    $stmt ->execute();
    $admissionData = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <a href="userpage.php" class="nav-link active" aria-current="page">Dashboard</a>
            </li>
            <li class="nav-item ps-1">
                <a href="managecollege.php?institutionId=<?php echo $_SESSION['institutionId'] ?>" class="nav-link" aria-current="page">Manage College</a>
            </li>
            <li class="nav-item ps-1">
                <a href="managecourse.php" class="nav-link" aria-current="page">Manage Courses</a>
            </li>
        </ul>
    </div>

    <!-- Admission Table -->

    <div class="background-color" style="min-height: 73.5vh;">
        <div id="admission">
            <div class="container admission-container">
                <p class="admission-title">ADMISSION LIST</p>
                <table class="table table-striped institution-admission-table">
                    <tr class="table-dark">
                        <td class="table-SN">S.N.</td>
                        <td class="table-body">Name</td>
                        <td class="table-body">Email Address</td>
                        <td class="table-body">Phone Number</td>
                        <td class="table-body">Interested Course</td>
                    </tr>

                    <?php
                        $count = 1;
                        foreach ($admissionData as $row){
                    ?>
                    <tr>
                        <td class="table-SN"><?php echo $count++ ?></td>
                        <td class="table-body"><?php echo $row['username'] ?></td>
                        <td class="table-body"><?php echo $row['email'] ?></td>
                        <td class="table-body"><?php echo $row['phone'] ?></td>
                        <td class="table-body"><?php echo $row['title'] ?></td>
                    </tr>
                    <?php 
                        } 
                    ?>
                </table>
            </div>
        </div>
    </div>

    <script src="../js/institutionscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
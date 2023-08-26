<?php
    session_start();
    include 'connect.php';
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
        <a class="navbar-brand" href="homepage.php">
            <img src="../Images/logo.png" alt="hamrocollege" width="200" height="50">
        </a>
    </div>
    </nav>

    <div class="background-color container mt-5" style="width: 82.5%;">
    <table class="table course-details-table" style="text-align: justify;">
        <?php
            if(isset($_GET['courseId'])){
                $courseId = $_GET['courseId'];

                $sql = "SELECT * FROM course_data WHERE courseId=:courseId";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':courseId', $courseId);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            if(count($result)>0){
        ?>
        <thead>
            <?php
                $i=0;
                foreach($result as $row){
            ?>
            <tr>
                <th scope="col" class="text-center h4" colspan="2"><i class="fa-sharp fa-solid fa-graduation-cap fa-lg mb-5 mt-5" style="color: #000000; font-size: 2.5rem;"></i> <?php echo $row['title']; ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="lh-lg"><?php echo $row['content']; ?></td>
            </tr>
            <tr>
                <td class="table-title">Eligibility</td>
            </tr>
            <tr>
                <td class="lh-lg">
                <?php
                    $eligibility = explode("\n", $row['eligibility']);
                    echo "<ul>";
                    foreach($eligibility as $point){
                        echo "<li>$point</li>";
                    }
                    echo "</ul>";
                ?>
                </td>
            </tr>
            <tr>
                <td class="table-title">Job Prospects</td>
            </tr>
            <tr>
                <td class="lh-lg"><?php echo $row['job']; ?></td>
            </tr>
            <tr>
                <td class="table-title">Prospect Careers</td>
            </tr>
            <tr>
                <td class="lh-lg">
                <?php
                    $career = explode("\n", $row['career']);
                    echo "<ul>";
                    foreach($career as $point){
                        echo "<li>$point</li>";
                    }
                    echo "</ul>";
                ?>
                </td>
            </tr>
            <?php
                $i++;
                }
            ?>
        </tbody>
        </table>
    </div>

    <!-- Offering Colleges  -->

    <div class="container college-container offering-college">
        <p class="container table-title">OFFERING COLLEGES</p>
        <div class="container text-center college-card">
            <div class="row row-gap-4">
                <div class="col">
                    <a href="collegedetails.php" class="card-link">
                        <div class="card" style="width: 13.7rem; border-radius: 15px;">
                            <img src="../Images/kathford.png" class="card-img-top college-logo" alt="...">
                            <div class="card-body">
                                <p class="college-name">Kathford International College of Engineering and Management</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="collegedetails.php" class="card-link">
                        <div class="card" style="width: 13.7rem; border-radius: 15px;">
                            <img src="../Images/kathford.png" class="card-img-top college-logo" alt="...">
                            <div class="card-body">
                                <p class="college-name">Kathford International College of Engineering and Management</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="collegedetails.php" class="card-link">
                        <div class="card" style="width: 13.7rem; border-radius: 15px;">
                            <img src="../Images/kathford.png" class="card-img-top college-logo" alt="...">
                            <div class="card-body">
                                <p class="college-name">Kathford International College of Engineering and Management</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="collegedetails.php" class="card-link">
                        <div class="card" style="width: 13.7rem; border-radius: 15px;">
                            <img src="../Images/kathford.png" class="card-img-top college-logo" alt="...">
                            <div class="card-body">
                                <p class="college-name">Kathford International College of Engineering and Management</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="collegedetails.php" class="card-link">
                        <div class="card" style="width: 13.7rem; border-radius: 15px;">
                            <img src="../Images/kathford.png" class="card-img-top college-logo" alt="...">
                            <div class="card-body">
                                <p class="college-name">Kathford International College of Engineering and Management</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php 
        }else{
            echo "No content available!";
        }
    ?>
    
    <script src="../JS/script.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
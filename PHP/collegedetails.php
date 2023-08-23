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
    <table class="table college-details-table" style="text-align: justify;">
        <?php
            if(isset($_GET['collegeId'])){
                $collegeId = $_GET['collegeId'];

                $sql = "SELECT * FROM college_data WHERE collegeId=:collegeId";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':collegeId', $collegeId);
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
                <th scope="col" class="text-center h4" colspan="2"><img src="../Images/<?php echo $row['logo']; ?>" class="img-fluid" alt="College Logo"> <?php echo $row['name']; ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="table-title" colspan="2" colspan="2">Overview</td>
            </tr>
            <tr>
                <td class="lh-lg" colspan="2"><?php echo $row['overview']; ?></td>
            </tr>
            <tr>
                <td class="table-title" colspan="2">Reason to Choose</td>
            </tr>
            <tr>
                <td class="lh-lg" colspan="2">
                    <?php
                    $reason = explode("\n", $row['reason']);
                    echo "<ul>";
                    foreach($reason as $point){
                        echo "<li>$point</li>";
                    }
                    echo "</ul>";
                ?>
                </td>
            </tr>
            <tr>
                <td class="table-title" colspan="2">Principal Message</td>
            </tr>
            <tr>
                <td class="lh-lg" colspan="2"><?php echo $row['message']; ?></td>
            </tr>
            <tr>
                <td class="table-title" colspan="2">Offered Programs</td>
            </tr>
            <tr>
                <td class="lh-lg" colspan="2">
                    <?php
                    $program = explode("\n", $row['program']);
                    echo "<ul>";
                    foreach($program as $point){
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
            <tr>
                <td class="table-title">Location</td>
                <td class="table-title">Contact</td>
            </tr>
            <tr>
                <td>
                    <?php echo $row['location']; ?>
                </td>
                <td>
                    <ul class="list-unstyled lh-lg" style="font-size: 0.85rem;">
                        <li><i class="fa-solid fa-building" style="color: black; padding-right: 0.60vw; padding-left: 0.15vw;"></i> <?php echo $row['name']; ?></li>
                        <li><i class="fa-solid fa-location-dot" style="color: black; padding-right: 0.60vw; padding-left: 0.15vw;"></i> <?php echo $row['address']; ?></li>
                        <li><i class="fa-solid fa-phone" style="color: black; padding-right: 0.5vw;"></i> <?php echo $row['phone']; ?></li>
                        <li><i class="fa-solid fa-envelope" style="color: black; padding-right: 0.5vw;"></i> <?php echo $row['email']; ?></li>
                        <li><i class="fa-solid fa-globe" style="color: black; padding-right: 0.5vw;"></i><a href="<?php echo $row['website']?>" target="_blank" style="text-decoration: none; color: black;"> <?php echo $row['website']?></a></li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
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
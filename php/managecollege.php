<?php
    session_start();
    include 'connect.php';

    $i_id = $_GET['institutionId'];

    if(!isset($_SESSION['institutionemail'])){
        header('location: homepage.php');
    }

    $stmt = $conn->prepare("SELECT * FROM college_data WHERE collegeId = :collegeId");
    $stmt ->bindParam(":collegeId", $collegeId);
    $stmt ->execute();
    $value = $stmt->fetch(PDO::FETCH_ASSOC);

    $collegeId = isset($value['collegeId']) ? $value['collegeId'] : '';
    $affiliation = isset($value['affiliation']) ? $value['affiliation'] : '';
    $name = isset($value['name']) ? $value['name'] : '';
    $overview = isset($value['overview']) ? $value['overview'] : '';
    $message = isset($value['message']) ? $value['message'] : '';
    $reason = isset($value['reason']) ? $value['reason'] : '';
    $program = isset($value['program']) ? $value['program'] : '';
    $phone = isset($value['phone']) ? $value['phone'] : '';
    $email = isset($value['email']) ? $value['email'] : '';
    $website = isset($value['website']) ? $value['website'] : '';
    $address = isset($value['address']) ? $value['address'] : '';
    $location = isset($value['location']) ? $value['location'] : '';
    $logo = isset($value['logo']) ? $value['logo'] : '';

    if(isset($_POST['update-college-submit'])){
        $collegeId = $_POST['collegeId'];
        $affiliation = $_POST['affiliation'];
        $name = $_POST['name'];
        $overview = nl2br($_POST['overview']);
        $message = nl2br($_POST['message']);
        $reason = nl2br($_POST['reason']);
        $program = nl2br($_POST['program']);
        $phone = nl2br($_POST['phone']);
        $email = nl2br($_POST['email']);
        $website = nl2br($_POST['website']);
        $address = nl2br($_POST['address']);
        $location = nl2br($_POST['location']);
        $logo = nl2br($_POST['logo']);

        if(empty($_POST['collegeId']) || empty($_POST['affiliation']) || empty($_POST['name']) || empty($_POST['overview']) || empty($_POST['message']) || empty($_POST['reason']) || empty($_POST['program']) || empty($_POST['phone']) || empty($_POST['email']) || empty($_POST['website']) || empty($_POST['address']) || empty($_POST['location']) || empty($_POST['logo'])){
            $success = 0;
        }else{
            $stmt = $conn->prepare("UPDATE college_data SET collegeId = :collegeId, affiliation =:affiliation, name = :name, overview = :overview, message = :message, reason = :reason, program = :program, phone =:phone, email =:email, website =:website, address =:address, location =:location, logo = :logo WHERE collegeId = :collegeId");
            $stmt ->bindParam(":collegeId", $i_id);
            $stmt ->bindParam(":affiliation", $affiliation);
            $stmt ->bindParam(":name", $name);
            $stmt ->bindParam(":overview", $overview);
            $stmt ->bindParam(":message", $message);
            $stmt ->bindParam(":reason", $reason);
            $stmt ->bindParam(":program", $program);
            $stmt ->bindParam(":phone", $phone);
            $stmt ->bindParam(":email", $email);
            $stmt ->bindParam(":website", $website);
            $stmt ->bindParam(":address", $address);
            $stmt ->bindParam(":location", $location);
            $stmt ->bindParam(":logo", $logo);
            
            if($stmt ->execute()){
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
                <a href="managecollege.php?institutionId=<?php echo $_SESSION['institutionId'] ?>" class="nav-link active" aria-current="page">Manage College</a>
            </li>
            <li class="nav-item ps-1">
                <a href="managecourse.php" class="nav-link" aria-current="page">Manage Courses</a>
            </li>
        </ul>
    </div>

    <div class="top hidden">
        <a href="#top" class="top"><i class="fa-solid fa-arrow-right fa-rotate-270 fa-lg" style="color: black;"></i></a>
    </div>

    <!-- College Details Form -->

    <div class="background-color">
    <div class="container update-container">
    <p class="update-title">COLLEGE DETAILS</p>
    <form action="" method="POST">
        <div class="row mb-3">
        <?php
            $stmt = $conn->prepare("SELECT * FROM college_data WHERE collegeId = :instiutionId");
            $stmt -> bindParam(":instiutionId", $i_id);
            $stmt -> execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
            foreach ($result as $value){
        ?>
            <div class="col-2">
                <input type="text" class="form-control" name="collegeId" id="collegeId" placeholder="College ID" value="College ID: <?php echo $value['collegeId'] ?>" readonly>
            </div>
            <div class="col-2">
                <select class="form-select" name="affiliation" id="affiliation" style="font-size: 0.87rem;">
                    <option value="">Affiliation</option>
                    <option value="TU">TU</option>
                    <option value="KU">KU</option>
                    <option value="PU">PU</option>
                    <option value="International">International</option>
                </select>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="name" id="name" placeholder="College Name" value="<?php echo $value['name'] ?>" readonly>
            </div>
        </div>
    
        <div>
            <textarea class="form-control mb-2" name="overview" id="overview" rows="11" placeholder="About College"><?php echo $value['overview'] ?></textarea>
            <textarea class="form-control mb-2" name="reason" id="reason" rows="10" placeholder="Reason to Enroll"><?php echo $value['reason'] ?></textarea>
            <textarea class="form-control mb-2" name="message" id="message" rows="10" placeholder="Principal Message"><?php echo $value['message'] ?></textarea>
            <textarea class="form-control mb-3" name="program" id="program" rows="10" placeholder="Academic Programs"><?php echo $value['program'] ?></textarea>
        </div>
    
        <div class="row mb-2">
            <div class="col-3">
                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone Number" value="<?php echo $value['phone'] ?>">
            </div>
            <div class="col">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" value="<?php echo $value['email'] ?>">
            </div>
            <div class="col">
                <input type="text" class="form-control" name="website" id="website" placeholder="Website" value="<?php echo $value['website'] ?>">
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-3">
                <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="<?php echo $value['address'] ?>">
            </div>
            <div class="col">
                <input type="file" class="form-control" name="logo" id="logo" accept="logo/*">
            </div>
        </div>
    
        <textarea class="form-control mb-4" name="location" id="location" rows="3" placeholder="Location"><?php echo $value['location'] ?></textarea>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary" name="update-college-submit" id="update-submit" value="Update" style="background-color: #082465;">Update</button>
        </div>
        <?php } ?>
    </form>
    </div>
    </div>

    <!-- Success Message -->

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="userSuccessToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Update Successful</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">College data has been updated.</div>
    </div>
    </div>

    <!-- Error Message -->

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="userErrorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Update Error</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="errorToastBody">Fill up all the fields.</div>
    </div>
    </div>

    <script>
    <?php
        if(isset($success) && $success === 1){
            echo 'document.addEventListener("DOMContentLoaded", function(){
                var successToast = new bootstrap.Toast(document.getElementById("userSuccessToast"));
                successToast.show();
            });';
        }
    ?>
    </script>

    <script>
    <?php
        if(isset($success) && $success === 0){
            echo 'document.addEventListener("DOMContentLoaded", function(){
                var errorToast = new bootstrap.Toast(document.getElementById("userErrorToast"));
                errorToast.show();
            });';
        }
    ?>
    </script>
   
    <script src="../js/institutionscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
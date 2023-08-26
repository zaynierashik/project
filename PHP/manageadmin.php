<?php
    session_start();
    include 'connect.php';

    if(!isset($_SESSION['adminname'])){
        header('location: homepage.php');
    }

    if (isset($_POST['delete'])) {
        $adminId = $_POST['adminId'];
        $stmt = $conn->prepare("DELETE FROM admin_data WHERE adminId = :adminId");
        $stmt->bindParam(':adminId', $adminId);
        $stmt->execute();
    
        header('Location: manageadmin.php');
        exit();
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
    <link rel="stylesheet" href="../CSS/admin.css">
</head>
<body>

    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="adminpage.php">
                <img src="../Images/logo.png" alt="hamrocollege" width="200" height="50">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="usernavbar navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"><?php echo $_SESSION['adminname'] ?></a>
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
                <a href="adminpage.php" class="nav-link" aria-current="page">Dashboard</a>
            </li>
            <li class="nav-item ps-1">
                <a href="manageadmin.php" class="nav-link active" aria-current="page">Manage Admin</a>
            </li>
        </ul>
    </div>

    <div class="background-color" style="min-height: 73.5vh;">
        <div class="container admin-container">
            <p class="admin-title">ADMIN</p>
            <table class="table table-striped user-table">
                <tr class="table-dark">
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Name</td>
                    <td class="table-body">Email Address</td>
                    <td class="table-body">Phone Number</td>
                    <td class="table-body"></td>
                </tr>
                
                <?php
                    $stmt = $conn->prepare("SELECT * FROM admin_data");
                    $stmt ->execute();
                    $count = 1;
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                ?>
                <tr>
                    <td class="table-SN"><?php echo $count++ ?></td>
                    <td class="table-body"><?php echo $row['name'] ?></td>
                    <td class="table-body"><?php echo $row['email'] ?></td>
                    <td class="table-body"><?php echo $row['phone'] ?></td>
                    <td class="table-body">
                        <button type="button" class="btn delete" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-adminid="<?php echo $row['adminId']; ?>" style="color: black; font-size: 0.87rem; padding: 0;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <!-- Delete Confirmation -->

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                Are you sure you want to delete this account?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input type="hidden" name="adminId" id="adminIdToDelete" value="">
                    <button type="submit" class="btn btn-primary" name="delete" id="delete">Delete account</button>
                </form>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var deleteButtons = document.querySelectorAll('.delete');
            var adminIdInput = document.getElementById('adminIdToDelete');

            deleteButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var adminId = button.getAttribute('data-adminid');
                    adminIdInput.value = adminId;
                });
            });
        });
    </script>

    <script src="../JS/adminscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
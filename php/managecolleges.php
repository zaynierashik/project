<?php
    session_start();
    include 'connect.php';

    if(!isset($_SESSION['adminname'])){
        header('location: homepage.php');
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
    <link rel="stylesheet" href="../css/admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="adminpage.php">
                <img src="../images/logo.png" alt="hamrocollege" width="200" height="50">
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
                <a href="manageadmin.php" class="nav-link" aria-current="page">Manage Admin</a>
            </li>
            <li class="nav-item ps-1">
                <a href="managecolleges.php" class="nav-link active" aria-current="page">Manage Colleges</a>
            </li>
        </ul>
    </div>

    <div class="background-color" style="min-height: 73.5vh;">
        <div class="container admin-container">
            <p class="admin-title">COLLEGE</p>
            <table class="table table-striped college-table">
                <tr class="table-dark">
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">College Name</td>
                    <td class="table-body">Email Address</td>
                    <td class="table-body">Phone Number</td>
                    <td class="table-body">Update Status</td>
                    <td class="table-body">Status</td>
                </tr>
                
                <?php
                    $stmt = $conn->prepare("SELECT * FROM institution_data");
                    $stmt ->execute();
                    $count = 1;
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                ?>
                <tr>
                    <td class="table-SN"><?php echo $count++ ?></td>
                    <td class="table-body"><?php echo $row['name'] ?></td>
                    <td class="table-body"><?php echo $row['email'] ?></td>
                    <td class="table-body"><?php echo $row['phone'] ?></td>
                    <td>
                        <select class="form-select" name="status" onchange="updateStatus(this, <?= $row['institutionId']; ?>)">
                            <option value="" disabled selected>Update</option>
                            <option value="Approved">Approve</option>
                            <option value="Rejected">Reject</option>
                        </select>
                    </td>
                    <td id="status-<?= $row['institutionId']; ?>"><?= $row['status']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <script>
    function updateStatus(selectElement, institutionId){
        var status = selectElement.value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'updatestatus.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200){
                var statusCell = document.getElementById('status-' + institutionId);
                statusCell.textContent = status;
            }
        };

        var data = 'institutionId=' + encodeURIComponent(institutionId) + '&status=' + encodeURIComponent(status);
        xhr.send(data);
    }
    </script>

    <script src="../js/adminscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
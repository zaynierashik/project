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
                        <a class="nav-link" aria-current="page">admin@gmail.com</a>
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
                <a href="adminpage.php" class="nav-link active" aria-current="page">Dashboard</a>
            </li>
            <li class="nav-item ps-1">
                <a href="manageadmin.php" class="nav-link" aria-current="page">Manage Admin</a>
            </li>
        </ul>
    </div>

    <div class="background-color">
        <div class="container admin-container">
            <p class="admin-title">DASHBOARD</p>
            <div class="container text-center admin-card">
                <div class="row row-gap-4">
                    <div class="col card-link">
                        <div class="card" style="width: 14.7rem; height: auto; border-radius: 15px;">
                            <div class="card-body">
                                <i class="fa-solid fa-user-tie fa-lg mb-5 mt-5" style="color: #000000; font-size: 2.5rem;"></i>
                                <p class="total-count">Admin Count: 100</p>
                            </div>
                        </div>
                    </div>
                    <div class="col card-link" onclick="showcounttable('user-count-table')">
                        <div class="card" style="width: 14.7rem; height: auto; border-radius: 15px;">
                            <div class="card-body">
                                <i class="fa-solid fa-user fa-lg mb-5 mt-5" style="color: #000000; font-size: 2.5rem;"></i>
                                <p class="total-count" id="usercount">User Count: 100</p>
                            </div>
                        </div>
                    </div>
                    <div class="col card-link" onclick="showcounttable('college-count-table')">
                        <div class="card" style="width: 14.7rem; height: auto; border-radius: 15px;">
                            <div class="card-body">
                                <i class="fa-solid fa-building-columns fa-lg mb-5 mt-5" style="color: #000000; font-size: 2.5rem;"></i>
                                <p class="total-count" id="collegecount">College Count: 100</p>
                            </div>
                        </div>
                    </div>
                    <div class="col card-link" onclick="showcounttable('course-count-table')">
                        <div class="card" style="width: 14.7rem; height: auto; border-radius: 15px;">
                            <div class="card-body">
                                <i class="fa-sharp fa-solid fa-graduation-cap fa-lg mb-5 mt-5" style="color: #000000; font-size: 2.5rem;"></i>
                                <p class="total-count" id="coursecount">Course Count: 100</p>
                            </div>
                        </div>
                    </div>
                    <div class="col card-link" onclick="showcounttable('feedback-count-table')">
                        <div class="card" style="width: 14.7rem; height: auto; border-radius: 15px;">
                            <div class="card-body">
                                <i class="fa-solid fa-message fa-lg mb-5 mt-5" style="color: #000000; font-size: 2.5rem;"></i>
                                <p class="total-count" id="feedback">View Feedback</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User -->

        <div class="container admin-container" id="user-count-table" style="display: none;">
            <p class="admin-title">USER</p>
            <table class="table table-striped user-table">
                <tr class="table-dark">
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Name</td>
                    <td class="table-body">Email Address</td>
                    <td class="table-body">Phone Number</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">rashikchauhan@gmail.com</td>
                    <td class="table-body">9800000000</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">rashikchauhan@gmail.com</td>
                    <td class="table-body">9800000000</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">rashikchauhan@gmail.com</td>
                    <td class="table-body">9800000000</td>
                </tr>
            </table>
        </div>

        <!-- COLLEGE -->

        <div class="container admin-container" id="college-count-table" style="display: none;">
            <p class="admin-title">COLLEGE</p>
            <table class="table table-striped college-table">
                <tr class="table-dark">
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">College Name</td>
                    <td class="table-body">Email Address</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">rashikchauhan@gmail.com</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">rashikchauhan@gmail.com</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">rashikchauhan@gmail.com</td>
                </tr>
            </table>
        </div>

        <!-- COURSE -->

        <div class="container admin-container" id="course-count-table" style="display: none;">
            <p class="admin-title">COURSE</p>
            <table class="table table-striped course-table">
                <tr class="table-dark">
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Course Name</td>
                    <td class="table-body">Abbreviation</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">BCA</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">BCA</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">BCA</td>
                </tr>
            </table>
        </div>

        <!-- Feedback -->

        <div class="container admin-container" id="feedback-count-table">
            <p class="admin-title">FEEDBACK</p>
            <table class="table table-striped feedback-table">
                <tr class="table-dark">
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Name</td>
                    <td class="table-body">Email Address</td>
                    <td class="table-body">Feedback</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">rashikchauhan@gmail.com</td>
                    <td class="table-body">9800000000</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">rashikchauhan@gmail.com</td>
                    <td class="table-body">9800000000</td>
                </tr>
                <tr>
                    <td class="table-SN">S.N.</td>
                    <td class="table-body">Rashik Chauhan</td>
                    <td class="table-body">rashikchauhan@gmail.com</td>
                    <td class="table-body">9800000000</td>
                </tr>
            </table>
        </div>
    </div>

    <script src="../JS/adminscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
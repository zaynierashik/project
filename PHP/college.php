<?php
    session_start();
    include 'connect.php';

    if(!isset($_SESSION['username'])){
        header('location: homepage.php');
    }

    $courseId;
    $i = 0;
    $searchValue;
    @$count = $_GET['count'];
    @$status = $_GET['status'];

    do{
        @$courseId = $_GET[$i];
        @$collegeId = $_GET[$i];

        if($courseId != null){
            if($status == 1){
                $stmt = $conn->prepare("SELECT * FROM course_data WHERE courseId = :courseId");
                $stmt ->bindParam(':courseId', $courseId);
            }else{
                $stmt = $conn->prepare("SELECT * FROM college_data WHERE collegeId = :collegeId");
                $stmt ->bindParam(':collegeId', $collegeId);
            }

            $stmt ->execute();
            $value[$i] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $searchValue = true;
        }else{
            $searchValue = false;
        }
        $i++;
    }while($i<$count);
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
    <!-- Search Result -->

    <?php
    if($searchValue){?>
        <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="userpage.php">
                <img src="../Images/logo.png" alt="hamrocollege" width="200" height="50">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="usernavbar navbar-nav ms-auto">
                <li class="nav-item">
                    <form action="datasearch.php" method="POST" id="search" class="d-flex" role="search">
                        <input type="search" name="search" class="form-control d-flex rounded-pill search-bar" placeholder="Search colleges, courses . . ." aria-label="Search">
                    </form>
                </li>
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

        <div class="background-color" style="min-height: 82.95vh;">
        <div class="container search-title search-container">Found results:</div>
        <div class="container">
        <?php
            foreach($value as $item){
            if($status == 1){
                echo '<div class="container text-center course-card">
                    <div class="row row-gap-4">
                    <div class="col">
                        <a href="coursedetails.php?courseId=' . $item[0]['courseId'] . '" class="card-link">
                        <div class="card" style="width: 18.7rem; height: 11vh; border-radius: 15px;">
                        <div class="card-body">
                            <p class="course-name">'.$item[0]['title'].'</p>
                        </div>
                        </div>
                        </a>
                    </div>
                    </div>
                </div>';
            }
            else{
                echo '<div class="container text-center college-card">
                    <div class="row row-gap-4">
                    <div class="col">
                        <a href="collegedetails.php?collegeId=' .$item[0]['collegeId'] . '" class="card-link">
                        <div class="card" style="width: 18.75rem; border-radius: 15px;">
                            <img src="../Images/' . $item[0]['logo'] . '" class="card-img-top college-logo" alt="...">
                            <div class="card-body">
                                <p class="college-name">' . $item[0]['name'] . '</p>
                                <p class="address">' . $item[0]['address'] . '</p>
                            </div>
                        </div>
                        </a>
                    </div>
                    </div>
                </div>';
            }
            }
        ?>
        </div>
        </div>
        <?php 
    }else{ 
    ?>

    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="userpage.php">
                <img src="../Images/logo.png" alt="hamrocollege" width="200" height="50">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="usernavbar navbar-nav ms-auto">
                    <li class="nav-item">
                    <form action="datasearch.php" method="POST" id="search" class="d-flex" role="search">
                            <input type="search" name="search" class="form-control d-flex rounded-pill search-bar" placeholder="Search colleges, courses . . ." aria-label="Search">
                        </form>
                    </li>
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

    <div class="link-container navbar navbar-expand-lg" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="userpage.php" class="nav-link" aria-current="page">Home</a>
            </li>
            <li class="nav-item ps-1">
                <a href="college.php" class="nav-link active" aria-current="page">Colleges</a>
            </li>
            <li class="nav-item ps-1">
                <a href="course.php" class="nav-link" aria-current="page">Courses</a>
            </li>
            <li class="nav-item ps-1">
                <a href="admission.php" class="nav-link" aria-current="page">Admissions</a>
            </li>
        </ul>
    </div>

    <!-- Colleges -->

    <div class="background-color">
    <div class="container college-container">
        <p class="college-title">COLLEGES</p>
        <div class="text-center college-card">
        <div class="row row-gap-4">
            <?php
                $sql = "SELECT * FROM college_data";
                $stmt = $conn->query($sql);
                if($stmt->rowCount() > 0){
                    while($row = $stmt->fetch()){
                        echo '<div class="col">
                                <a href="collegedetails.php?collegeId=' .$row['collegeId'] . '" class="card-link">
                                <div class="card" style="width: 18.75rem; border-radius: 15px; min-height: 34vh">
                                    <img src="../Images/' . $row['logo'] . '" class="card-img-top college-logo" alt="...">
                                    <div class="card-body">
                                        <p class="college-name">' . $row['name'] . '</p>
                                        <p class="address">' . $row['address'] . '</p>
                                    </div>
                                </div>
                                </a>
                        </div>';
                    }
                }else{
                    echo "No colleges found.";
                }
            ?>
        </div>
        </div>
    </div>

    <!-- Universities -->

    <div class="container university-container">
        <p class="college-title">UNIVERSITIES</p>
        <div class="text-center college-card">
            <div class="row row-gap-4">
            <div class="col">
                <a href="category.php?affiliation=TU" class="card-link">
                    <div class="card" style="width: 18.75rem; border-radius: 15px;">
                        <img src="../Images/tu-logo.png" class="card-img-top university-logo" alt="...">
                        <div class="card-body">
                            <p class="college-name">Tribhuvan University</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="category.php?affiliation=KU" class="card-link">
                    <div class="card" style="width: 18.75rem; border-radius: 15px;">
                        <img src="../Images/ku-logo.png" class="card-img-top university-logo" alt="...">
                        <div class="card-body">
                            <p class="college-name">Kathmandu University</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="category.php?affiliation=PU" class="card-link">
                    <div class="card" style="width: 18.75rem; border-radius: 15px;">
                        <img src="../Images/pu-logo.png" class="card-img-top university-logo" alt="...">
                        <div class="card-body">
                            <p class="college-name">Pokhara University</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col">
                <a href="category.php?affiliation=International" class="card-link">
                    <div class="card" style="width: 18.75rem; border-radius: 15px;">
                        <img src="../Images/international-logo.jpg" class="card-img-top intl-university-logo" alt="...">
                        <div class="card-body">
                            <p class="college-name">International</p>
                        </div>
                    </div>
                </a>
            </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Chatbot -->

    <div class="bot-btn" onclick="toggleChatBot()">
        <i class="fa-solid fa-message fa-lg" style="color: #000000;"></i>
    </div>
    
    <div class="bot" style="display: none;">
    <div class="hamrobot">Hamro Bot</div>
        <div class="form">
            <p class="msg-header"><i class="fa-solid fa-robot" style="color: #121212; padding-right: 0.5vw;"></i>Hello there, how can I help you?</p>
        </div>
        <input type="text" class="form-control bot-input" id="bot-input" placeholder="Type something here.." required>
        <button class="bot-send" id="bot-send" style="display: none;"><i class="fa-solid fa-paper-plane"></i></button>
    </div>
    </div>

    <?php } ?>

    <script>
        function toggleChatBot(){
            var chatbotContainer = document.querySelector('.bot');
            var currentDisplay = chatbotContainer.style.display;
    
            if(currentDisplay === 'none'){
                chatbotContainer.style.display = 'block';
            }else{
                chatbotContainer.style.display = 'none';
            }
        }
    </script>

    <script>
        $(document).ready(function(){
            $("#bot-send").on("click", function(){
                $value = $("#bot-input").val();
                $msg = '<p class="user-text">'+ $value +'</p>';
                $(".form").append($msg);
                $("#bot-input").val('');
                
                $.ajax({
                    url: 'chat.php',
                    type: 'POST',
                    data: 'text='+$value,
                    success: function(result){
                        $replay = '<p class="msg-header"><i class="fa-solid fa-robot" style="color: #121212; padding-right: 0.5vw;"></i>'+ result +'</p>';
                        $(".form").append($replay);
                        $(".form").scrollTop($(".form")[0].scrollHeight);
                    }
                });
            });
        });
    </script>

    <script>
        var botInput = document.getElementById('bot-input');
        var botSend = document.getElementById('bot-send');

        botInput.addEventListener('input', function(){
            if(botInput.value.trim() !== ''){
                botSend.style.display = 'block';
            }else{
                botSend.style.display = 'none';
            }
        });
    </script>
    
    <script src="../JS/userscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
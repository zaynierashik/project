<?php
    session_start();
    include 'connect.php';

    if(!isset($_SESSION['username'])){
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
    <link rel="stylesheet" href="../css/user.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navbar -->

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
                        <form action="datasearch.php" method="POST" id="search" class="d-flex" role="search">
                            <input type="search" name="search" class="form-control d-flex rounded-pill search-bar" placeholder="Search colleges, courses . . ." aria-label="Search">
                        </form>
                    </li>
                    <li class="nav-item">
                        <a href="account.php?userId=<?php echo $_SESSION['userId']; ?>" class="nav-link" aria-current="page"><i class="fa-solid fa-user" style="color: #000000;"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"><?php echo $_SESSION['username']; ?></a>
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
                <a href="college.php" class="nav-link" aria-current="page">Colleges</a>
            </li>
            <li class="nav-item ps-1">
                <a href="course.php" class="nav-link active" aria-current="page">Courses</a>
            </li>
            <li class="nav-item ps-1">
                <a href="admission.php" class="nav-link" aria-current="page">Admissions</a>
            </li>
        </ul>
    </div>

    <!-- Courses -->

    <div class="background-color">
    <div class="container course-container">
        <p class="course-title">COURSES</p>
        <div class="text-center course-card">
        <div class="row row-gap-3">
            <?php
                $sql = "SELECT * FROM course_data";
                $stmt = $conn->query($sql);
                if($stmt->rowCount() > 0){
                    while($row = $stmt->fetch()){
                        echo '<div class="col">
                                <a href="coursedetails.php?courseId=' . $row['courseId'] . '" class="card-link">
                                <div class="card" style="width: 18.7rem; height: 11vh; border-radius: 15px;">
                                    <div class="card-body">
                                        <p class="course-name">'.$row['title'].'</p>
                                    </div>
                                </div>
                                </a>
                        </div>';
                    }
                }else{
                    echo "No courses found.";
                }
            ?>
        </div>
        </div>
    </div>

    <!-- Disciplines -->

    <div class="container discipline-container">
        <p class="course-title">DISCIPLINES</p>
        <p style="color: #082465;">Field of Studies</p>
        <div class="text-center course-card">
            <div class="row row-gap-3">
                <div class="col">
                    <a href="category.php?field=Computer and Information Technology" class="card-link">
                        <div class="card" style=" border-radius: 15px; padding-top: 5vh;">
                            <i class="fa-solid fa-microchip" style="color: #2b2b2b; font-size: 2.5rem;"></i>
                            <div class="card-body">
                                <p class="course-name">Computer and Information Technology</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="category.php?field=Engineering" class="card-link">
                        <div class="card" style="border-radius: 15px; padding-top: 5vh; min-height: 23.5vh;">
                            <i class="fa-solid fa-helmet-safety" style="color: #2b2b2b; font-size: 2.5rem;"></i>
                            <div class="card-body">
                                <p class="course-name">Engineering</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="category.php?field=Management" class="card-link">
                        <div class="card" style="border-radius: 15px; padding-top: 5vh; min-height: 23.5vh;">
                            <i class="fa-solid fa-people-group" style="color: #2b2b2b; font-size: 2.5rem;"></i>
                            <div class="card-body">
                                <p class="course-name">Management</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="category.php?field=Agriculture, Forestry and Animal Sciences" class="card-link">
                        <div class="card" style="border-radius: 15px; padding-top: 5vh; min-height: 23.5vh;">
                            <i class="fa-solid fa-tree" style="color: #2b2b2b; font-size: 2.5rem;"></i>
                            <div class="card-body">
                                <p class="course-name">Agriculture, Forestry and Animal Sciences</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="category.php?field=Science and Technology" class="card-link">
                        <div class="card" style="border-radius: 15px; padding-top: 5vh; min-height: 23.5vh;">
                            <i class="fa-solid fa-microscope" style="color: #2b2b2b; font-size: 2.5rem;"></i>
                            <div class="card-body">
                                <p class="course-name">Science and Technology</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="category.php?field=Humanities and Social Sciences" class="card-link">
                        <div class="card" style="border-radius: 15px; padding-top: 5vh; min-height: 23.5vh;">
                            <i class="fa-solid fa-people-roof" style="color: #2b2b2b; font-size: 2.5rem;"></i>
                            <div class="card-body">
                                <p class="course-name">Humanities and Social Sciences</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="category.php?field=Health Professional Education" class="card-link">
                        <div class="card" style="border-radius: 15px; padding-top: 5vh; min-height: 23.5vh;">
                            <i class="fa-solid fa-suitcase-medical" style="color: #2b2b2b; font-size: 2.5rem;"></i>
                            <div class="card-body">
                                <p class="course-name">Health Professional Education</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="category.php?field=Law" class="card-link">
                        <div class="card" style="border-radius: 15px; padding-top: 5vh; min-height: 23.5vh;">
                            <i class="fa-solid fa-scale-balanced" style="color: #2b2b2b; font-size: 2.5rem;"></i>
                            <div class="card-body">
                                <p class="course-name">Law</p>
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
            <p class="msg-header"><i class="fa-solid fa-robot" style="color: #121212; padding-right: 0.5vw;"></i>Hello there, how can I help you? Maybe searching these keywords might help: colleges, courses.</p>
        </div>
        <input type="text" class="form-control bot-input" id="bot-input" placeholder="Type something here.." required>
        <button class="bot-send" id="bot-send" style="display: none;"><i class="fa-solid fa-paper-plane"></i></button>
    </div>
    </div>

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

    <script src="../js/userscript.js"></script>
    <script src="https://kit.fontawesome.com/296ff2fa8f.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
</html>
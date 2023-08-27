<?php
    session_start();
    include 'connect.php';

    if(isset($_POST['search'])){
        $title = strtolower($_POST['search']);
        $abbreviation = strtolower($_POST['search']);
        $name = strtolower($_POST['search']);
    }
    
    if($title != null || $abbreviation != null){
        $stmt = $conn->prepare("SELECT * FROM course_data WHERE lower(title) LIKE :title || lower(abbreviation) LIKE :abbreviation");
        $stmt ->bindParam(':title', $titleLike);
        $stmt ->bindParam(':abbreviation', $abbreviationLike);
        $titleLike = '%' .$title. '%';
        $abbreviationLike = '%' .$abbreviation. '%';
        $stmt ->execute();
        $value = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $status = 1; 

        $get_id = array(); 
        foreach($value as $item){
            $get_id[] = $item['courseId'];
            $count++;
        }
    }

    if($name != null){
        if(empty($get_id)){
            $stmt = $conn->prepare("SELECT * FROM college_data WHERE lower(name) LIKE :name");
            $stmt ->bindParam(':name', $nameLike);
            $nameLike = '%' .$name. '%';
            $stmt ->execute();
            $value = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            $status = 0; 

            foreach($value as $item){
                $get_id[] = $item['collegeId'];
                $count++;
            }
        }
    }

    if(empty($get_id)){?>
        <div style="margin-top: 10vh; font-size: 1.7rem; margin-left: 6.5vw; font-family: Poppins, sans-serif;">No results found.</div>
        <div style="margin-top: 1.5vh; font-size: 1.1rem; margin-left: 6.5vw; font-family: Poppins, sans-serif;">Redirecting to the homepage . . .</div>
    <?php 
    }else{
        $queryString = http_build_query($get_id);
        header("location: userpage.php?count=$count&status=$status&$queryString");
    }    
    ?>
    <script>
        setTimeout(function(){
            window.location.href = "userpage.php";
        }, 2000);
    </script>
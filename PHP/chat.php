<?php
    include 'connect.php';

    $getMesg = $_POST['text'];
    $check_data = "SELECT replies FROM chatbot WHERE queries LIKE :getMesg";
    $stmt = $conn->prepare($check_data);
    $stmt->bindValue(':getMesg', '%' . $getMesg . '%');
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $fetch_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $reply = $fetch_data['replies'];

        if (strpos(strtolower($getMesg), 'colleges') !== false) {
            $collegeNames = "";
            $collegeQuery = "SELECT name FROM college_data LIMIT 7";
            $collegeStmt = $conn->query($collegeQuery);
        
            while ($collegeRow = $collegeStmt->fetch(PDO::FETCH_ASSOC)) {
                $collegeNames .= $collegeRow['name'] . ", ";
            }
            $reply .= "\n\nHere are some colleges: " . rtrim($collegeNames, ', ');
        }

        if (strpos(strtolower($getMesg), 'courses') !== false) {
            $courseTitles = "";
            $courseQuery = "SELECT title FROM course_data LIMIT 7";
            $courseStmt = $conn->query($courseQuery);
        
            while ($courseRow = $courseStmt->fetch(PDO::FETCH_ASSOC)) {
                $courseTitles .= $courseRow['title'] . ", ";
            }
            $reply .= "\n\nHere are some courses: " . rtrim($courseTitles, ', ');
        }

        echo $reply;
    }else{
        echo "Sorry, I can't understand you! Maybe try these keywords: colleges, courses.";
    }
?>
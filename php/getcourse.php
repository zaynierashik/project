<?php
    include 'connect.php';

    if(isset($_GET['collegeId'])){
        $collegeId = $_GET['collegeId'];
        $stmt = $conn->prepare("SELECT c.courseId, c.title FROM course_data c INNER JOIN college_course cc ON c.courseId = cc.courseId WHERE cc.collegeId = :collegeId");
        $stmt->bindParam(":collegeId", $collegeId);
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($courses);
    }else{
        http_response_code(400);
        echo json_encode(["error" => "CollegeId not provided"]);
    }
?>
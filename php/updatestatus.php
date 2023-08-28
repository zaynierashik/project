<?php
    include 'connect.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $institutionId = $_POST['institutionId'];
        $status = $_POST['status'];

        $updateQuery = $conn->prepare("UPDATE institution_data SET status = :newStatus WHERE institutionId = :institutionId");
        $updateQuery ->bindParam(':newStatus', $status);
        $updateQuery ->bindParam(':institutionId', $institutionId);
        $updateQuery ->execute();
    }
?>
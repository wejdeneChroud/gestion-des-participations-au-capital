<?php
session_start();

function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
}

function establishConnection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "startupinvest";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function getCapitalRisqueId($conn, $username) {
    $sql = "SELECT id_capital_risque FROM capital_risque WHERE pseudo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id_capital_risque'];
    } else {
        return null; // Capital risque not found
    }
}

function deleteProject($conn, $projectId)
{
    $deleteSql = "DELETE FROM projet WHERE id_projet = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $projectId);
    $deleteStmt->execute();
}

?>
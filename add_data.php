<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "markir";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  if(isset($_POST)){
    $top = $_POST['topInp'];
    $left = $_POST['leftInp'];
    $width = $_POST['widthInp'];
    $height = $_POST['heightInp'];
    $column = $_POST['columnInp'];
    $row = $_POST['rowInp'];

    $sql = "INSERT INTO parking_slot (`top`, `left`, `width`, `height`, `column`, `row`) values($top, $left, $width, $height, $column, $row)";
    $conn->query($sql);
  }
  $conn->close();
header("Location:index.php");
?>

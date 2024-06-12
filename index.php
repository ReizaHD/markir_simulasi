<!DOCTYPE html>
<html lang="en">
<head>
  <title>Webcam Cropper</title>
  <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest"> </script>
  <script src="js/webcam.js"></script>
  <script src="js/cropper.js"></script>
  <script>
    let json;
  </script>
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
  echo '<script>
    console.log(\'Connection Successfull\')
    </script>';

  $sql = "SELECT * FROM parking_slot";
  $result = $conn->query($sql);


  echo '<script>
    const slotData = {};
    </script>';

  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      echo '<script>
        slotData['.$row['id'].']='.json_encode($row).';
      </script>';
    }
  }

  ?>

  <link
    href="https://fonts.googleapis.com/css2?family=Rubik&display=swap"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="css/cropper.css"
  />
  <style>
    * {
      padding: 0;
      margin: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }
    body {
      background-color: #2a2a2a;
    }
    .preview-container {
      height: 100%;
      margin: 0 auto;
      display: flex;
      justify-content: center; /* centers items horizontally */
      align-items: center; /* centers items vertically */
      border-radius: 0.3em;
    }

    .options {
      display: flex;
      justify-content: center;
      gap: 1em;
      padding: 3em;
    }

    .video-container {
      height: 100%;
      margin: 0 auto;
      display: flex;
      justify-content: center; /* centers items horizontally */
      align-items: center; /* centers items vertically */
    }
    video{
      border-radius: 0.3em;
    }
    img {
      display: block;
      /* Important for cropper js*/
      max-width: 100%;
    }
    button {
      padding: 1em;
      border-radius: 0.3em;
      border: 2px solid #025bee;
      background-color: #ffffff;
      color: #025bee;
    }
    .container {
      display: grid;
      gap: 2em;
      border-radius: 7px;
    }
    .wrapper {
      width: min(90%, 2000px);
      position: absolute;
      transform: translateX(-50%);
      top: 1em;
      left: 50%;
      background-color: #ffffff;
      padding: 2em 3em;
      border-radius: 0.5em;
    }
    button {
      padding: 1em;
      border-radius: 0.3em;
      border: 2px solid #025bee;
      background-color: #ffffff;
      color: #025bee;
    }
    input[type="number"] {
      width: 100px;
      padding: 16px 5px;
      border-radius: 0.3em;
      border: 2px solid #000000;
    }
  </style>
</head>
<body>

<div class="wrapper">
  <div class="container">
    <div class="video-container">
      <video autoplay playsinline muted id="wc" width="400" height="400"></video>
    </div>
    <div class="preview-container">
      <img id="preview-image" />
    </div>
  </div>
  <div class="options hide" id="buttons">
    <form hidden id="hidden_form" target="_self" action="add_data.php" method="post">
      <input type="text" id="leftInp" name="leftInp">
      <input type="text" id="topInp" name="topInp">
      <input type="text" id="widthInp" name="widthInp">
      <input type="text" id="heightInp" name="heightInp">
      <input type="text" id="columnInp" name="columnInp">
      <input type="text" id="rowInp" name="rowInp">
    </form>
    <select id="device-list"></select>
    <button type="button" onclick="captureNow()" >Capture</button>
    <button onclick="cropReset()">Reset</button>
    <button onclick="cropCapture()">Get Data</button>
    <button onclick="setCrop()">Set</button>
    <input
      id="column_input"
      type="number"
      placeholder="Enter Column"
    />
    <input
      id="row_input"
      type="number"
      placeholder="Enter Row"
    />
    <button id="send_data" name="sent">Send Data</button>
  </div>
</div>
<div hidden id="hidden_layer">
  <img id="preview-image-crop" />
</div>
</body>
<script src="js/app.js"></script>
<?php
  echo '<script>
      async function predictAll(){
        for (let i = 0; i < slotData.length; i++) {
          cropper.setCropBoxData(slotData[i]);
          let img = cropper.getCroppedCanvas({});
          let classId = await predict(img);
        }
      }
      </script>';
?>
<?php
$conn->close();
?>
</html>

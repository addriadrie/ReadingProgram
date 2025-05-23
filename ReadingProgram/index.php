<?php


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="images/icon.png" type="image/x-icon">
  <title>Reading Program</title>

  <!-- css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />

  <!-- font -->
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

  <!-- stylesheet -->
  <style>
    body {
      background-image: url('images/bg.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      font-family: 'Poppins';
    }

    .card-img {
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      border-radius: 10px;
    }

    .card-img:hover {
      filter: brightness(80%);
      transform: scale(1.05);
    }

    .card-body {
      height: 150px;
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      padding: 10px;
      border: 0.5em solid white;
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .card-body:hover {
      color: #ffbd59;
      border: 0.5em solid #ffbd59;
      border-radius: 10px;
      box-shadow: 0 0 15px #ffbd59, 0 0 25px #fff199;
      text-shadow: 0 0 5px #ffbd59, 0 0 10px #fff199;
    }

    .card-title {
      font-size: 1.5em;
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); /* text shadow */
      text-align: center; /* horizontal */
      margin: 1.5em auto; /* top-bottom right-left */
      display: flex;
      justify-content: center; /* horizontal */
      align-items: center; /* vertical */
    }
  </style>

</head>

<body>

  <div class="container" style="background-color: rgba(255, 255, 255, 0.5); border-radius: 10px; padding: 0 1em 3em 1em; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <img src="images/select-story.png" style="width: 40em; display: block; margin: 1em auto 0 auto;" alt="SELECT STORY:">

    <!-- cards -->
    <div class="row row-cols-1 row-cols-md-4 g-4">
      <!-- story 1 -->
      <div class="col">
        <a href="easy.php?story=1" style="text-decoration: none; color: inherit;">
          <div class="card-img" style="background-image: url('images/1-graduation.png');" aria-label="The Graduation Trip">
            <div class="card-body">
              <h5 class="card-title">The Graduation Trip</h5>
            </div>
          </div>
        </a>
      </div>

      <!-- story 2 -->
      <div class="col">
        <a href="easy.php?story=1" style="text-decoration: none; color: inherit;">
          <div class="card-img" style="background-image: url('images/2-rome.png');" aria-label="Exploring Rome">
            <div class="card-body">
              <h5 class="card-title">Exploring Rome</h5>
            </div>
          </div>
        </a>
      </div>

      <!-- story 3 -->
      <div class="col">
        <div class="card-img" style="background-image: url('images/3-russia.png')" aria-label="A Stop in Russia">
          <div class="card-body">
            <h5 class="card-title">A Stop in Russia</h5>
          </div>
        </div>
      </div>

      <!-- story 4 -->
      <div class="col">
        <div class="card-img" style="background-image: url('images/4-newyork.png')" aria-label="On to New York">
          <div class="card-body">
            <h5 class="card-title">On to New York</h5>
          </div>
        </div>
      </div>

      <!-- story 5 -->
      <div class="col">
        <div class="card-img" style="background-image: url('images/5-africa.png')" aria-label="African Safari">
          <div class="card-body">
            <h5 class="card-title">African Safari</h5>
          </div>
        </div>
      </div>

      <!-- story 6 -->
      <div class="col">
        <div class="card-img" style="background-image: url('images/6-china.png')" aria-label="Visit to China">
          <div class="card-body">
            <h5 class="card-title">Visit to China</h5>
          </div>
        </div>
      </div>

      <!-- story 7 -->
      <div class="col">
        <div class="card-img" style="background-image: url('images/7-japan.png')" aria-label="Trip to Japan">
          <div class="card-body">
            <h5 class="card-title">Trip to Japan</h5>
          </div>
        </div>
      </div>

      <!-- story 8 -->
      <div class="col">
        <div class="card-img" style="background-image: url('images/8-final.png')" aria-label="The Final Stop">
          <div class="card-body">
            <h5 class="card-title">The Final Stop</h5>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"></script>
</body>

</html>
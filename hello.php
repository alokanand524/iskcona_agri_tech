<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Background Blur Fix</title>
  <style>

    .bg-glass-wrapper {
      position: relative;
      overflow: hidden;
      min-height: 300px; /* ✅ ensure it has height */
    }

    .bg-blur-image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      /* background-color: blue; */
      background-image: url('src/insecticide1.png');
      background-size: cover;
      background-position: center;
      filter: blur(2px);
      opacity: 0.6;
      z-index: 0;
      border-bottom-left-radius: 60%;
      border-bottom-right-radius: 100%;
    }

    .insect {
      position: relative;
      padding: 3rem 3rem 12rem 3rem;
      border-bottom-left-radius: 60%;
      border-bottom-right-radius: 100%;
      width: 100%;
      text-align: center;
      z-index: 1; /* must be above background */
    }

    .section-title {
      font-size: 2rem;
      color: #333;
    }

    .text-muted {
      color: #666;
    }
  </style>
</head>
<body>

  <div class="bg-glass-wrapper">
    <div class="bg-blur-image"></div>
    <div class="insect">
      <h2 class="section-title">Insecticide for Plants</h2>
      <p class="text-muted">Explore some of the powerful insecticide which heal the plants.</p>
    </div>
  </div>

</body>
</html>

<!doctype html>
<html>

<head>

  <title>Tania Rascia</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,800' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
  <style>
    body {
      background: linear-gradient(253deg, #0cc898, #1797d2, #864fe1);
      background-size: 300% 300%;
      -webkit-animation: Background 25s ease infinite;
      -moz-animation: Background 25s ease infinite;
      animation: Background 25s ease infinite;
    }
    
    @-webkit-keyframes Background {
      0% {
        background-position: 0% 50%
      }
      50% {
        background-position: 100% 50%
      }
      100% {
        background-position: 0% 50%
      }
    }
    
    @-moz-keyframes Background {
      0% {
        background-position: 0% 50%
      }
      50% {
        background-position: 100% 50%
      }
      100% {
        background-position: 0% 50%
      }
    }
    
    @keyframes Background {
      0% {
        background-position: 0% 50%
      }
      50% {
        background-position: 100% 50%
      }
      100% {
        background-position: 0% 50%
      }
    }
    
    .full-screen {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background: url(/wp-content/themes/oblate/images/triangles.png);
      background-size: cover;
      background-position: center;
      width: 100%;
      height: 100%;
      display: -webkit-flex;
      display: flex;
      -webkit-flex-direction: column/* works with row or column */
      flex-direction: column;
      -webkit-align-items: center;
      align-items: center;
      -webkit-justify-content: center;
      justify-content: center;
      text-align: center;
    }
    
    .inner {}
    
    h1 {
      color: #fff;
      font-family: 'Open Sans', sans-serif;
      font-weight: 800;
      font-size: 4em;
      letter-spacing: -2px;
      text-align: center;
      text-shadow: 1px 2px 1px rgba(0, 0, 0, .6);
    }
    
    h1:after {
      display: block;
      color: #fff;
      letter-spacing: 1px;
      font-family: 'Poiret One', sans-serif;
      content: 'Front End Web Developer';
      font-size: .4em;
      text-align: center;
    }
    
    .button-line {
      font-family: 'Open Sans', sans-serif;
      text-transform: uppercase;
      letter-spacing: 2px;
      background: transparent;
      border: 1px solid #fff;
      color: #fff;
      text-align: center;
      font-size: 1.4em;
      padding: 20px 40px;
      text-decoration: none;
      transition: all .6s ease;
    }
    
    .button-line:hover {
      background: #fff;
      color: #0cc898;
    }

  </style>
</head>

<body>
  <div class="full-screen">
    <div class="inner">
      <h1>Tania Rascia</h1>
      <br>
      <a class="button-line" href="/blog">Blog</a>
    </div>
  </div>
</body>

</html>

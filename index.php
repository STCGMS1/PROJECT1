<?php
session_start();

    include("connection.php");
    include("functions.php");

    $user_data = check_login($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Landing Page</title>
    
</head>
<body>
<header>
        <div class="logo">GMS1</div>
        <nav>
            <ul>
                <li><a href="create.php">ADD ENTRY</a></li>
                <li><a href="webmap/index.html">VIEW THE GRAVEYARD LAYOUT</a></li>
                <li><a href="https://public.tableau.com/views/RBCemeteryDashboard/RBCemetery2024Dashboard?:language=en-US&:sid=&:redirect=auth&:display_count=n&:origin=viz_share_link">VIEW STATISTICS</a></li>
                <li><a href="index4.php">LOCATE GRAVE</a></li>
                <li><a href="logout.php"><button>Log Out</button></a></li>
            </ul>
        </nav>
    </header>
    
    <style>
         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .logo {
            font-weight: bold;
            font-size: 24px;
            color: orange;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 30px;
        }
        
        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        nav ul li a:hover {
            color: #007bff;
        }
       
        .contact-btn {
            background-color: #007bff;
            color: white;
            padding: 8px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
        
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 70vh;
            text-align: center;
            padding: 0 20px;
            position: relative;
        }
        
        .hide-text {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        button{
        background-color: orange; margin-left: 10px; border-radius: 10px; padding: 10px; width: 120px; cursor: pointer;
      }
      button a{
        color: white; font-weight: bold; font-size 25px;
      }
      button:hover{
        background: white;
        color: orange;
      }
      h1:hover{
        color: green
      }
      footer {
        position: relative;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #333;
        color: #fff;
        padding: 10px 0;
        text-align: center;
        font-size: 25px;
      }
      #slider{
        overflow: hidden;
      }
      #slider figure{
        position: relative;
        width: 500%;
        margin: 0;
        left: 0;
        animation: 20s slider infinite;
      }
      #slider figure img{
        float: left;
        width: 20%;
      }

      @keyframes slider{
        0% {left: 0;}
        20% {left: 0;}
        25% {left: -100%;}
        45% {left: -100%;}
        50% {left: -200%;}
        70% {left: -200%;}
        75% {left: -300%;}
        95% {left: -300%;}
        100% {left: -400%;}
      }
      </style>
  
    <h3>Hello, <?php echo $user_data ['user_name']; ?>
    <i>    you are in!</i></h3>
    <div id="slider">
      <figure>
          <img src="billing.jpeg">
          <img src="RB.png">
          <img src="stc logo.jpg">
          <img src="revenue hall.jpeg">
          <img src="graves.jpeg">
          <img src="townhouse.jpg">
    </figure>
    </div>
</body>
</html>
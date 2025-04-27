<?php
    session_start();

    include("connection.php");
    include("functions.php");


    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        //something was posted
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];

        if(!empty($user_name) && !empty($password) && !is_numeric($user_name));
        {
            //read from database
            $query = "select * from users where user_name = '$user_name' limit 1";

            $result = mysqli_query($con, $query);

            if($result)
            {
                if($result && mysqli_num_rows($result) > 0)
                {
        
                    $user_data = mysqli_fetch_assoc($result);
                    
                    if($user_data['password'] === $password)
                    {
                        $_SESSION['user_id'] = $user_data['user_id'];
                        header("Location: index.php");
                        die;
                    }
                }
            }
            
            echo "Wrong username or password!";
        }
    }
?> 




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <style>
      body {
        background-image: url("image2.jpg")
        }
      h1 {
        color: white;
      }
      h2 {
        color: orange;
        font-size: 45px;
        text-align: center;
      }
      #text{
        height: 25px;
        border-radius: 5px;
        padding: 4px;
        border: solid thin #aaa;
        width: 100%;
      }
      #button{
        padding: 10px;
        width: 100px;
        color: white;
        background-color: lightblue;
        border: none;
        cursor: pointer;
      }
      #button:hover{
        background: orange;
        color: white;
      }
      #box{
        background-color: grey;
        margin: auto;
        width: 300px;
        padding: 20px;
      }
      .placeholder{
        position: absolute;
        top: 10px;
        left: 8px;
        font-size: 14px;
        padding: 0px 5px;
        color: orange;
        transition: 0.3s;
        pointer-events: none;
      }
      .input:focus + .placeholder{
        top: -10px;
        color: orange;
        background-color: orange;
      }
      .company-name span {
            display: block;
        }
        
        .landing-page-text {
            font-size: 36px;
            color: #007bff;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 3px;
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
        
        .welcome-text {
            font-size: 24px;
            margin-bottom: 10px;
            color: #444;
        }
        
        .company-name {
            font-size: 72px;
            font-weight: bold;
            margin-bottom: 20px;
            line-height: 1;
        }

    </style>
     <section class="hero">
        <div class="welcome-text">WELCOME TO OUR</div>
        <div class="company-name">
            <span>GRAVEYARD MANAGEMENT</span>
            <span>SYSTEM</span>
        </div>
        <div class="landing-page-text">LOG IN PAGE</div>
    </section>
    <div id="box">
        <form method="post">
            <div style="font-size: 20px;margin: 10px;">Login</div>

            <input id="text" type="text" name="user_name" placeholder="Username" required><br><br>
            <input id="text" type="password" name="password" placeholder="Password" required><br><br>

            <input id="button" type="submit" value="Login"><br><br>

            
        </form>
    </div><br>
</body>
</html>
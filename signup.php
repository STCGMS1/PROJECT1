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
            //save to database
            $user_id = random_num(20);
            $query = "insert into users (user_id,user_name,password) values ('$user_id','$user_name','$password')";
            mysqli_query($con, $query);

            header("Location: login.php");
            die;
        }
        {
          echo "Please enter some valid information!";
        }
    }
?> 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
</head>
<body>
    <style>
         body {
        background-color: green;
        width: 50%;
        margin-left: 340px;
        margin-right: 340px;
        font-size: 25px;
      }
      img {
        border-radius: 50px;
      }
      h1 {
        color: white;
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
      }
      #box{
        background-color: grey;
        margin: auto;
        width: 300px;
        padding: 20px;
      }

    </style>
    <div id="box">
        <form method="post">
            <div style="font-size: 20px;margin: 10px;">Signup</div>

            <input id="text" type="text" name="user_name"><br><br>
            <input id="text" type="password" name="password"><br><br>

            <input id="button" type="submit" value="Signup"><br><br>

            <a href="login.php">Click to Login</a>
        </form>
    </div>
</body>
</html>
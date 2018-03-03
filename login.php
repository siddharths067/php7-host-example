<?php
try{
    $connection_string = "mysql:host=%s;port=%s;dbname=%s";
    $dbase = new PDO(sprintf($connection_string,"localhost","3306","siteusers"),"root","siddharth");
    $dbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(isset($_POST["login_submit"])){
            $ans = $dbase->query(sprintf("select * from login where username=\"%s\";",$_POST["username"]));
            //echo var_dump($ans);
            if($ans->rowCount()==0){
                echo "User not found";

            }
            else{
                $true_password = $ans->fetchColumn(1);
                if($true_password == hash("sha512",$_POST["password"])){
                        session_start();
                        $_SESSION["username"]  = $_POST["username"];
                        $_SESSION["password"] = hash("sha512",$_POST["password"]);
                }
                else{
                    echo "Wrong Password Buddy";
                }

            }

    }
    else if(isset($_POST["signup_submit"])){
        $ans = $dbase->query(sprintf("select * from login where username=\"%s\";",$_POST["username"]));
            
            if($ans->rowCount()==0){
                $res = $dbase->query(sprintf("insert into login values(\"%s\",\"%s\");",$_POST["username"],hash("sha512",$_POST["password"])));
                
                if($res->rowCount()==1){
                    $_SESSION["username"] = $_POST["username"];
                    $_SESSION["password"] =hash("sha512",$_POST["password"]);
                    $dbase->query("create database ".$_SESSION['username'].";");
                    $dbase->query(sprintf("grant all privileges on %s.* to '%s'@localhost identified by \"%s\";",$_SESSION["username"],$_POST["username"],hash("sha512",$_POST["password"])));
                    session_destroy();
                    header("Location:regsuc.php");
                }
            }
            
                else{
                    echo "Please choose another username/password";
                    

                }

        
    }
}
catch (PDOException $e){
    if($e->getCode() == "HY000"){

        
    }
     else echo $e->getMessage();
}
?>

<html>
    <head>
        <title> Login Page </title>
    </head>
    <body>
    <?php 
        if(!isset($_SESSION["username"])){
        echo '
            <div name="login_div">
            <h1>Login Here</h1>
            <form action="login.php" method="post">
                <legend>Username</legend><br>
                <input type="text" name="username">
                <legend>Password</legend><br>
                <input type="password" name="password"> <br>
                <span>
                    <input type="submit" name="login_submit" value="Login">
                    <input type="submit" name="signup_submit" value="Signup">
                </span>
                <br>
                Successful Signup redirects you to login page.
            </form>
            
        </div>
            ';
        }
        else{
            session_start();
            echo "Hello ".$_SESSION["username"]."<br>";
            echo '
                <a href="mysqlview.php"> My SQL Console</a>
            ';
        }
        ?>
    </body>
</html>
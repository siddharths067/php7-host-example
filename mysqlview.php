<?php

session_start(); 
    if(isset($_POST["new_sql_session"])||(!isset($_SESSION["username"]))){
        session_destroy();
        header("Location:login.php");
        $_POST = NULL;
    }
    echo "Welcome ".$_SESSION["username"];
?>
<html>
    <head>
        <title>The SQL Console</title>

        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
    <div class="logout_block" onclick="window.location.href = 'logout.php'">
         Logout
       </div><br>
        <form form="sql_console" method="post" action="mysqlview.php">
        <fieldset>
        <!--
            <legend>Username</legend>
            <input type="text" name="username" value="<?php echo $_POST['username']; ?>" > <br>
            <legend>Password</legend>
            <input type="password" name="pass" value="<?php echo $_POST['pass'] ;?>"><br>
            <legend>Enter Database Name</legend>
            <input type="text" name="database" value="<?php echo $_POST['database']; ?>"><br>
            -->
            <legend>Query</legend>
            <input type="text" name="query_statement" value="" style="width: 300px;">
            <input type="submit" name="query_submit" value="Submit SQL Query">
            <input type="submit" name="new_sql_session" value="Start a new session">
        </fieldset>
        </form>
        <div name="result_space_mysql">
            <?php
            if(isset($_SESSION["username"])&&isset($_POST["query_statement"]))
                try{

                    //echo $_POST["username"]." ".$_POST["pass"]." ".$_POST["query_statement"];
                    $connection_string = "mysql:host=%s;port=3306;dbname=%s";
                    echo sprintf($connection_string,"localhost",$_SESSION["username"],$_SESSION["username"])."<br>";
                    $dbase = new PDO(sprintf($connection_string,"localhost",$_SESSION["username"],$_SESSION["username"]),$_SESSION["username"],$_SESSION["password"]);  
                    $dbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    if($dbase)  echo "Connection securely established<br>";
                    foreach($dbase->query($_POST["query_statement"]) as $row){
                        //echo $row.toString();
                        $cnt = 1;
                        foreach($row as $key => $value)
                            if($cnt++%2)
                                $_SESSION['session_queries'] .= sprintf(" | %s : %s  ",$key,$row[$key]);
                            
                        $_SESSION['session_queries'] .= "<br>";
                    }
                    echo $_SESSION['session_queries'];
                }
                catch(PDOException $e){
                    if($e->getCode() == "HY000"){
                        $_SESSION["session_queries"] .= "Insertion/Deletion Done <br>";
                        echo $_SESSION["session_queries"];
                        
                    }
                    else{
                    echo "Error Occured<br>";
                    echo $e->getMessage()."<br>";
                    }
                }
            ?>
        </div>
    </body>
</html>
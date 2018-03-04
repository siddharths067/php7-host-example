<?php
    session_start();
    if(!isset($_SESSION["username"])){
        session_destroy();
        header("Location:login.php");
    }
    function delTree($dir) { 
        $files = array_diff(scandir($dir), array('.','..')); 
         foreach ($files as $file) { 
           (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
         } 
         return rmdir($dir); 
       } 

    if(isset($_POST["website_submit"])){
        if(isset($_FILES["website"])){
            error_reporting(-1);  ini_set('display_errors', 'On');
            $tempfilepath = $_FILES["website"]["tmp_name"];
            $filepathformat = $_SERVER["DOCUMENT_ROOT"]."/users/%s/%s";
            $filepath = sprintf($filepathformat,$_SESSION["username"],$_FILES["website"]["name"]);
            if(is_dir($_SERVER["DOCUMENT_ROOT"]."/users/".$_SESSION["username"])){
                delTree($_SERVER["DOCUMENT_ROOT"]."/users/".$_SESSION["username"]);
                echo "directory deleted <br>";
            }
            echo $tempfilepath."<br>".$filepath."<br>".$_SERVER["DOCUMENT_ROOT"]."<br>";
            if(mkdir($_SERVER["DOCUMENT_ROOT"]."/users/".$_SESSION["username"],0777,true)){
                if(move_uploaded_file($tempfilepath, $filepath)){
                    /*zip = new ZipArchive;
                    $res = $zip->open($filepath);
                    if($res==true){
                        echo sprintf($_SERVER["DOCUMENT_ROOT"]."/users/%s/",$_SESSION["username"])."<br>";
                        if($zip->extractTo(sprintf($_SERVER["DOCUMENT_ROOT"]."/users/%s/",$_SESSION["username"]))){
                            
                            echo "success";
                        }
                        $zip->close();
                        //cunlink($tempfilepath);
                        
                    }
                    else{
                        echo "opening zip failed";
                    }*/
                    system("unzip -d ".($_SERVER["DOCUMENT_ROOT"]."/users/".$_SESSION["username"])." ".$filepath);
                }
                else{
                    echo "Moving File Failed";
                }

            }
            else{
                echo "Generating Directory Failed";
            }
        }
    }
?>
<html>
    <head>
        <title> Website Upload </title>
    </head>
    <body>
        <h1>The Rules are Simple<h1> <Br>
        <ol>
            <li>Make Sure you upload your Website Folder in a zip
            <li>rename the homepage index.php, you can redirect from there if you want
            <li>Your site url will be live at site.com/users/&lt;your username&gt;/index.php
        </ol>
       <br>
       <form name="upload_form" method="post" action="uploadsite.php" enctype="multipart/form-data">
            <legend>Choose File</legend>
            <input type="file" name="website">
            <input type="submit" name="website_submit">
       </form>
    </body>
</html>
<?php session_start(); ?>

<html>
<head>
<style>
    body
    {
        background-color: #87faab;
    }
    .btn {
  background: #3498db;
  background-image: -webkit-linear-gradient(top, #3498db, #020608);
  background-image: -moz-linear-gradient(top, #3498db, #020608);
  background-image: -ms-linear-gradient(top, #3498db, #020608);
  background-image: -o-linear-gradient(top, #3498db, #020608);
  background-image: linear-gradient(to bottom, #3498db, #020608);
  -webkit-border-radius: 33;
  -moz-border-radius: 33;
  border-radius: 33px;
  font-family: Courier New;
  color: #ffffff;
  font-size: 20px;
  padding: 6px 20px 10px 20px;
  border: solid #000000 4px;
  text-decoration: none;
}

.btn:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #34d981);
  background-image: -moz-linear-gradient(top, #3cb0fd, #34d981);
  background-image: -ms-linear-gradient(top, #3cb0fd, #34d981);
  background-image: -o-linear-gradient(top, #3cb0fd, #34d981);
  background-image: linear-gradient(to bottom, #3cb0fd, #34d981);
  text-decoration: none;
}
    form
    {
        text-align: center;
    }
    h1 
    {
        text-align:center;
    }
</style>
<body>

<form method = "post" enctype = "multipart/form-data">
    <front size = "6"><b> File Search: </b></front><br>
    <input type = "file" class = "btn" name = "fileToUpload" id = "upload"><br>
    <br>
    <input type = "submit" class = "btn" name = "upload" value = "upload">
</form>
</body>
</html>
<?php
//ini_set('display_errors', true);
//error_reporting(E_ALL);
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);


//echo "target_file: " . $target_file . ".";
/*$fileArray = array_filter(scandir($target_dir), function($item)
{
    return !is_dir($target_dir . $item);
});
//$fileArray = array_slice($fileArray, 2);
*/
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
mkdir("uploads", 0755);
chmod("uploads", 0777);
//print_r($_POST);
echo "<br>";

if(!isset($_SESSION["numUploads"]))
{

    $_SESSION["numUploads"] = 0;
    //print_r($_SESSION);
}

if(isset($_POST["upload"]))
{
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== FALSE)
    {
        echo "<center>File is an image - " . $check["mime"] . ".</center>";
        echo "<br>";
        $uploadOk = 1;
    }
    else
    {
        echo "<center>File is not an image.</center><br>";
        $uploadOk = 0;
    }


    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<center>Sorry, file already exists.</center><br>";
        $uploadOk = 0;
    }

    if($_FILES["fileToUpload"]["size"] > 1000000)
    {
        $$uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" )
    {
        echo "<center>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</center><br>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0)
    {
        echo "<center>Sorry, your file was not uploaded.</center><br>";
        // if everything is ok, try to upload file
    }

    else
    {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
        {
            echo "<center>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</center>";
            echo "<br>";
            $img = basename( $_FILES['fileToUpload']['name']);
            $target_path = "uploads/";
            $target_path = dirname(__FILE__)."/".$target_path.$img;
            //print($target_path);
            chmod($target_path,0777);
            //print_r($_SESSION);
            $_SESSION["numUploads"]++;
            //print_r($_SESSION);

        }
        else
        {
            echo "<center>Sorry, there was an error uploading your file.</center><br>";
        }
    }
}

if($_SESSION["numUploads"] != 1)
    echo "<h1> You have uploaded " . $_SESSION["numUploads"] . " files. </h1><br>";
elseif($_SESSION["numUploads"] == 1)
    echo "<h1> You have uploaded 1 file. </h1>";
$fileArray = array_slice(scandir($target_dir), 2);

// Delete here
if(isset($_POST["delete"]))
{
    $size = count($fileArray);
    for($ndx = 0; $ndx < $size; ++$ndx)
    {
        if($_POST["file" . $ndx])
        {
            $img = $fileArray[$ndx];
            $target_path = "uploads/";
            $target_path = dirname(__FILE__)."/".$target_path.$img;
            if(unlink($target_path))
            {
                echo "<center> " . $img . " deleted!</center><br>";
            }
            unset($fileArray[$ndx]);

        }
    }
    $fileArray = array_values($fileArray);
}
//print_r($fileArray);
echo "<form action = \"upload.php\" method = \"post\" enctype = \"multipart/form-data\">
    <table align = \"center\" border = \"1\"></td>
    <tr><td font size = \"35\" width = \"200\" align = \"center\"><b>Filename</b></td>
    <td font size = \"35\" width = \"80\" align = \"center\"><b>Delete</b></tr>";


for($ndx = 0; $ndx < sizeof($fileArray); ++$ndx)
{
    echo "<tr><td><b><a href=\" uploads/" . $fileArray[$ndx] . "\">" . $fileArray[$ndx] . "</a></b></td>
            <td align=\"center\"><input type=\"checkbox\" name=\"file" . $ndx ."\"></td></tr>";
}
echo "<tr><td></td><td align = \"center\"><input type=\"submit\" name=\"delete\" value=\"delete\"></td></tr></table></form>";


session_write_close();
?>


<?php


$name_error = $email_error = $title_error = $desc_error = "";
$n = $e = $ds = $t = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (empty($_POST["title"])) {
        $title_error = "Topic title is required";
    } else {
        $t = test_input($_POST["title"]);
    }


    if (empty($_POST["name"])) {
        $name_error = "Name is required";
    } else {
        $n = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $n)) {
            $name_error = "Only letters and white space allowed";
        }
    }


    if (empty($_POST["email"])) {
        $email_error = "Email is required";
    } else {
        $e = test_input($_POST["email"]);
        if (!filter_var($e, FILTER_VALIDATE_EMAIL)) {
            $email_error = "Invalid email format";
        }
    }
    if (empty($_POST["desc"])) {
        $desc_error = "Description is required";
    } else {
        $ds = test_input($_POST["desc"]);
    }


    if ($name_error == "" and $email_error == "" and $title_error == "" and $desc_error == "") {

        $name_error = $email_error = $title_error = $desc_error = "";
        $n = $e = $ds = $t = "";
        session_start();

        $db = mysqli_connect('localhost:3307', 'root', '', 'discussx') or die('Could not connect to database');
        $name = mysqli_real_escape_string($db, $_POST["name"]);
        $email = mysqli_real_escape_string($db, $_POST["email"]);
        $title = mysqli_real_escape_string($db, $_POST["title"]);
        $desc = mysqli_real_escape_string($db, $_POST["desc"]);
        $query = "INSERT INTO topics(topic_title, topic_username,topic_no_of_posts, topic_email) VALUES ('$title','$name',1, '$email')";
        mysqli_query($db, $query);
        $new_query = "CREATE TABLE `discussx`. `$title` ( `post_id` INT NOT NULL AUTO_INCREMENT , `post_date_of_creation` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `post_username` VARCHAR(255) NOT NULL , `post_email` VARCHAR(255) NOT NULL , 
                `post_desc` TEXT NOT NULL , PRIMARY KEY (`post_id`)) ENGINE = InnoDB;";
        mysqli_query($db, $new_query);
        $new_query = "INSERT INTO `$title`(post_username, post_email, post_desc) VALUES ('$name', '$email', '$desc');";
        // echo ($new_query);
        mysqli_query($db, $new_query);
    }
}


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

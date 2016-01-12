<?php
/**
 * Created by PhpStorm.
 * User: JaanMartin
 * Date: 12.01.2016
 * Time: 10:11
 */
require_once(__DIR__.'/functions.php');
require_once(__DIR__.'/user_manage_class.php');
if(isset($_SESSION['logged_in_uid'])){
    header("Location: /table2.php");
}
$user_manage = new user_manage($connection);
$username_error = $pw_error = $usernamecreate_error = $passwordcreate_error ="";
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(empty($_POST["loginusername"])){
        $username_error = "Username is required";
    }else{
        $username = safe_input($_POST["username"]);
    }
    if(empty($_POST["loginpassword"])){
        $pw_error = "Password is required";
    }else{
        $loginpassword = safe_input($_POST['loginpassword']);
        $loginpassword = hash(sha512, $loginpassword);
    }
    if(empty($_POST["createusername"])){
        $usernamecreate_error = "Username is required";
    }else{
        $createusername = safe_input($_POST["createusername"]);
    }
    if(empty($_POST["createpassword"])){
        $passwordcreate_error = "Password is required";
    }

}

?>
<!DOCTYPE html>
<html lang="et"">
<head>
    <meta charset="utf-8">
    <title>Smth Smth</title>
</head>
<body>
    <div id="main">
        <div id="login">
            <form action="<?php echo $_SERVER["PHP_SELF"]?> " method="post">
            <h1>User login</h1>
            <p>Username</p>
            <input name="loginusername" type="text" placeholder="Username">
            <br>
            <p>Password</p>
            <input name="loginpassword" type="text" placeholder="Password">
            <br>
            <button name="login"type="submit">Login</button>
            </form>
        </div>

        <div id="create">
            <form action="<?php echo $_SERVER["PHP_SELF"]?> " method="post">
            <h1>Create User</h1>
            <p>Username</p>
            <input name="createusername" type="text" placeholder="Username">
            <br>
            <p>Password</p>
            <input name="createpassword" type="text" placeholder="Password">
            <br>
            <button name="create" type="submit">Create User</button>
            </form>
        </div>
    </div>
</body>
</html>
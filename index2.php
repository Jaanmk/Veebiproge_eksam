<?php
/**
 * Created by PhpStorm.
 * User: JaanMartin
 * Date: 12.01.2016
 * Time: 10:11
 */
require_once(__DIR__.'/functions.php');
require_once(__DIR__.'/user_manage_class.php');

$user_manage = new user_manage($connection);
$username_error = $pw_error = $usernamecreate_error = $passwordcreate_error ="";
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(empty($_POST["loginusername"])){
        $username_error = "Username is required";
    }else{
        //echo "kasutajanimi db";
        $username_to_db = clean_input($_POST["loginusername"]);
    }
    if(empty($_POST["loginpassword"])){

        $pw_error = "Password is required";
    }else{
        //echo "pass db";
        $loginpassword = clean_input($_POST['loginpassword']);
        $password_to_db = hash(sha512, $loginpassword);
    }
    if(empty($_POST["createusername"])){
        $usernamecreate_error = "Username is required";
    }else{
        $createusername = clean_input($_POST["createusername"]);
      //  echo"create kasutajanimi korras";
    }
    if(empty($_POST["createpassword"])){
        $passwordcreate_error = "Password is required";
    }else{
        $createpassword = clean_input($_POST['createpassword']);
        $createpassword=hash(sha512, $createpassword);
       // echo"create password korras";
    }
    if($username_error== "" and $pw_error ==""){
        //echo"läheme db";
        $response = $user_manage->loginUser($username_to_db, $password_to_db);
    }
    if($passwordcreate_error == "" and $usernamecreate_error ==""){
        $response = $user_manage->createUser($createusername, $createpassword);
       // echo"create läheb baasi";
    }
}


?>
<?php
$_SESSION['logged_in_uid'] = $response->success->user->id;
$_SESSION['logged_in_username'] = $response->success->user->username;
if(isset($_SESSION['logged_in_uid'])){
    header("Location: /table2.php");
}
?>
<!DOCTYPE html>
<html lang="et"">
<head>
    <meta charset="utf-8">
    <title>Smth Smth</title>
</head>
<body>
<?var_dump($_SESSION);?>
    <div id="main">
        <div id="login">
            <form action="<?php echo $_SERVER["PHP_SELF"]?> " method="post">
            <h1>User login</h1>
            <p>Username</p>
            <input name="loginusername" type="text" placeholder="Username"><?php echo"$username_error";?>
            <br>
            <p>Password</p>
            <input name="loginpassword" type="text" placeholder="Password"><?php echo"$pw_error";?>
            <br>
            <button name="login"type="submit">Login</button>
            </form>
        </div>

        <div id="create">

            <form action="<?php echo $_SERVER["PHP_SELF"]?> " method="post">
            <h1>Create User</h1>
             <h2>
                <?php if(isset($response->success)):	 ?>

                    <p><?=$response->success->message;?></p>

                <?php	elseif(isset($response->error)): ?>

                    <p><?=$response->error->message;?></p>

                <?php	endif; ?>
             </h2>
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
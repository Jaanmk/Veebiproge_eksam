<?php
/**
 * Created by PhpStorm.
 * User: JaanMartin
 * Date: 13.01.2016
 * Time: 15:11
 */
require_once(__DIR__.'/../functions.php');
require_once(__DIR__.'/../user_manage_class.php');
$user_manage = new user_manage($connection);
if(isset($_GET["temperatuur"]) && isset($_GET["andur"])){

    $response = $user_manage->arduinput($_GET['temperatuur'], $_GET['andur']);
}else{

}
?>
<?php if(isset($response->success)):	 ?>

    <p><?=$response->success->message;?></p>

<?php	elseif(isset($response->error)): ?>

    <p><?=$response->error->message;?></p>

<?php	endif; ?>
<div class="block"">
<form action="<?php echo $_SERVER["PHP_SELF"]?> " method="GET">

    <h1>Login</h1>
    <p>
        <label for="temperatuur">temp</label>
        <input name="temperatuur" type="text" placeholder="temp"><?php echo"$username_error";?>
    </p>
    <p>

        <label for="andur">andur</label>
        <input name="andur" type="text" placeholder="andur"><?php echo"$pw_error";?>
    </p>
    <p>
        <button type="submit">Login</button>
    </p>
</form>
</div>

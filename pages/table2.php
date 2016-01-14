<?php
/**
 * Created by PhpStorm.
 * User: JaanMartin
 * Date: 12.01.2016
 * Time: 18:13
 */
require_once(__DIR__.'/../functions.php');
require_once(__DIR__.'/../user_manage_class.php');
$user_manage = new user_manage($connection);

$users_array = $user_manage->getAllUsers();
if(isset($_GET["delete"])) {
    $response = $user_manage->deleteUsers($_GET["delete"]);
}

if(isset($_GET["update"])){
    $response = $user_manage->updateUsers($_GET['first_name'], $_GET['last_name'], $_GET['phone'], $_GET['user_id'], $_GET['badge']);
}

$keyword = "";
if(isset($_GET["keyword"])) {
    $keyword = $_GET["keyword"];
    $users_array = $user_manage->getAllUsers($keyword);
}
else{
    $users_array = $user_manage->getAllUsers();
}
?>
<?php
require_once(__DIR__.'/header.php');
?>

<body>
<table class="table table-hover">
    <tr>
        <th>id</th>
        <th>aeg</th>
        <th>temperatuur</th>
        <th>andur</th>


        <th>Muuda</th>
        <th>Kustuta</th>
    </tr>
    <?php
    for($i = 0; $i < count($users_array); $i++){
        if(isset($_GET["edit"]) && $_GET["edit"] == $users_array[$i]->id) {
            echo "<tr>";
            echo '<form action="/pages/table2.php" method="get">';
            echo "<input type='hidden' name='user_id' value='".$users_array[$i]->id."'>";
            echo "<td>".$users_array[$i]->id."</td> ";
            echo "<td><input class='form-control' name='first_name' value='".$users_array[$i]->first_name."'></td>";
            echo "<td><input class='form-control' name='last_name' value='".$users_array[$i]->last_name."'></td>";
            echo "<td><input class='form-control' name='phone' value='".$users_array[$i]->phone."'></td>";

            echo "<td><input class='btn btn-default btn-block' name='update' type='submit' value='Uuenda'></td>";
            echo "<td><a class='btn btn-default btn-block' href='/pages/table2.php'>Katkesta</a></td>";
            echo "</tr>";
            echo "</form>";
        } else {
            echo "<tr> <td>".$users_array[$i]->id."</td> ";
            echo "<td>".$users_array[$i]->first_name."</td>";
            echo "<td>".$users_array[$i]->last_name."</td>";
            echo "<td>".$users_array[$i]->phone."</td>";
            echo '<td><a class="btn btn-info btn-block" href="/pages/table2.php?edit='.$users_array[$i]->id.'">Muuda</a></td>';
            echo '<td><a class="btn btn-info btn-block" href="/pages/table2.php?delete='.$users_array[$i]->id.'">Kustuta</a></td></tr>';

        }
    }
    ?>
</table>



</body>
</html>
<?php
/**
 * Created by PhpStorm.
 * User: JaanMartin
 * Date: 7.01.2016
 * Time: 14:04
 */
require_once(__DIR__.'/functions.php');
require_once(__DIR__.'/user_class.php');

var_dump($_SESSION);
?>
<html>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3">
            <h2>Kontakti lisamine</h2><br><br>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <?php if(isset($response->success)):	 ?>

                    <p><?=$response->success->message;?></p>

                <?php	elseif(isset($response->error)): ?>

                    <p><?=$response->error->message;?></p>

                <?php	endif; ?>

                <label>Eesnimi</label>
                <input class="form-control" name="contact_firstname" type="text" placeholder="Eesnimi"  ><?php echo $contact_firstname_error;?><br>
                <label>Perekonnanimi</label>
                <input class="form-control" name="contact_lastname" type="text" placeholder="Perekonnanimi" > <?php echo $contact_lastname_error;?> <br>
                <label>Telefon</label>
                <input class="form-control" name="contact_phone" type="text" placeholder="Telefon"><?php echo $contact_phone_error;?> <br>
                <button type="submit" class="btn btn-info btn-block">Sisesta</button>
                <br><br>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9">
            <table class="table table-hover">
                <tr>
                    <th>Eesnimi</th>
                    <th>Perekonnanimi</th>
                    <th>Telefon</th>
                  
                    <th>Muuda</th>
                    <th>Kustuta</th>
                </tr>
                <?php
                for($i = 0; $i < count($users_array); $i++){
                    if(isset($_GET["edit"]) && $_GET["edit"] == $users_array[$i]->id) {
                        echo "<tr>";
                        echo '<form action="/table.php" method="get">';
                        echo "<input type='hidden' name='user_id' value='".$users_array[$i]->id."'>";
                        echo "<td>".$users_array[$i]->id."</td> ";
                        echo "<td><input class='form-control' name='contact_firstname' value='".$users_array[$i]->contact_firstname."'></td>";
                        echo "<td><input class='form-control' name='contact_lastname' value='".$users_array[$i]->contact_lastname."'></td>";
                        echo "<td><input class='form-control' name='contact_phone' value='".$users_array[$i]->contact_phone."'></td>";

                        echo "<td><input class='btn btn-default btn-block' name='update' type='submit' value='Uuenda'></td>";
                        echo "<td><a class='btn btn-default btn-block' href='/table.php'>Katkesta</a></td>";
                        echo "</tr>";
                        echo "</form>";
                    } else {
                        echo "<tr> <td>".$users_array[$i]->id."</td> ";
                        echo "<td>".$users_array[$i]->contact_firstname."</td>";
                        echo "<td>".$users_array[$i]->contact_lastname."</td>";
                        echo "<td>".$users_array[$i]->contact_phone."</td>";
                        echo '<td><a class="btn btn-info btn-block" href="/table.php?edit='.$users_array[$i]->id.'">Muuda</a></td>';
                        echo '<td><a class="btn btn-info btn-block" href="/table.php?delete='.$users_array[$i]->id.'">Kustuta</a></td></tr>';

                    }
                }
                ?>
            </table>
        </div>
        <div class="col-sm-2">
            <label class="text"> Otsi kasutajat </label>
            <form action="/table.php" method="get">
                <input class="form-control" name="keyword" type="search" value="<?=$keyword?>" ><br>
                <input type="submit" value="otsi" class="btn btn-info btn-block">
            </form>
        </div>
    </div>
</div>
</html>
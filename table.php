<?php
	$page_title = "User edit";
	$page_file_name = "userpage.php";
	require_once(__DIR__."/functions.php");
	require_once(__DIR__."/user_manage_class.php");
	if(is_null($_SESSION['logged_in_uid'])){
		session_destroy();
		header("Location: index.php");
	}
	$user_manage = new user_manage($connection);
	$userfirstname_error = "";
	$userlastname_error = "";
	$useraddress_error = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST["first_name"])) {
		$userfirstname_error = "FirstName is required";
		} else {
		$first_name = test_input($_POST["first_name"]);
		}

		if (empty($_POST["last_name"])) {
		$userlastname_error = "LastName is required";
		} else {
		$last_name = test_input($_POST["last_name"]);
		}

		if (empty($_POST["userphone"])) {
		$userphone_error = "Address required";
		} else {
		$phone = test_input($_POST["userphone"]);
		}
		if (empty($_POST["tag"])) {
		$tag_error = "tag required";
		} else {
		$usertag = test_input($_POST["tag"]);
		}

		if ($userfirstname_error == "" and $userlastname_error == "" and $userphone_error=="" and $tag_error==""){
			$response = $user_manage->addcontact($first_name, $last_name, $phone,$usertag);
		}
	}
	

	$users_array = $user_manage->getAllUsers();
	$tags_array = $user_manage->get_unique_tag();
	if(isset($_GET["delete"])) {
		$response = $user_manage->deleteUsers($_GET["delete"]);
	}

	if(isset($_GET["update"])){
		$response = $user_manage->updateUsers($_GET['first_name'], $_GET['last_name'], $_GET['phone'], $_GET['tag'], $_GET['list_id']);
	}

	
	if(isset($_GET["keyword"])){
		$keyword = $_GET["keyword"];
		$users_array = $user_manage->get_all_phonebook($keyword);
	}else{
		$users_array = $user_manage->get_all_phonebook();
	}
	?>
	<html>
	
				<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<?php if(isset($response->success)):	 ?>

						<p><?=$response->success->message;?></p>

						<?php	elseif(isset($response->error)): ?>

						<p><?=$response->error->message;?></p>

						<?php	endif; ?>

					<label>Eesnimi</label>
					<input class="form-control" name="first_name" type="text" placeholder="Eesnimi"  ><?php echo $userfirstname_error;?><br>
					<label>Perekonnanimi</label>
					<input class="form-control" name="last_name" type="text" placeholder="Perekonnanimi" > <?php echo $userlastname_error;?> <br>
					<label>telefon</label>
					<input class="form-control" name="userphone" type="text" placeholder="telefon"><?php echo $useraddress_error;?> <br>
					<label>markeering</label>
					<input class="form-control" name="tag" type="text" placeholder="tag"><?php echo $tag_error;?> <br>
					
					<button type="submit" class="btn btn-info btn-block">Sisesta</button>
					<br><br>
				</form>
		
<div>
<form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<select name="keyword">
	<?php
	var_dump($tags_array);
	for($i = 0; $i < count($tags_array); $i++){
		echo "<option value='".$tags_array[$i]->tags."'>".$tags_array[$i]->tags."</option>";
  
	}
	?>
</select>
<button type="submit" >Otsi</button>


</form>
<button onclick="window.location.href='table.php'">Puhasta otsing</button>
</div>
			<table >
				<tr>
					<th>ID</th>
					<th>Eesnimi</th>
					<th>Perekonnanimi</th>
					<th>Telefon</th>
					<th>Markeering</th>
					<th>Muuda</th>
					<th>Kustuta</th>
				</tr>
				<?php
				for($i = 0; $i < count($users_array); $i++){
					if(isset($_GET["edit"]) && $_GET["edit"] == $users_array[$i]->id) {
						echo "<tr>";
						echo '<form action="table.php" method="get">';
						echo "<input type='hidden' name='list_id' value='".$users_array[$i]->id."'>";
						echo "<td>".$users_array[$i]->id."</td>";
						echo "<td><input class='form-control' name='first_name' value='".$users_array[$i]->first_name."'></td>";
						echo "<td><input class='form-control' name='last_name' value='".$users_array[$i]->last_name."'></td>";
						echo "<td><input class='form-control' name='phone' value='".$users_array[$i]->phone."'></td>";
						echo "<td><input class='form-control' name='tag' value='".$users_array[$i]->tag."'></td>";
						echo "<td><input class='btn btn-default btn-block' name='update' type='submit' value='Uuenda'></td>";
						echo "<td><a class='btn btn-default btn-block' href='table.php'>Katkesta</a></td>";
						echo "</tr>";
						echo "</form>";
					} else {
						echo "<tr> <td>".$users_array[$i]->id."</td> ";
						echo "<td>".$users_array[$i]->first_name."</td>";
						echo "<td>".$users_array[$i]->last_name."</td>";
						echo "<td>".$users_array[$i]->phone."</td> ";
						echo "<td>".$users_array[$i]->tag."</td> ";
	
						echo '<td><a class="btn btn-info btn-block" href="table.php?edit='.$users_array[$i]->id.'">Muuda</a></td>';
						echo '<td><a class="btn btn-info btn-block" href="table.php?delete='.$users_array[$i]->id.'">Kustuta</a></td></tr>';

					}
				}
				?>
			</table>
		
	
	</html>

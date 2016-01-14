<?php
require_once(__DIR__.'/functions.php');

class user_manage
{

    private $connection;

    function __construct($connection){
        $this->connection = $connection;
    }


    function loginUser($username_to_db, $password_to_db){

        $response = new StdClass();

        $stmt = $this->connection->prepare("SELECT id FROM userbase WHERE username=?");
        echo($this->connection->error);
        $stmt->bind_param("s", $username_to_db);
        $stmt->execute();
        if(!$stmt->fetch()){

            $error = new StdClass();
            $error->id = 0;
            $error->message = "Tundmatu kasutaja!";
            $response->error = $error;

            return $response;

        }
        $stmt->close();
        $stmt = $this->connection->prepare("SELECT id, username FROM userbase WHERE username = ? AND password = ? ");
        echo($this->connection->error);
        $stmt->bind_param("ss", $username_to_db, $password_to_db);
        $stmt->bind_result($id_from_db, $username_from_db);
        $stmt->execute();
        if($stmt->fetch()){

            $success = new StdClass();
            $success->message = "Edukalt sisse logitud!!!";

            $user = new StdClass();
            $user->id = $id_from_db;
            $user->username = $username_from_db;


            $success->user = $user;

            $response->success = $success;

        } else {
            echo($stmt->error);
            $error = new StdClass();
            $error->id = 1;
            $error->message = "Meie hampstritel jooksev server on ülekoormatud palun oodake.";
            $response->error = $error;

        }
        $stmt->close();
        return $response;
    }

    function createUser($username, $password){

        $response = new StdClass();

        $stmt = $this->connection->prepare("SELECT id FROM userbase WHERE username=?");
        #echo($this->connection->error);
        $stmt->bind_param("s", $username);
        $stmt->bind_result($id);
        $stmt->execute();
        if($stmt->fetch()){

            $error = new StdClass();
            $error->id = 0;
            $error->message = "Kasutajanimi on juba kasutusel";
            $response->error = $error;

            return $response;

        }
        $stmt->close();
        $stmt = $this->connection->prepare("INSERT INTO userbase (username, password) VALUES (?,?)");
        $stmt->bind_param("ss", $username, $password);

        if($stmt->execute()){

            $success = new StdClass();
            $success->message = "Kasutaja edukalt loodud";

            $response->success = $success;
            header("Location: index.php");


        } else {
            #echo($stmt->error);
            $error = new StdClass();
            $error->id = 1;
            $error->message = "Hiireke läks katki";
            $response->error = $error;

        }
        $stmt->close();

        return $response;
    }

    function getAllUsers(){

        
        $stmt = $this->connection->prepare("SELECT id, first_name, last_name, address FROM userbase WHERE deleted IS NULL");
        echo $this->connection->error;
        $stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db, $address_from_db);
        $stmt->execute();
        $array = array();
        while($stmt->fetch()) {

            $users = new StdClass();
            $users->id = $id_from_db;
            $users->first_name = $first_name_from_db;
            $users->last_name = $last_name_from_db;
            $users->address = $address_from_db;

            array_push($array, $users);

        }

        if($keyword == ""){
            //ei otsi
            $search = "%%";
        }else{
            //otsime
            $search = "%".$keyword."%";
        }
        $stmt = $this->connection->prepare("SELECT id, first_name, last_name, address FROM userbase WHERE deleted IS NULL ");
        echo $this->connection->error;

        $stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db, $address_from_db);
        $stmt->execute();
        $array = array();
        while($stmt->fetch()){

            $users = new StdClass();
            $users->id = $id_from_db;
            $users->first_name = $first_name_from_db;
            $users->last_name = $last_name_from_db;
            $users->address = $address_from_db;
            array_push($array, $users);

        }

        return $array;
    }


    function deleteUsers($user_id){

        $stmt = $this->connection->prepare("UPDATE phonebook SET deleted=NOW() WHERE id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();



        $stmt->close();
    }


    function updateUsers($first_name, $last_name, $phone, $tag, $list_id){

        $stmt = $this->connection->prepare("UPDATE phonebook SET first_name=?, last_name=?, phone_number=?, tag=? WHERE id=?");
        echo $this->connection->error;
        $stmt->bind_param("sssss", $first_name, $last_name, $phone, $tag ,$list_id);
        $stmt->execute();

        // tühjendame aadressirea
        header("Location: table.php");

        $stmt->close();

    }
    

       
	function editUser($userfirstname, $userlastname, $userphone, $usertag, $list_id){
		$response = new StdClass();
		$stmt = $this->connection->prepare("UPDATE phonebook SET first_name=?, last_name=?, phone_number=?, tag=? WHERE id=?");
		$stmt->bind_param("sssss", $userfirstname, $userlastname, $userphone, $usertag, $list_id);
		if($stmt->execute()){

            $success = new StdClass();
            $success->message = "andmed lisatud";

            $response->success = $success;
            header("Location: table.php");


        } else {
            #echo($stmt->error);
            $error = new StdClass();
            $error->id = 1;
            $error->message = "Hiireke läks katki";
            $response->error = $error;

        }
        $stmt->close();

        return $response;
		
	}
	function get_all_phonebook($keyword=""){

        
       
		
		if($keyword == ""){
			//ei otsi
			$search = "%%";
		}else{
			//otsime
			$search = "%".$keyword."%";
		}
		$stmt = $this->connection->prepare("SELECT id, first_name, last_name, phone_number, tag from phonebook WHERE deleted IS NULL AND (tag LIKE ?)");
		$stmt->bind_param("s", $search);
		$stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db, $phone_from_db, $tag_from_db);
		$stmt->execute();
		$array = array();
		while($stmt->fetch()){
			
			$users = new StdClass();
			$users->id = $id_from_db;
			$users->address = $address_from_db;
			$users->first_name = $first_name_from_db;
			$users->last_name = $last_name_from_db;
			$users->phone = $phone_from_db;
			$users->tag = $tag_from_db;
			array_push($array, $users);
			
			}
			
		return $array;
    }
	function get_unique_tag(){
		$stmt = $this->connection->prepare("SELECT DISTINCT(tag) AS tag FROM phonebook where deleted is null");
		$stmt->bind_result($unique_tag);
		if($stmt->execute()){

			


        } else {
           
        }
		$array = array();
		while($stmt->fetch()){
			$uniques = new StdClass();
			$uniques->tags = $unique_tag;
			array_push($array, $uniques);
		}
		return $array;
	}
	function addcontact($first_name, $last_name, $phone,$usertag){
		$stmt = $this->connection->prepare("insert into phonebook (first_name, last_name, phone_number, tag) values(?,?,?,?)");
		$stmt->bind_param("ssss", $first_name, $last_name, $phone,$usertag);
		$stmt->execute();
		
	}
}

	


?>
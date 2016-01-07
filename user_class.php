<?php

/**
 * Created by PhpStorm.
 * User: JaanMartin
 * Date: 7.01.2016
 * Time: 14:02
 */
require_once(__DIR__.'/functions.php');

class userCreate {
    private $connection;

    function __construct($connection){
        $this->connection = $connection;
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
            header("Location: /index.php");


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

}
class userLogin {
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

}
class conInsert {
    private $connection;

    function __construct($connection){
        $this->connection = $connection;
    }

    function editUser($contact_firstname, $contact_lastname, $contact_phone){

        $response = new StdClass();

        $stmt = $this->connection->prepare("INSERT INTO contacts (first_name, last_name, phone_number) VALUES (?,?,?)" );
        #echo($this->connection->error);
        $stmt->bind_param("sss", $contact_firstname, $contact_lastname, $contact_phone);
        //$stmt->execute();
        //echo($this->connection->error);
        if($stmt->execute()){

            $success = new StdClass();
            $success->message = "Andmed uuendatud";

            $response->success = $success;


        } else {
            #echo($stmt->error);
            $error = new StdClass();
            $error->id = 0;
            $error->message = "Hiireke läks katki";
            $response->error = $error;

        }
        $stmt->close();

        return $response;
    }

}
class getAllUsers{
    private $connection;

    function __construct($connection){
        $this->connection = $connection;
    }
    function getAllUsers($keyword=""){

        if($keyword == ""){
            //ei otsi
            $search = "%%";
        }else{
            //otsime
            $search = "%".$keyword."%";
        }
        $stmt = $this->connection->prepare("SELECT id, first_name, last_name, phone_number FROM contacts WHERE deleted IS NULL AND (last_name LIKE ?)");
        echo $this->connection->error;
        $stmt->bind_param("s", $search);
        $stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db, $phone_from_db);
        $stmt->execute();
        $array = array();
        while($stmt->fetch()){

            $users = new StdClass();
            $users->id = $id_from_db;
            $users->first_name = $first_name_from_db;
            $users->last_name = $last_name_from_db;
            $users->phone = $phone_from_db;
            array_push($array, $users);

        }

        return $array;
    }
}
class deleteUsers{
    private $connection;

    function __construct($connection){
        $this->connection = $connection;
    }
    function deleteUsers($user_id){

        $stmt = $this->connection->prepare("UPDATE contacts SET deleted=NOW() WHERE id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();



        $stmt->close();
    }
}
class updateUsers{
    private $connection;

    function __construct($connection){
        $this->connection = $connection;
    }
    function updateUsers($first_name, $last_name, $phone, $id){

        $stmt = $this->connection->prepare("UPDATE contacts SET first_name=?, last_name=?, phone_number=? WHERE id=?");
        echo $this->connection->error;
        $stmt->bind_param("ssss", $first_name, $last_name, $phone, $id);
        $stmt->execute();

        // tühjendame aadressirea
        header("Location: /table.php");

        $stmt->close();

    }
}

?>
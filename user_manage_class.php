<?php
/**
 * Created by PhpStorm.
 * User: JaanMartin
 * Date: 12.01.2016
 * Time: 10:34
 */

namespace user_manage_class;


class user_manage{
    private $connection;
    function __construct($connection){
        $this->connection = $connection;
        echo"smthsmth";
    }

    function loginUser($loginusername, $loginpassword){
        $response = new StdClass();
    }
}
?>
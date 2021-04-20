<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Classes/DB.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Entity/User.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Manager/UserManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/Classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Entity/Message.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Manager/MessageManager.php';

use App\Classes\DB;
use App\Entity\User;
use App\Manager\UserManager;

$manager = new UserManager();

function sanitize($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = addslashes($data);

    return $data;
}



if(isset($_POST, $_POST["email"], $_POST["name"],$_POST["pass"])){
    $mail = sanitize($_POST['email']);
    $name = sanitize($_POST['name']);
    $pass = password_hash(sanitize($_POST['pass']), PASSWORD_DEFAULT);
    if(strlen($mail) > 0 && filter_var($mail, FILTER_VALIDATE_EMAIL)){
        if(strlen($name) > 0){
            if(strlen($pass) > 0){
                $manager->insertUser($mail,$pass,$name);
                header("Location: ../?success=inscription");
            }
            else{

            }
        }
    }
    else{
        header("Location: ../?error=email");
    }
}
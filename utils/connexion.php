<?php
require_once("../Classes/DB.php");
require_once("../Entity/User.php");
require_once("../Manager/UserManager.php");

use App\Classes\DB;
use App\Entity\User;
use App\Manager\UserManager;


session_start();

if(isset($_GET, $_GET["deco"]) && $_GET["deco"] === "1" ){
    unset($_SESSION["user"]);
    session_destroy();
    header("Location: ../index.php");
}
else{
    if(isset($_POST, $_POST["email"], $_POST["pass"])){
        $userManager = new UserManager();
        $user = $userManager->getUserByLog($_POST["email"], $_POST["pass"]);
        if(!is_null($user)){
            $_SESSION["user"] = $user->getId();
            header("Location: ../index.php");
        }
        else{
            echo "erreur";
        }
    }
}
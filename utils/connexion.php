<?php
require_once("../Classes/DB.php");
require_once("../Entity/User.php");
require_once("../Manager/UserManager.php");

use App\Classes\DB;
use App\Entity\User;
use App\Manager\UserManager;

function sanitize($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = addslashes($data);

    return $data;
}


session_start();

if(isset($_GET, $_GET["deco"]) && $_GET["deco"] === "1" ){
    unset($_SESSION["user"]);
    session_destroy();
    header("Location: ../");
}
else{
    if(isset($_POST, $_POST["email"], $_POST["pass"])){
        $userManager = new UserManager();
        $user = $userManager->getUserByLog(sanitize($_POST["email"]), sanitize($_POST["pass"]));
        if(!is_null($user)){
            $_SESSION["user"] = $user->getId();
            header("Location: ../");
        }
        else{
            header("Location: ../?error=connection");
        }
    }
}
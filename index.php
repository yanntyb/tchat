<?php

require_once("./Classes/DB.php");
require_once("./Entity/User.php");
require_once("./Manager/UserManager.php");

use App\Classes\DB;
use App\Entity\User;
use App\Manager\UserManager;


session_start();
if(isset($_SESSION, $_SESSION["user"])){
    $connected = true;
    $manager = new UserManager();
}
else{
    $connected = false;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/chat.css">
    <link rel="stylesheet" href="./css/user.css">
    <link rel="stylesheet" href="./css/data.css">
    <link rel="stylesheet" href="./css/privateMessage.css">
    <title>Document</title>
</head>
<body>
<div id="global">
    <div id="chat">
        <div id="message">
            <div id="message_send"></div>
        </div>
        <?php
        if($connected){?>
            <div id="send">
                <form action="">
                    <div>
                        <input id="sendMessageInput" type="text" placeholder="message">
                        <input id="sendMessage" type="submit" value="envoyer">
                    </div>
                </form>
            </div><?php
        }
        ?>

    </div>
    <div id="data">
        <div id="connexion"><?php
            if(!$connected){?>
                <div id="connexionForm">
                    <form action="utils/connexion.php" method="POST">
                        <div>
                            <input name="email" type="text" placeholder="email">
                            <label for="pass">Password</label>
                            <input name="pass" type="password">
                        </div>
                        <div>
                            <input type="submit" value="connexion">
                        </div>
                    </form>
                </div>
                <?php
            }
            else{?>
                <h1>Connecté en temps que <?php echo $manager->getUserById($_SESSION["user"])->getName() ?></h1>
                <div><a id="deco" href="utils/connexion.php?deco=1">Deconnexion</a></div>
                <div><a id="modifProfil" href="#">Modifier Profile</a></div>

                <?php
            }
            ?>
        </div>
        <div id="privateMessage"></div>
    </div>
</div>
<script src="js/message.js"></script>
</body>
</html>
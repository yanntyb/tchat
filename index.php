<?php
session_start();
$_SESSION["user"] = 1;
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
    <title>Document</title>
</head>
<body>
<div id="global">
    <div id="chat">
        <div id="message">
            <div id="message_send"></div>
        </div>
        <div id="send">
            <form action="">
                <div>
                    <input id="sendMessageInput" type="text" placeholder="message">
                    <input id="sendMessage" type="submit" value="envoyer">
                </div>
            </form>
        </div>
    </div>
    <div id="users"></div>
</div>
<script src="js/message.js"></script>
</body>
</html>
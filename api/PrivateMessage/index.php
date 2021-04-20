<?php

session_start();


require_once $_SERVER['DOCUMENT_ROOT'] . '/Classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Entity/Message.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Manager/MessageManager.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/Entity/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Manager/UserManager.php';

use App\Entity\Message;
use App\Manager\MessageManager;
use App\Entity\User;
use App\Manager\UserManager;

header('Content-Type: application/json');

$requestType = $_SERVER['REQUEST_METHOD'];
$managerMessage = new MessageManager();
$managerUser = new UserManager();


switch($requestType) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));
        sendPrivateMessage($data->user,$data->message, $managerMessage);
        break;
    case 'GET':
        if(isset($_GET["showMessage"]) && $_GET["showMessage"] === "1"){
            if(isset($_GET["id"])){
                echo getPrivateMessage(intval($_GET["id"]),$managerMessage, $managerUser);
            }
        }
        break;
    default:
        break;
}

//Send private message from session user to an other user
/**
 * @param int $user2
 * @param string $message
 * @param MessageManager $managerMessage
 */
function sendPrivateMessage(int $user2,string $message, MessageManager $managerMessage){
    $managerMessage->sendPrivateMessage($_SESSION["user"], $user2, $message);
}

/**
 * @param int $id
 * @param MessageManager $manager
 * @param UserManager $managerUser
 * @return false|string
 */
function getPrivateMessage(int $id, MessageManager $manager, UserManager $managerUser){
    $messages = $manager->getPrivateMessage($_SESSION["user"], $id);
    //Get user name and set sent bool to organise message in css
    for($index = 0; $index < count($messages); $index ++){
        if($messages[$index]["user1_id"] === strval($_SESSION["user"])){
            $messages[$index]["user1_id"] = $managerUser->getUserById(intval($_SESSION["user"]))->getName();
            $messages[$index]["user2_id"] = $managerUser->getUserById(intval($messages[$index]["user2_id"]))->getName();
            $messages[$index]["sent"] = true;
        }
        else{
            $messages[$index]["user1_id"] = $managerUser->getUserById(intval($messages[$index]["user1_id"]))->getName();
            $messages[$index]["user2_id"] = $managerUser->getUserById(intval($_SESSION["user"]))->getName();
            $messages[$index]["sent"] = false;
        }
    }
    return json_encode($messages);
}

exit;

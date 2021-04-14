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
    case 'GET':
        if(isset($_GET)){
            if( isset($_GET["getUser"]) && $_GET["getUser"] === "1"){
                echo sendUserSession();
            }
            elseif(isset($_GET["showMessage"]) && $_GET["showMessage"] === "1"){
                if(isset($_GET["id"])){
                    echo getPrivateMessage(intval($_GET["id"]),$managerMessage, $managerUser);
                }
            }
            else{
                echo getMessages($managerMessage, $managerUser);
            }
        }

        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));
        sendMessage($managerMessage,$data->user,$data->message);
        break;
    default:
        break;
}

/**
 * @param MessageManager $managerMessage
 * @param UserManager $managerUser
 * @return string
 */
function getMessages(MessageManager $managerMessage, UserManager $managerUser): string {
    $response = [];
    // Obtention des students.
    $data = $managerMessage->getMessages();
    foreach($data as $message) {
        $user = getUser($managerMessage, $message->getId(), $managerUser);
        /* @var Message $message*/
        $response[] = [
            'id' => $message->getId(),
            'message' => $message->getMessage(),
            'date' => $message->getDate(),
            'user' => $user->getName(),
            'user_id' => $user->getId()
        ];
    }
    // Envoi de la réponse ( on encode notre tableau au format json ).
    return json_encode($response);
}

function getUser(MessageManager $managerMessage, int $message_id, UserManager $managerUser){
    $id = $managerMessage->getUserFk($message_id);
    if(!is_null($id)){
        $user = $managerUser->getUserById($id);
        return $user;
    }
    return "User inconnue";
}

function sendMessage(MessageManager $managerMessage, int $id, string $message){
    $managerMessage->sendMessages($message, $id);
}

function sendUserSession(){
    return json_encode(["user" => $_SESSION["user"]]);
}

function getPrivateMessage(int $id, MessageManager $manager, UserManager $managerUser){
    $messages = $manager->getPrivateMessage($_SESSION["user"], $id);
    for($index = 0; $index < count($messages); $index ++){
        if($messages[$index]["user1_id"] === strval($_SESSION["user"])){
            $messages[$index]["user1_id"] = $managerUser->getUserById(intval($_SESSION["user"]))->getName();
            $messages[$index]["user2_id"] = $managerUser->getUserById(intval($messages[$index]["user2_id"]))->getName();
            $messages[$index]["sended"] = true;
        }
        else{
            $messages[$index]["user1_id"] = $managerUser->getUserById(intval($messages[$index]["user1_id"]))->getName();
            $messages[$index]["user2_id"] = $managerUser->getUserById(intval($_SESSION["user"]))->getName();
            $messages[$index]["sended"] = false;
        }
    }
    return json_encode($messages);
}

exit;

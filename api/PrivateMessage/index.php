<?php

session_start();


require_once $_SERVER['DOCUMENT_ROOT'] . '/Classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Entity/Message.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Manager/MessageManager.php';

use App\Entity\Message;
use App\Manager\MessageManager;
use App\Entity\User;
use App\Manager\UserManager;

header('Content-Type: application/json');

$requestType = $_SERVER['REQUEST_METHOD'];
$managerMessage = new MessageManager();

switch($requestType) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));
        sendPrivateMessage($data->user,$data->message, $managerMessage);
        break;
    default:
        break;
}

function sendPrivateMessage(int $user2,string $message, MessageManager $managerMessage){
    $managerMessage->sendPrivateMessage($_SESSION["user"], $user2, $message);
}

exit;

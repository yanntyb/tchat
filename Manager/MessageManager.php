<?php

namespace App\Manager;

use App\Classes\DB;
use App\Entity\Message;

use PDO;

class MessageManager
{
    private ?PDO $db;

    /**
     * ArticleManager constructor.
     */
    public function __construct(){
        $this->db = DB::getInstance();
    }
    
    public function getMessages(){
        $conn = $this->db->prepare("SELECT * FROM message");
        $messages = [];
        if($conn->execute()){
            foreach ($conn->fetchAll() as $select){
                $message = new Message();
                $message
                    ->setMessage($select["message"])
                    ->setId($select["id"])
                    ->setDate($select["date"]);
                $messages[] = $message;
            }
        }
        return $messages;
    }

    public function getUserFk(int $id){
        $conn = $this->db->prepare("SELECT message_user.user_fk From message_user INNER JOIN message ON message.id = message_user.message_fk");
        if($conn->execute()){
            return $conn->fetch()["user_fk"];
        }
        return null;
    }

    public function sendMessages(string $messageContent, int $id_user){
        $message = new Message();
        $message->setMessage($messageContent);
        $conn = $this->db->prepare("INSERT INTO message (message, date) VALUES (:message, :date)");
        $conn->bindValue(":message", $message->getMessage());
        $conn->bindValue(":date", $message->getDate());
        $conn->execute();
        $id = $this->db->lastInsertId();

        $conn = $this->db->prepare("INSERT INTO message_user (user_fk, message_fk) VALUES (:user, :message)");
        $conn->bindValue("user", $id_user);
        $conn->bindValue(":message", $id);
        $conn->execute();

    }
}
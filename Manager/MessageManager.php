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
        $conn = $this->db->prepare("SELECT message_user.user_fk From message_user WHERE message_user.message_fk = :id");
        $conn->bindValue(":id", $id);
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
        $conn->bindValue(":user", $id_user);
        $conn->bindValue(":message", $id);
        $conn->execute();

    }

    public function getPrivateMessage(int $user1, int $user2){
        $conn = $this->db->prepare("SELECT * FROM private_message INNER JOIN private_message_user ON private_message.id = private_message_user.message_id  WHERE (private_message_user.user1_id = :id1 AND private_message_user.user2_id = :id2) OR (private_message_user.user2_id = :id1 AND private_message_user.user1_id = :id2) ");
        $conn->bindValue(":id1", $user1);
        $conn->bindValue(":id2", $user2);
        $messages = [];
        if($conn->execute()){
            foreach($conn->fetchAll() as $select){
                $messages[] = $select;
            }
        }
        return $messages;
    }

    public function sendPrivateMessage(int $user1, int $user2, string $message){
        $conn = $this->db->prepare("INSERT INTO private_message (message, date) VALUES (:message, :date)");
        $conn->bindValue(":message", $message);
        $conn->bindValue(":date", date('l jS \of F Y h:i:s A'));
        $conn->execute();
        $id = $this->db->lastInsertId();
        //Need lastInsertedId because there is a join table
        $conn = $this->db->prepare("INSERT INTO private_message_user (user1_id, user2_id, message_id) VALUES (:user1, :user2, :id)");
        $conn->bindValue(":user1", $user1);
        $conn->bindValue(":user2", $user2);
        $conn->bindValue(":id", $id);
        $conn->execute();
    }
}
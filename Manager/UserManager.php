<?php


namespace App\Manager;

use App\Classes\DB;
use App\Entity\User;

use PDO;

class UserManager
{
    private ?PDO $db;

    /**
     * ArticleManager constructor.
     */
    public function __construct(){
        $this->db = DB::getInstance();
    }

    public function getUserById(int $id){
        $conn = $this->db->prepare("SELECT * FROM user WHERE id = :id");
        $conn->bindValue(":id", $id);
        $user = new User();
        if($conn->execute()){
            $select = $conn->fetch();
            if(!$select){
                $user
                    ->setName("User inconnue");
            }
            else {
                $user
                    ->setName($select["name"])
                    ->setId($id);
            }
        }
        return $user;
    }

    public function getUserByLog(string $mail, string $pass){
        $conn = $this->db->prepare("SELECT * FROM user WHERE mail = :mail");
        $conn->bindValue(":mail", $mail);
        $user = null;
        if($conn->execute()){
            $select = $conn->fetch();
            if(isset($select["mail"])){
                if($select["pass"] === $pass){
                    $user = new User();
                    $user
                        ->setName($select["name"])
                        ->setPass($select["pass"])
                        ->setId($select["id"]);
                }
            }
        }
        return $user;
    }

}
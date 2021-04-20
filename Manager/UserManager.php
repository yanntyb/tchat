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

    /**
     * @param int $id
     * @return User
     */
    public function getUserById(int $id): User{
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

    //Used for connection to check if an email match with a password

    /**
     * @param string $mail
     * @param string $pass
     * @return User
     */
    public function getUserByLog(string $mail, string $pass): User{
        $conn = $this->db->prepare("SELECT * FROM user WHERE mail = :mail");
        $conn->bindValue(":mail", $mail);
        $user = null;
        if($conn->execute()){
            $select = $conn->fetch();
            if(isset($select["mail"])){
                if(password_verify($pass ,$select["pass"])){
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

    /**
     * @param string $mail
     * @param string $pass
     * @param string $name
     */
    public function insertUser(string $mail, string $pass, string $name): void{
        $conn = $this->db->prepare("INSERT INTO user (name, pass, mail) VALUES (:name, :pass, :mail)");
        $conn->bindValue(":name", $name);
        $conn->bindValue(":pass", $pass,);
        $conn->bindValue(":mail", $mail);
        $conn->execute();
    }

}
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
                    ->setName($select["name"]);
            }
        }
        return $user;
    }
}
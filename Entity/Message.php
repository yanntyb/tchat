<?php

namespace App\Entity;

class Message
{
    private ?string $message;
    private ?int $id;
    private ?string $date;

    public function __construct(string $message = null, string $date = null, int $id= null){
        date_default_timezone_set('UTC');
        $this->message = $message;
        $this->date = date('l jS \of F Y h:i:s A');
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function setMessage(?string $message): Message
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id): Message
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return false|string|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param $date
     * @return $this
     */
    public function setDate($date): Message
    {
        $this->date = $date;
        return $this;
    }
    
    
}
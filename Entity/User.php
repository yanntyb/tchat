<?php


namespace App\Entity;


class User
{
    private ?int $id;
    private ?string $name;
    private ?string $pass;

    public function __construct(int $id = null, string $name = null, string $pass = null){
        $this->id = $id;
        $this->name = $name;
        $this->pass = $pass;
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
    public function setId(?int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPass(): ?string
    {
        return $this->pass;
    }

    /**
     * @param string|null $pass
     * @return $this
     */
    public function setPass(?string $pass): User
    {
        $this->pass = $pass;
        return $this;
    }
}
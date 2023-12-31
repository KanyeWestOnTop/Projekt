<?php

namespace App\Model;

use App\Gateway\UserGateway;

class User
{
    private int $id = 0;
    private string $prename = "";
    private string $lastname = "";
    private string $email = "";
    private string $password = "";

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPrename(): string
    {
        return $this->prename;
    }

    public function setPrename(string $prename)
    {
        $this->prename = $prename;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getName(): string
    {
        return $this->prename . " " . $this->lastname;
    }


    public function save()
    {
        $gateway = new UserGateway();
        if ($this->id > 0) {
            $gateway->update($this->id, $this->getAttributAsAssociativeArray());
        } else {
            $this->id = $gateway->insert($this->getAttributAsAssociativeArray());
        }
    }


    private static function create(array $tmpUser): User
    {
        $user = new User();
        $user->id = $tmpUser["id"];
        $user->email = $tmpUser["email"];
        $user->password = $tmpUser["password"];
        $user->lastname = $tmpUser["lastname"];
        $user->prename = $tmpUser["prename"];

        return $user;
    }


    public static function all(): array
    {
        $gateway = new UserGateway();
        $users = [];

        $dbUsers = $gateway->all();

        foreach ($dbUsers as $dbUser) {
            $user = new User();
            $user->id = $dbUser["id"];
            $user->prename = $dbUser["prename"];
            $user->lastname = $dbUser["lastname"];
            $user->email = $dbUser["email"];
            $user->password = $dbUser["password"];
            $users[] = $user;
        }

        return $users;
    }

    public function delete()
    {
        $gateway = new UserGateway();
        $gateway->delete($this->id);
    }

    public static function findByEmailAndPassword(string $email, string $password): ?User
    {
        $gateway = new UserGateway();

        $tmpUser = $gateway->findByFields([
            "email" => $email
        ]);

        $user = null;

        if (count($tmpUser) === 1) {
            $userArray = $tmpUser[0];

            if (password_verify($password, $userArray["password"])) {
                $user = new User();
                $user->id = $userArray["id"];
                $user->prename = $userArray["prename"];
                $user->lastname = $userArray["lastname"];
                $user->email = $userArray["email"];
            }
        }
        return $user;
    }

    public static function findById(int $id): ?User
    {
        $gateway = new UserGateway();
        $tmpUser = $gateway->findById($id);
        $user = null;

        if ($tmpUser) {
            $user = self::create($tmpUser);
        }

        return $user;
    }

    private function getAttributAsAssociativeArray(): array
    {
        return [
            "prename" => $this->prename,
            "lastname" => $this->lastname,
            "email" => $this->email,
            "password" => $this->password
        ];
    }
}

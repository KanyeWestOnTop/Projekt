<?php
namespace App\Model;
use App\Gateway\UserGateway;
class User {
    private int $id;
    private string $email;
    private string $password;
    private string $prename;
    private string $lastname;

    public function getId(): int {
        return $this->id;
    }
    public function setEmail(string $email) {
        $this->email = $email;
    }
    public function getEmail(): string {
        return $this->email;
    }
    public function setPassword(string $password) {
        $this->password = $password;
    }
    public function getPassword(): string {
        return $this->password;
    }
    public function setPrename(string $prename) {
        $this->prename = $prename;
    }
    public function getPrename(): string {
        return $this->prename;
    }
    public function setLastname(string $lastname) {
        $this->lastname = $lastname;
    }
    public function getLastname(): string {
        return $this->lastname;
    }
    public static function all(): array {
        $gateway = new UserGateway();
        $user = [];
        $dbUsers = $gateway->all();
        foreach ($dbUsers as $dbUser) {
            $user = new user();
            $user->id = $dbUser["id"];
            $user->setEmail($dbUser["email"]);
            $user->setPassword($dbUser["password"]);
            $user->setPrename($dbUser["prename"]);
            $user->setLastname($dbUser["lastname"]);

            $users[] = $user;
        }
        return $users;
    }

    public static function find(int $id): User {
        $gateway = new UserGateway();
        $dbUser = $gateway->find($id);
        $user = new User();
        $user->id = $dbUser["id"];
        $user->setEmail($dbUser["email"]);
        $user->setPassword($dbUser["password"]);
        $user->setPrename($dbUser["prename"]);
        $user->setLastname($dbUser["lastname"]);
        return $user;
    }

    public function save() {
        $gateway = new UserGateway();
        $gateway->save($this);
    }

    public function delete() {
        $gateway = new UserGateway();
        $gateway->delete($this);
    }

    public function update() {
        $gateway = new UserGateway();
        $gateway->update($this);
    }

    


}
?>
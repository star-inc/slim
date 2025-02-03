<?php

namespace Slim\Models;

use InvalidArgumentException;
use PDO;
use Slim\Kernel\Database;
use TypeError;

class User extends ModelBase implements ModelInterface
{
    public string $uuid;
    public string $username;
    public string $password;
    public int $level;
    public string $display_name;
    public int $created_time;
    public string $address;
    public string $email;
    public string $phone;

    use ModelUtils;

    public function checkReady(): bool
    {
        return isset($this->uuid);
    }

    public static function batch(Database $db_instance): array
    {
        $sql = "
            SELECT `uuid`, `username`, `password`, `level`, `display_name`, `created_time`, `address`, `email`, `phone`
            FROM `users`
            ORDER BY `display_name` DESC
        ";
        $stmt = $db_instance->getClient()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function (array $item) {
            $user = new static();
            $user->fromArray($item);
            return $user;
        }, $result);
    }

    public function load(Database $db_instance, $filter): ModelInterface
    {
        if (!is_string($filter)) {
            throw new TypeError();
        }
        $sql = "SELECT `uuid`, `username`, `password`, `level`, `display_name`, `created_time`, `address`, `email`, `phone` FROM `users` WHERE `uuid` = ?";
        $stmt = $db_instance->getClient()->prepare($sql);
        $stmt->execute([$filter]);
        $this->loadResult($this, $stmt);
        return $this;
    }

    public function loadFromUsernameAndPassword(Database $db_instance): ModelInterface
    {
        $sql = "SELECT `uuid`, `username`, `password`, `level`, `display_name`, `created_time`, `address`, `email`, `phone` FROM `users` WHERE `username` = ? AND `password` = ?";
        $stmt = $db_instance->getClient()->prepare($sql);
        $stmt->execute([$this->username, $this->password]);
        $this->loadResult($this, $stmt);
        return $this;
    }

    public function reload(Database $db_instance): ModelInterface
    {
        return $this->load($db_instance, $this->uuid);
    }

    public function create(Database $db_instance): bool
    {
        $sql = "INSERT INTO `users` (`uuid`, `username`, `password`, `level`, `display_name`, `created_time`, `address`, `email`, `phone`) VALUES (:uuid, :username, :password, :level, :display_name, UNIX_TIMESTAMP(), :address, :email, :phone)";
        $stmt = $db_instance->getClient()->prepare($sql);
        $db_instance->bindParamsFilled($stmt, $this->toArray());
        return $stmt->execute();
    }

    public function replace(Database $db_instance): bool
    {
        $sql = "UPDATE `users` SET `username` = :username, `password` = :password, `level` = :level, `display_name` = :display_name, `address` = :address, `email` = :email, `phone` = :phone WHERE  `uuid` = :uuid";
        $stmt = $db_instance->getClient()->prepare($sql);
        $db_instance->bindParamsFilled($stmt, $this->toArray());
        return $stmt->execute();
    }

    public function destroy(Database $db_instance): bool
    {
        $sql = "DELETE FROM `users` WHERE `uuid` = ?";
        $stmt = $db_instance->getClient()->prepare($sql);
        return $stmt->execute([$this->uuid]);
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return User
     */
    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param int $level
     * @return User
     */
    public function setLevel(int $level): static
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @param string $display_name
     * @return User
     */
    public function setDisplayName(string $display_name): static
    {
        $this->display_name = $display_name;
        return $this;
    }

    /**
     * @param string $address
     * @return User
     */
    public function setAddress(string $address): static
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $phone
     * @return User
     */
    public function setPhone(string $phone): static
    {
        if (!is_numeric($phone)) {
            throw new InvalidArgumentException();
        }
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return User
     */
    public function hashPassword(): static
    {
        $this->password = hash("sha256", $this->password);
        return $this;
    }

    public function jsonSerialize(): ?array
    {
        $result = $this->toArray();
        unset($result["password"]);
        return !empty($result) ? $result : null;
    }
}

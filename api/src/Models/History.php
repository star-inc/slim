<?php

namespace Slim\Models;

use PDO;
use Slim\Kernel\Database;
use TypeError;

class History extends ModelBase implements ModelInterface
{
    public int $_id;
    public string $user;
    public string $method;
    public string $resource;

    use ModelUtils;

    public function checkReady(): bool
    {
        return isset($this->_id);
    }

    public static function batch(Database $db_instance): array
    {
        $sql = "
            SELECT `_id`, `created_time`, `user`, `method`, `resource`
            FROM `histories`
            ORDER BY `created_time` DESC
        ";
        $stmt = $db_instance->getClient()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function (array $item) {
            $history = new static();
            $history->fromArray($item);
            return $history;
        }, $result);
    }

    public function load(Database $db_instance, $filter): ModelInterface
    {
        if (!is_int($filter)) {
            throw new TypeError();
        }
        $sql = "SELECT `_id`, `created_time`, `user`, `method`, `resource` FROM `histories` WHERE `_id` = ?";
        $stmt = $db_instance->getClient()->prepare($sql);
        $stmt->execute([$filter]);
        $this->loadResult($this, $stmt);
        return $this;
    }

    public function reload(Database $db_instance): ModelInterface
    {
        return $this->load($db_instance, $this->_id);
    }

    public function create(Database $db_instance): bool
    {
        $sql = "INSERT INTO `histories`(`created_time`, `user`, `method`, `resource`) VALUES (UNIX_TIMESTAMP(), :user, :method, :resource)";
        $stmt = $db_instance->getClient()->prepare($sql);
        $db_instance->bindParamsFilled($stmt, $this->toArray());
        $status = $stmt->execute();
        $this->setId($db_instance->getClient()->lastInsertId());
        return $status;
    }

    public function replace(Database $db_instance): bool
    {
        $sql = "UPDATE `histories` SET `user` = :user, `method` = :method, `resource` = :resource WHERE `_id` = :id";
        $stmt = $db_instance->getClient()->prepare($sql);
        $db_instance->bindParamsFilled($stmt, $this->toArray());
        return $stmt->execute();
    }

    public function destroy(Database $db_instance): bool
    {
        $sql = "DELETE FROM `histories` WHERE `_id` = ?";
        $stmt = $db_instance->getClient()->prepare($sql);
        return $stmt->execute([$this->_id]);
    }

    /**
     * @param int $id
     * @return History
     */
    public function setId(int $id): static
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @param string $user
     * @return History
     */
    public function setUser(string $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param string $method
     * @return History
     */
    public function setMethod(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param string $resource
     * @return History
     */
    public function setResource(string $resource): static
    {
        $this->resource = $resource;
        return $this;
    }
}

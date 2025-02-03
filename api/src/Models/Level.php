<?php

namespace Slim\Models;

use PDO;
use Slim\Kernel\Database;
use TypeError;

class Level extends ModelBase implements ModelInterface
{
    public int $_id;
    public string $display_name;

    use ModelUtils;

    public function checkReady(): bool
    {
        return isset($this->_id);
    }

    public static function batch(Database $db_instance): array
    {
        $sql = "
            SELECT `_id`, `display_name`
            FROM `levels`
            ORDER BY `display_name` DESC
        ";
        $stmt = $db_instance->getClient()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function (array $item) {
            $level = new static();
            $level->fromArray($item);
            return $level;
        }, $result);
    }

    public function load(Database $db_instance, $filter): ModelInterface
    {
        if (!is_int($filter)) {
            throw new TypeError();
        }
        $sql = "SELECT `_id`, `display_name` FROM `levels` WHERE `_id` = ?";
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
        $sql = "INSERT INTO `levels`(`display_name`) VALUES (:display_name)";
        $stmt = $db_instance->getClient()->prepare($sql);
        $db_instance->bindParamsFilled($stmt, $this->toArray());
        $status = $stmt->execute();
        $this->setId($db_instance->getClient()->lastInsertId());
        return $status;
    }

    public function replace(Database $db_instance): bool
    {
        $sql = "UPDATE `levels` SET `display_name` = :display_name WHERE `_id` = :id";
        $stmt = $db_instance->getClient()->prepare($sql);
        $db_instance->bindParamsFilled($stmt, $this->toArray());
        return $stmt->execute();
    }

    public function destroy(Database $db_instance): bool
    {
        $sql = "DELETE FROM `levels` WHERE `_id` = ?";
        $stmt = $db_instance->getClient()->prepare($sql);
        return $stmt->execute([$this->_id]);
    }

    /**
     * @param int $id
     * @return Level
     */
    public function setId(int $id): static
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @param string $display_name
     * @return Level
     */
    public function setDisplayName(string $display_name): static
    {
        $this->display_name = $display_name;
        return $this;
    }
}

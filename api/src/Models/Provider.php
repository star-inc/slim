<?php

namespace Slim\Models;

use PDO;
use Slim\Kernel\Database;
use TypeError;

class Provider extends ModelBase implements ModelInterface
{
    public int $_id;
    public string $display_name;
    public string $contact_name;
    public string $contact_phone;
    public string $contact_address;

    use ModelUtils;

    public function checkReady(): bool
    {
        return isset($this->_id);
    }

    public static function batch(Database $db_instance): array
    {
        $sql = "
            SELECT `_id`, `display_name`, `contact_name`, `contact_phone`, `contact_address`
            FROM `providers`
            ORDER BY `display_name` DESC
        ";
        $stmt = $db_instance->getClient()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function (array $item) {
            $provider = new static();
            $provider->fromArray($item);
            return $provider;
        }, $result);
    }

    public function load(Database $db_instance, $filter): ModelInterface
    {
        if (!is_int($filter)) {
            throw new TypeError();
        }
        $sql = "SELECT `_id`, `display_name`, `contact_name`, `contact_phone`, `contact_address` FROM `providers` WHERE `_id` = ?";
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
        $sql = "INSERT INTO `providers` (`display_name`, `contact_name`, `contact_phone`, `contact_address`) VALUES (:display_name, :contact_name, :contact_phone, :contact_address)";
        $stmt = $db_instance->getClient()->prepare($sql);
        $db_instance->bindParamsFilled($stmt, $this->toArray());
        $status = $stmt->execute();
        $this->setId($db_instance->getClient()->lastInsertId());
        return $status;
    }

    public function replace(Database $db_instance): bool
    {
        $sql = "UPDATE `providers` SET `display_name` = :display_name, `contact_name` = :contact_name, `contact_phone` = :contact_phone, `contact_address` = :contact_address WHERE `_id` = :id";
        $stmt = $db_instance->getClient()->prepare($sql);
        $db_instance->bindParamsFilled($stmt, $this->toArray());
        return $stmt->execute();
    }

    public function destroy(Database $db_instance): bool
    {
        $sql = "DELETE FROM `providers` WHERE `_id` = ?";
        $stmt = $db_instance->getClient()->prepare($sql);
        return $stmt->execute([$this->_id]);
    }

    /**
     * @param int $id
     * @return Provider
     */
    public function setId(int $id): static
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @param string $display_name
     * @return Provider
     */
    public function setDisplayName(string $display_name): static
    {
        $this->display_name = $display_name;
        return $this;
    }

    /**
     * @param string $contact_name
     * @return Provider
     */
    public function setContactName(string $contact_name): static
    {
        $this->contact_name = $contact_name;
        return $this;
    }

    /**
     * @param string $contact_phone
     * @return Provider
     */
    public function setContactPhone(string $contact_phone): static
    {
        $this->contact_phone = $contact_phone;
        return $this;
    }

    /**
     * @param string $contact_address
     * @return Provider
     */
    public function setContactAddress(string $contact_address): static
    {
        $this->contact_address = $contact_address;
        return $this;
    }
}

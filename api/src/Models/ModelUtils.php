<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

namespace Slim\Models;

use PDO;
use PDOStatement;
use Slim\Kernel\DuplicateResultException;

trait ModelUtils
{
    public function loadResult(ModelInterface $model, PDOStatement $stmt): ModelInterface
    {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) > 1) {
            throw new DuplicateResultException();
        }
        if (count($result) === 1) {
            $model->fromArray($result[0]);
        }
        return $model;
    }
}
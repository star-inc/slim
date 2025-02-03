<?php

namespace Slim\Controllers;

use PDOException;
use Slim\Kernel\Context;
use Slim\Models\Level as LevelModel;
use Slim\Models\User;

final class Level implements ControllerInterface
{
    public function getAll(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof User)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        $levels = (new LevelModel())->batch($context->getDatabase());
        $context->getResponse()->setBody($levels)->sendJSON();
    }

    public function getOne(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof User)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        if (empty($uuid = $context->getRequest()->getQuery("uuid"))) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        $level = (new LevelModel())->load($context->getDatabase(), $uuid);
        if ($level->checkReady()) {
            $context->getResponse()->setBody($level)->sendJSON();
        } else {
            $context->getResponse()->setStatus(404)->setBody(["message" => "not found"])->sendJSON();
        }
    }

    public function postOne(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof User)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        $form = $context->getRequest()->read();
        if (!isset($form['display_name'])) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        $level = new LevelModel();
        $level->setDisplayName($form['display_name']);
        try {
            $level->create($context->getDatabase());
            $context->getResponse()->setStatus(201)->send();
         } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $context->getResponse()->setStatus(409)->setBody(["message" => "conflict"])->sendJSON();
            } else {
                error_log($e->getMessage());
                $context->getResponse()->setStatus(500)->setBody(["message" => "internal server error"])->sendJSON();
            }
         }
    }

    public function putOne(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof User)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        $form = $context->getRequest()->read();
        if (!isset($form['id']) || !isset($form['display_name'])) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        $level = new LevelModel();
        $level
            ->setId(intval($form['id']))
            ->setDisplayName($form['display_name'])
            ->replace($context->getDatabase());
        $context->getResponse()->setStatus(204)->send();
    }

    public function deleteOne(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof User)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        if (empty($id = $context->getRequest()->getQuery("id"))) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        (new LevelModel())->setId($id)->destroy($context->getDatabase());
        $context->getResponse()->setStatus(204)->send();
    }
}

<?php

namespace Slim\Controllers;

use Slim\Kernel\Context;
use Slim\Models\User as UserModel;

final class User implements ControllerInterface
{
    public function getAll(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof UserModel)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        $users = (new UserModel())->batch($context->getDatabase());
        $context->getResponse()->setBody($users)->sendJSON();
    }

    public function getOne(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof UserModel)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        if (empty($uuid = $context->getRequest()->getQuery("uuid"))) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        $user = (new UserModel())->load($context->getDatabase(), $uuid);
        if ($user->checkReady()) {
            $context->getResponse()->setBody($user)->sendJSON();
        } else {
            $context->getResponse()->setStatus(404)->setBody(["message" => "not found"])->sendJSON();
        }
    }

    public function postOne(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof UserModel)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        $form = $context->getRequest()->read();
        if (
            !isset($form['username']) ||
            !isset($form["password"]) ||
            !isset($form["level"]) ||
            !isset($form["display_name"]) ||
            !isset($form["address"]) ||
            !isset($form["email"]) ||
            !isset($form["phone"])
        ) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        $user = new UserModel();
        $user
            ->setUuid($context->getDatabase()->guidV4())
            ->setUsername($form["username"])
            ->setPassword($form["password"])
            ->setLevel(intval($form["level"]))
            ->setDisplayName($form['display_name'])
            ->setAddress($form["address"])
            ->setEmail($form["email"])
            ->setPhone($form["phone"])
            ->hashPassword()
            ->create($context->getDatabase());
        if ($user->reload($context->getDatabase())->checkReady()) {
            $context->getResponse()->setStatus(201)->send();
        } else {
            $context->getResponse()->setStatus(500)->setBody(["message" => "internal server error"])->sendJSON();
        }
    }

    public function putOne(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof UserModel)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        $form = $context->getRequest()->read();
        if (
            !isset($form['uuid']) ||
            !isset($form['username']) ||
            !isset($form["password"]) ||
            !isset($form["level"]) ||
            !isset($form["display_name"]) ||
            !isset($form["address"]) ||
            !isset($form["email"]) ||
            !isset($form["phone"])
        ) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        $user = new UserModel();
        $user
            ->setUuid($form["uuid"])
            ->setUsername($form["username"])
            ->setLevel(intval($form["level"]))
            ->setDisplayName($form['display_name'])
            ->setAddress($form["address"])
            ->setEmail($form["email"])
            ->setPhone($form["phone"]);
        if (!empty($form["password"])) {
            $user->setPassword($form["password"])->hashPassword();
        }
        $user->replace($context->getDatabase());
        if ($user->reload($context->getDatabase())->checkReady()) {
            $context->getResponse()->setStatus(204)->send();
        } else {
            $context->getResponse()->setStatus(500)->setBody(["message" => "internal server error"])->sendJSON();
        }
    }

    public function deleteOne(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof UserModel)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        if (empty($uuid = $context->getRequest()->getQuery("uuid"))) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        (new UserModel())->setUuid($uuid)->destroy($context->getDatabase());
        $context->getResponse()->setStatus(204)->send();
    }
}

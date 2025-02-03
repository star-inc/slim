<?php

namespace Slim\Controllers;

use Slim\Kernel\Context;
use Slim\Models\User;

final class Authentic implements ControllerInterface
{
    public function getSession(Context $context): void
    {
        $user = $context->getState()->get("user");
        if ($user instanceof User && $user->checkReady()) {
            $context->getResponse()->setStatus(200)->setBody($user)->sendJSON();
        } else {
            $context->getResponse()->setStatus(401)->setBody(["message" => "unauthorized"])->sendJSON();
        }
    }

    public function postSession(Context $context): void
    {
        $form = $context->getRequest()->read();
        if (!isset($form['username']) || !isset($form['password'])) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        $user = new User();
        $user
            ->setUsername($form['username'])
            ->setPassword($form['password'])
            ->hashPassword()
            ->loadFromUsernameAndPassword($context->getDatabase());
        if ($user->checkReady()) {
            $context->getSession()->set("user_id", $user->getUuid());
            $context->getResponse()->setStatus(201)->send();
        } else {
            $context->getResponse()->setStatus(401)->setBody(["message" => "unauthorized"])->sendJSON();
        }
    }

    public function deleteSession(Context $context): void
    {
        $user = $context->getState()->get("user");
        if ($user instanceof User && $user->checkReady()) {
            $context->getSession()->del("user_id");
            $context->getResponse()->setStatus(204)->send();
        } else {
            $context->getResponse()->setStatus(401)->setBody(["message" => "unauthorized"])->sendJSON();
        }
    }
}

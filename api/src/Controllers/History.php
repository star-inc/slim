<?php

namespace Slim\Controllers;

use Slim\Kernel\Context;
use Slim\Models\History as HistoryModel;
use Slim\Models\User;

final class History implements ControllerInterface
{
    public function getAll(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof User)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        $products = (new HistoryModel())->batch($context->getDatabase());
        $context->getResponse()->setBody($products)->sendJSON();
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
        $product = (new HistoryModel())->load($context->getDatabase(), $uuid);
        if ($product->checkReady()) {
            $context->getResponse()->setBody($product)->sendJSON();
        } else {
            $context->getResponse()->setStatus(404)->setBody(["message" => "not found"])->sendJSON();
        }
    }
}

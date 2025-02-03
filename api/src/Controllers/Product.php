<?php

namespace Slim\Controllers;

use Slim\Kernel\Context;
use Slim\Models\Product as ProductModel;
use Slim\Models\User;

final class Product implements ControllerInterface
{
    public function getAll(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof User)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        $products = (new ProductModel())->batch($context->getDatabase());
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
        $product = (new ProductModel())->load($context->getDatabase(), $uuid);
        if ($product->checkReady()) {
            $context->getResponse()->setBody($product)->sendJSON();
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
        if (
            !isset($form['display_name']) ||
            !isset($form["cost_value"]) ||
            !isset($form['sell_value']) ||
            !isset($form["remain_amount"])
        ) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        $product = new ProductModel();
        $product
            ->setUuid($context->getDatabase()->guidV4())
            ->setDisplayName($form['display_name'])
            ->setCostValue(intval($form["cost_value"]))
            ->setSellValue(intval($form["sell_value"]))
            ->setRemainAmount(intval($form["remain_amount"]))
            ->create($context->getDatabase());
        if ($product->reload($context->getDatabase())->checkReady()) {
            $context->getResponse()->setStatus(201)->send();
        } else {
            $context->getResponse()->setStatus(500)->setBody(["message" => "internal server error"])->sendJSON();
        }
    }

    public function putOne(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof User)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        $form = $context->getRequest()->read();
        if (
            !isset($form['uuid']) ||
            !isset($form['display_name']) ||
            !isset($form["cost_value"]) ||
            !isset($form['sell_value']) ||
            !isset($form["remain_amount"])
        ) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        $product = new ProductModel();
        $product
            ->setUuid($form['uuid'])
            ->setDisplayName($form['display_name'])
            ->setCostValue(intval($form["cost_value"]))
            ->setSellValue(intval($form["sell_value"]))
            ->setRemainAmount(intval($form["remain_amount"]))
            ->replace($context->getDatabase());
        if ($product->reload($context->getDatabase())->checkReady()) {
            $context->getResponse()->setStatus(204)->send();
        } else {
            $context->getResponse()->setStatus(500)->setBody(["message" => "internal server error"])->sendJSON();
        }
    }

    public function deleteOne(Context $context): void
    {
        if (!($context->getState()->get("user") instanceof User)) {
            $context->getResponse()->setStatus(403)->setBody(["message" => "forbidden"])->sendJSON();
            return;
        }
        if (empty($uuid = $context->getRequest()->getQuery("uuid"))) {
            $context->getResponse()->setStatus(400)->setBody(["message" => "bad request"])->sendJSON();
            return;
        }
        (new ProductModel())->setUuid($uuid)->destroy($context->getDatabase());
        $context->getResponse()->setStatus(204)->send();
    }
}

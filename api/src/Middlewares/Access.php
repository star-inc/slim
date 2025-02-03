<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

namespace Slim\Middlewares;

use Slim\Kernel\Context;
use Slim\Models\User;

class Access implements MiddlewareInterface
{
    public static function toUse(Context $context): void
    {
        if (is_null($uuid = $context->getSession()->get("user_id"))) return;
        $user = new User();
        $user->load($context->getDatabase(), $uuid);
        if ($user->checkReady()) {
            $context->getState()->set("user", $user);
        }
    }
}

<?php
// Justin PHP Framework
// (c)2021 SuperSonic(https://randychen.tk)

namespace Slim\Kernel;

class Session
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function del(string $key): void
    {
        unset($_SESSION[$key]);
    }
}

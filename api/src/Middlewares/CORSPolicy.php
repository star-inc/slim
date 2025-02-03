<?php

namespace Slim\Middlewares;

interface CORSPolicy
{
    public function getAllowOrigin(): string;
    public function getAllowMethods(): array;
    public function getAllowHeaders(): array;
    public function getAllowCredentials(): bool;
}

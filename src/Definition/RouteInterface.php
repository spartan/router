<?php

namespace Spartan\Router\Definition;

/**
 * RouteInterface
 *
 * @package Spartan\Router
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
interface RouteInterface
{
    const FOUND              = 200;
    const NOT_FOUND          = 404;
    const METHOD_NOT_ALLOWED = 405;
    const ERROR              = 500;

    /**
     * @return int
     */
    public function status(): int;

    /**
     * @return mixed
     */
    public function handler();

    /**
     * @return mixed[]
     */
    public function params(): array;
}

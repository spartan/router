<?php

namespace Spartan\Router;

use Spartan\Router\Definition\RouteInterface;

/**
 * Route Router
 *
 * @package Spartan\Router
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
final class Route implements RouteInterface
{
    protected int $status;

    /**
     * @var mixed
     */
    protected $handler;

    /**
     * @var mixed[]
     */
    protected array $params;

    /**
     * Route constructor.
     *
     * @param int     $status
     * @param mixed   $handler
     * @param mixed[] $params
     */
    public function __construct(
        int $status,
        $handler,
        array $params = []
    ) {
        $this->status  = $status;
        $this->handler = $handler;
        $this->params  = $params;
    }

    /**
     * @inheritDoc
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function handler()
    {
        return $this->handler;
    }

    /**
     * @inheritDoc
     */
    public function params(): array
    {
        return $this->params;
    }
}

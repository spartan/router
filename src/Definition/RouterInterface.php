<?php

namespace Spartan\Router\Definition;

/**
 * RouterInterface
 *
 * @package Spartan\Router
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
interface RouterInterface
{
    /**
     * @param string          $name
     * @param string[]|string $methods
     * @param string          $pattern
     * @param mixed           $handler
     * @param mixed[]         $options
     *
     * @return $this
     */
    public function withRoute(string $name, $methods, string $pattern, $handler, array $options = []): self;

    /**
     * @param string $method
     * @param mixed  $uri
     *
     * @return RouteInterface
     */
    public function resolve(string $method, $uri): RouteInterface;

    /**
     * @param string  $name
     * @param mixed[] $params
     * @param mixed[] $query
     *
     * @return string
     */
    public function reverse(string $name, array $params = [], array $query = []): string;
}

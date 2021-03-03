<?php

namespace Spartan\Router;

use Spartan\Router\Definition\RouteInterface;
use Spartan\Router\Definition\RouterInterface;

/**
 * Router Facade
 *
 * @package Spartan\Router
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class Router
{
    /**
     * @return RouterInterface
     * @throws \ReflectionException
     * @throws \Spartan\Service\Exception\ContainerException
     * @throws \Spartan\Service\Exception\NotFoundException
     */
    public static function adapter(): RouterInterface
    {
        return container()->get('router');
    }

    /**
     * @param string  $name
     * @param mixed   $methods
     * @param string  $pattern
     * @param mixed   $handler
     * @param mixed[] $options
     *
     * @return RouterInterface
     * @throws \ReflectionException
     * @throws \Spartan\Service\Exception\ContainerException
     * @throws \Spartan\Service\Exception\NotFoundException
     */
    public static function withRoute(string $name, $methods, string $pattern, $handler, array $options = [])
    {
        return self::adapter()->withRoute($name, $methods, $pattern, $handler, $options);
    }

    /**
     * @param string       $method
     * @param mixed|string $uri
     *
     * @return RouteInterface
     * @throws \ReflectionException
     * @throws \Spartan\Service\Exception\ContainerException
     * @throws \Spartan\Service\Exception\NotFoundException
     */
    public static function resolve(string $method, $uri): RouteInterface
    {
        return self::adapter()->resolve($method, (string)$uri);
    }

    /**
     * @param string  $name
     * @param mixed[] $params
     * @param mixed[] $query
     *
     * @return string
     * @throws \ReflectionException
     * @throws \Spartan\Service\Exception\ContainerException
     * @throws \Spartan\Service\Exception\NotFoundException
     */
    public static function reverse(string $name, array $params = [], array $query = []): string
    {
        return self::adapter()->reverse($name, $params, $query);
    }
}

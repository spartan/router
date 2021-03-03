<?php

namespace Spartan\Router\Adapter;

use FastRoute\DataGenerator\GroupCountBased as Generator;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Psr\Http\Message\UriInterface;
use Spartan\Router\Definition\RouteInterface;
use Spartan\Router\Definition\RouterInterface;
use Spartan\Router\Route;

/**
 * FastRoute Adapter
 *
 * @package Spartan\Router
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class FastRoute implements RouterInterface
{
    const STATUS_MAP = [
        Dispatcher::FOUND              => RouteInterface::FOUND,
        Dispatcher::NOT_FOUND          => RouteInterface::NOT_FOUND,
        Dispatcher::METHOD_NOT_ALLOWED => RouteInterface::METHOD_NOT_ALLOWED,
    ];

    protected RouteCollector $collector;

    protected ?Dispatcher $dispatcher = null;

    public function __construct()
    {
        $this->collector = new RouteCollector(new Std, new Generator());
    }

    /**
     * @return Dispatcher
     */
    public function dispatcher(): Dispatcher
    {
        if (!$this->dispatcher) {
            $this->dispatcher = new Dispatcher($this->collector->getData());
        }

        return $this->dispatcher;
    }

    /**
     * @param string          $name
     * @param string|string[] $methods
     * @param string          $pattern
     * @param mixed           $handler
     * @param mixed[]         $options
     *
     * @return $this|RouterInterface
     */
    public function withRoute(string $name, $methods, string $pattern, $handler, array $options = []): RouterInterface
    {
        foreach ((array)$methods as $method) {
            $this->collector->addRoute($method, $pattern, $handler);
        }

        return $this;
    }

    /**
     * @param string $method
     * @param mixed  $uri
     *
     * @return RouteInterface
     */
    public function resolve(string $method, $uri): RouteInterface
    {
        $path = $uri instanceof UriInterface ? $uri->getPath() : (string)$uri;

        [$status, $handler, $params] = $this->dispatcher()->dispatch($method, $path);

        return new Route(
            self::STATUS_MAP[$status] ?? Route::ERROR,
            $handler,
            $params
        );
    }

    /**
     * @param string  $name
     * @param mixed[] $params
     * @param mixed[] $query
     *
     * @return string
     */
    public function reverse($name, array $params = [], array $query = []): string
    {
        throw new \BadMethodCallException('Reverse routing is not supported!');
    }
}

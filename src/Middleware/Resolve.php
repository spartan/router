<?php

namespace Spartan\Router\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spartan\Adr\Definition\ActionInterface;
use Spartan\Http\Exception\HttpNotFound;
use Spartan\Http\Exception\HttpNotImplemented;
use Spartan\Router\Router;

/**
 * Resolve Middleware
 *
 * @package Spartan\Router
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class Resolve implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws HttpNotFound
     * @throws HttpNotImplemented
     * @throws \ReflectionException
     * @throws \Spartan\Service\Exception\ContainerException
     * @throws \Spartan\Service\Exception\NotFoundException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = Router::adapter()->resolve($request->getMethod(), (string)$request->getUri()->getPath());

        container()->withBindings(['route' => $route]);

        if ($route->status() == 404) {
            throw new HttpNotFound();
        } elseif ($route->status() == 405) {
            throw new HttpNotImplemented();
        }

        foreach ($route->params() as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        /** @var ActionInterface|null $actionClass */
        $actionClass = self::actionClassFromRouteHandler($route->handler());
        if ($actionClass) {
            foreach ($actionClass::middleware() as $name => $priority) {
                $handler->queue()->insert($name, $priority);
            }
        }

        return $handler->handle($request);
    }

    /**
     * @param mixed $routeHandler
     *
     * @return string|null
     */
    public static function actionClassFromRouteHandler($routeHandler)
    {
        if (is_string($routeHandler)) {
            return strpos($routeHandler, '\\')
                ? $routeHandler
                : getenv('APP_NAME') . "\\Action\\" . str_replace('.', '\\', $routeHandler);
        }

        return null;
    }
}

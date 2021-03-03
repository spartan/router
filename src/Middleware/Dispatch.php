<?php

namespace Spartan\Router\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spartan\Adr\Definition\ResponderInterface;

/**
 * Dispatch Middleware
 *
 * @package Spartan\Router
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class Dispatch implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws \ReflectionException
     * @throws \Spartan\Service\Exception\ContainerException
     * @throws \Spartan\Service\Exception\NotFoundException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeHandler = container()->get('route')->handler();

        $actionClass = Resolve::actionClassFromRouteHandler($routeHandler);
        if ($actionClass) {
            $action = container()
                ->withBindings([ServerRequestInterface::class => $request])
                ->get($actionClass);

            $payload = $action(...array_values($request->getAttributes()));
            if ($payload instanceof ResponseInterface) {
                return $payload;
            }

            /** @var ResponderInterface $responder */
            $responder = $action->responder();

            return $responder($payload);
        }

        return call_user_func($routeHandler, $request, container()->get('response'));
    }
}

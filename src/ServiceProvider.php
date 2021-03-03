<?php

namespace Spartan\Router;

use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Spartan\Cache\Cache;
use Spartan\Router\Definition\RouterInterface;
use Spartan\Service\Container;
use Spartan\Service\Definition\ProviderInterface;
use Spartan\Service\Pipeline;

/**
 * ServiceProvider Router
 *
 * @package Spartan\Router
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class ServiceProvider implements ProviderInterface
{
    /**
     * @return mixed[]
     */
    public function singletons(): array
    {
        return [
            'router' => function ($c) {
                $cache = $c->get(CacheInterface::class);

                $router = $cache->get('router');

                if (!$router) {
                    $adapterClass = 'Spartan\\Router\\Adapter\\' . (getenv('ROUTER_ADAPTER') ?: 'FastRoute');
                    /** @var RouterInterface $router */
                    $router = new $adapterClass();
                    $routes = require_once './config/routes.php';

                    foreach ($routes as $route) {
                        $router->withRoute(
                            $route[0],  // name
                            $route[1],  // method(s)
                            $route[2],  // path regex
                            $route[3],  // handler
                            $route[4] ?? [], // options
                        );
                    }

                    $cache->set('router', $router, Cache::TTL_ONE_WEEK);
                }

                return $router;
            },
        ];
    }

    /**
     * @param ContainerInterface $container
     * @param Pipeline               $handler
     *
     * @return ContainerInterface
     */
    public function process(ContainerInterface $container, Pipeline $handler): ContainerInterface
    {
        /*
         * We load all services first
         */
        $container = $handler->handle($container);

        /** @var Container $container */
        $container->withBindings($this->singletons(), []);

        return $container;
    }
}

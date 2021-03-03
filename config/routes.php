<?php

/*
 * Definition:
 *  ['name', 'method', 'path|regex', 'handler', ['options']]
 *
 * Example:
 *  ['home', 'GET', '/', 'Home']
 *
 * Multiple methods:
 *  ['home', ['get', 'post'], '/', 'Handler']
 *
 * With options (depending on the adapter's capabilities)
 *  ['home', ['get', 'post'], '/', 'Handler', ['middleware' => [...]]
 *
 * Grouping (@TODO)
 *  'group' => [
 *      'prefix' => '/resource' // prefix for url
 *      'name' => 'resource' // prefix for names
 *      'resource' => 'Resource' // use for all
 *      'routes' => [
 *          ['read', 'GET', '/'],
 *          ['create', 'POST', '/'],
 *          ['update', 'PATCH', '/{id}'],
 *          ['delete', 'DELETE', '/{id}'],
 *      ]
 *  ]
 */

return [
    ['home', 'GET', '/', 'Home'],
];

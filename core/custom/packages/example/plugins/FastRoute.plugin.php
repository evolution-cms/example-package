<?php

namespace EvolutionCMS\Example;
use Event;
use FastRoute;

Event::listen('evolution.OnPageNotFound', function() {
    //header('Access-Control-Allow-Origin: *');
    //header("Access-Control-Allow-Credentials: true");
    //header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    //header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

    $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

        $r->addRoute('GET', '/api/v1/document/{id:\d+}', ['EvolutionCMS\Custom\ExampleApiController','getDocument']);

    });

    // Fetch method and URI from somewhere
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    // Strip query string (?foo=bar) and decode URI
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = rawurldecode($uri);

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            header("HTTP/1.1 405");
            header('Content-Type: text/plain');
            die('Method not allowed');
            break;
        case FastRoute\Dispatcher::FOUND:
            call_user_func_array(array(new $routeInfo[1][0], $routeInfo[1][1]), $routeInfo[2]);
            die();
            break;
    }

});


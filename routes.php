<?php

$router = new \Bramus\Router\Router();

$router->post('/superposuda', '\App\Api@post');

$router->run();
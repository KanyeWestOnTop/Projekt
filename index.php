<?php

use App\Controller\HomeController;
use App\Controller\ExerciseController;
use App\Controller\UserController;
use App\Controller\ProgressController;

require_once "vendor/autoload.php";

$uri = $_SERVER["REQUEST_URI"];
$httpMethod = $_SERVER["REQUEST_METHOD"];

if ($uri === "/") {
    $controller = new HomeController();
    $controller->index();
}

if ($uri === "/exercises") {
    $controller = new ExerciseController();
    if ($httpMethod === "GET") {
        $controller->index();
    } else if ($httpMethod === "POST") {
        $controller->store($_POST);
    }
}

if ($uri === "/exercises/create") {
    $controller = new ExerciseController();
    $controller->create();
    if ($httpMethod === "POST") {
        $controller->index();
    }
}

if (preg_match("#/exercises/\d+$#", $uri)) {
    $matches = array();
    preg_match("/\d+/", $uri, $matches);
    $controller = new ExerciseController();
    if ($httpMethod === "GET") {
        $controller->edit($matches[0]);
    } else if ($httpMethod === "POST" && isset($_POST["_method"]) && $_POST["_method"] === "PUT") {
        $controller->update($matches[0], $_POST);
    } else if ($httpMethod === "POST" && isset($_POST["_method"]) && $_POST["_method"] === "DELETE") {
        $controller->delete($matches[0]);
    }
}

if ($uri === "/progress") {
    $controller = new ProgressController();
    if ($httpMethod === "GET") {
        $controller->index();
    } else if ($httpMethod === "POST") {
        $controller->store($_POST);
    }
}

if ($uri === "/progress/create") {
    $controller = new ProgressController();
    $controller->create();
    if ($httpMethod === "POST") {
        $controller->index();
    }
}

if (preg_match("#/progress/\d+$#", $uri)) {
    $matches = array();
    preg_match("/\d+/", $uri, $matches);
    $controller = new ProgressController();
    if ($httpMethod === "GET") {
        $controller->edit($matches[0]);
    } else if ($httpMethod === "POST" && isset($_POST["_method"]) && $_POST["_method"] === "PUT") {
        $controller->update($matches[0], $_POST);
    } else if ($httpMethod === "POST" && isset($_POST["_method"]) && $_POST["_method"] === "DELETE") {
        $controller->delete($matches[0]);
    }
}

if (preg_match("#/history/\d+$#", $uri)) {
    $matches = array();
    preg_match("/\d+/", $uri, $matches);
    $controller = new ProgressController();
    if ($httpMethod === "GET") {
        $controller->history($matches[0]);
    }
}

if ($uri === "/profile") {
    $controller = new UserController();
    $controller->index();
}

if ($uri === "/login") {
    $controller = new UserController();
    $controller->login();
    if ($httpMethod === "POST") {
        $controller->login();
    }
}

if ($uri === "/register") {
    $controller = new UserController();
    $controller->register();
    if ($httpMethod === "POST") {
        $controller->register();
    }
}

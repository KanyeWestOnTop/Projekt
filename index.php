<?php

use App\Controller\HomeController;
use App\Controller\ExerciseController;
use App\Controller\LoginController;
use App\Controller\ProgressController;
use App\Controller\UserController;
use App\Middleware\Authenticated;

require_once "vendor/autoload.php";

session_start();

$uri = $_SERVER["REQUEST_URI"];
$httpMethod = $_SERVER["REQUEST_METHOD"];
$authenticated = new Authenticated();

if ($uri == "/register") {
    $controller = new UserController();
    if ($httpMethod == "GET") {
        $controller->create();
        die();
    } else {
        $controller->store($_POST);
    }
}

if ($uri == "/login") {
    $controller = new LoginController();
    if ($httpMethod == "GET") {
        $controller->index();
        die();
    } else {
        $controller->login($_POST);
    }
    die();
}


if ($uri == "/logout") {
    $controller = new LoginController();
    if ($httpMethod === "POST" && isset($_POST["_method"]) && $_POST["_method"] === "DELETE") {
        $controller->logout();
    }
}

if ($authenticated->handle()) {

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

    if ($uri === "/exercise/create") {
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

    if (preg_match("#progresses/\d+$#", $uri)) {
        $matches = array();
        preg_match("/\d+/", $uri, $matches);
        $controller = new ProgressController();
        if ($httpMethod === "GET") {
            $controller->show($matches[0], $_POST);
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

    if (preg_match("#progress/\d+$#", $uri)) {
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

    if (preg_match("#/progress/create/\d+$#", $uri)) {
        $matches = array();
        preg_match("/\d+/", $uri, $matches);
        $controller = new ProgressController();
        $controller->createthis($matches[0]);
        if ($httpMethod === "POST") {
            $controller->index();
        }
    }

    if ($uri === "/profile") {
        $controller = new UserController();
        $controller->info();
    }

    if ($uri === "/user/changepassword") {
        $controller = new UserController();
        if ($httpMethod === "GET") {
            $controller->changepassword();
        } else if ($httpMethod === "POST") {
            $controller->updatepassword($_POST);
        }
    }
}

<?php

namespace App\Controller;

use App\Utils\Validator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class DefaultController {

    
    private Environment $twig;
    private Validator $validator;
    
    public function __construct() {
        $loader = new FilesystemLoader("views");
        $this->twig = new Environment($loader);
        $this->twig->addGlobal("session", $_SESSION);
        $this->validator = new Validator();
    }

    protected function render(string $file, array $params = []) {
        $_SESSION['template'] = $file;
        $_SESSION['params'] = serialize($params);

        echo $this->twig->render($file, $params);
    }

    protected function redirect(string $path) {
        header("Location: $path");
    }

    protected function validate(array $values, array $rules): bool {

        $response = $this->validator->validate($values, $rules);
        
        if(count($response) > 0) {
            $values['errors'] = $response;
            $values = array_merge($values, unserialize($_SESSION['params']));

            $this->render($_SESSION['template'], $values);
            die();
        }
        return true;
    }
}
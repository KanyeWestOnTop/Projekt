<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class DefaultController
{
    
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader("views");
        $this->twig = new Environment($loader);
    }

    protected function render(string $file, array $params = [])
    {
        echo $this->twig->render($file, $params);
    }

    protected function redirect(string $path)
    {
        header("Location: $path");
    }
}



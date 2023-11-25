<?php
namespace App\Controller;

class HomeController extends DefaultController {

    public function index() {
        $this->render("home.html.twig");
    }
}
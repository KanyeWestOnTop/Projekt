<?php
namespace App\Controller;

class HomeController extends DefaultController {

    public function index() {
        $this->render("home.html.twig");
    }

    /*public function notfound() {
        $this->render("404.html");
    }*/
}
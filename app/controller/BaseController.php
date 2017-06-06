<?php

class BaseController{

    public $view;

    public $router;

    public $route;

    public $history;

    public $pdo;

    public $user;

    public $session;

    public $di;

    public $tv;

    public function __construct(Pimple\Container $di){

        $this->di = $di;

        $this->tv = $di->get("tv");

        $this->view = $di->get("view");

        $this->router = $di->get("router");

        $this->pdo = $di->get("pdo");

        $this->history = $di->get("history");

        $this->user = $di->get("user");

        $this->session =$di->get("session");

        $this->commonTV();

    }

    public function commonTV(){

        $this->tv["homelink"] = $this->router->pathFor("home");

        $this->tv["signinlink"] = $this->router->pathFor("signin");

        $this->tv["signuplink"] = $this->router->pathFor("signup");

    }

}
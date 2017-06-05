<?php

$app->group("/", function(){

    $this->get("", '\HomeController:homeAction')->setName("home");

    $this->get("connexion", '\SignController:signinAction')->setName("signin");

    $this->get("inscription", '\SignController:signupAction')->setName("signup");

});
<?php

class SignController extends BaseController{

    public function signinAction($request, $response, $args){

        $params["css"][] = Less::compile("signin");

        return $this->view->render($response, 'signin.html', $params);

    }

    public function signupAction($request, $response, $args){

        $params["css"][] = Less::compile("signup");

        return $this->view->render($response, 'signup.html', $params);

    }

    

}
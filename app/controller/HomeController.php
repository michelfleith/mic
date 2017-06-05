<?php

class HomeController extends BaseController{

    public function homeAction($request, $response, $args){

        $this->tv["css"][] = Less::compile("home");

        return $this->view->render($response, 'home.html', $this->tv);

    }

}
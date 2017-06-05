<?php

/*
give access or not to current route

if cannot access, simply redirect to correct route : 401, login, etc.

rule to give access is completely custom and can be set with any code you want : session/cookie , some test value of user or another...

*/

class AclMiddleware{

    public $di;

    public $user;

    public function __construct($app) {

        $this->user = $app->getContainer()->get("user");

        $this->router = $app->getContainer()->get("router");

    }

    public function __invoke($request, $response, $next){

        $route = $request->getAttribute('route');

        switch($route->getName()){

            case "customer_account":

            case "customer_dashboard":

            case "customer_information":

            case "customer_orders":

            case "customer_order":

                if(!$this->user || $this->user->id_role != 1)

                    return $response->withRedirect($this->router->pathFor('login'), 301);

                break;

        }
        
        $response = $next($request, $response);

        return $response;
    }
}
<?php

$app->group("/customer", function(){

    $this->get("/account", '\CustomerController:dashboardAction')->setName("customer_account");

    $this->get("/account/information", '\CustomerController:informationAction')->setName("customer_information");

    $this->get("/account/orders", '\CustomerController:ordersAction')->setName("customer_orders");

    $this->get("/account/order/{token_order}", '\CustomerController:orderAction')->setName("customer_order");

})->add(new AclMiddleware($app));
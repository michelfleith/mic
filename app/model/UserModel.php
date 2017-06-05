<?php

class UserModel extends DalModel{

    protected $table = "user";

    protected $primary_key = "id_user";

    public function checkLogin($user, $password){

        $this->_select("id_user");

        $this->_where("user", "=", $user);

        $this->_where("password", "=", hash('sha512',$password));

        if($this->_getOne()){

            return $this->login();

        }else{

            return false;

        }

    }

    public function login(){

        if($this->token){

            $this->session->set("token_user", $this->token);

        }

        return $this->isConnected();

    }

    public function logout(){

        $this->session->destroy();

    }

    public function isConnected(){

        return (boolean)$this->session->exists("token");

    }

    public function getUser(){

        $user = false;

        if($this->isConnected()){

            $user = $this->_getBy("token", $this->session->get("token"));

        }

        return $user;

    }

}
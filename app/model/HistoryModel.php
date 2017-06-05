<?php

class HistoryModel extends DalModel{

    protected $table = "history";

    protected $primary_key = "id_history";


    public function add($id_user, $event){

        if((boolean)$is_user && !empty($event)){

            $this->_set("id_user", $id_user);

            $this->_set("event", $event);

            $this->_set("date", date("Y-m-d H:i:s"));

            $this->_create();

        }

    }

}
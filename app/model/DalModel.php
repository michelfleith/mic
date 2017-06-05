<?php

use Slim\Container;

class DalModel {

    protected $table = "";

    public $_token = "token";

    public $session;

    public $di;

	public $innerjoin;

	public $where;

	public $from;

	public $select;

	public $limit;

	public $order;


    function __construct() {

        global $di;

        $this->di = $di;

        $this->session = $di->get("session");

        $this->pdo	=	$di->get("pdo");

		$this->router = $di->get("router");

		$this->where = array();

		$this->innerjoin = array();

		$this->from = array();

		$this->select = array();

		$this->limit = "";

		$this->order = "";

    }


    public function _get($field){

		if(!isset($this->{$field}))return false;

		return $this->{$field};

	}

	
	public function _set($field, $value){

		$this->{$field} = $value;

		return $this->data[$field] = $value;

	}

	
	public function _getBy($field, $value, $limit = false){

		$item = $this->pdo->query("SELECT * FROM `".$this->table."` WHERE `".$field."`='".$value."'")->fetch(PDO::FETCH_OBJ);
		if(!$item)

			return false;

		foreach($item as $attr=>$value){

			$this->{$attr} = $value;

		}

		$this->_set($this->primary_key, $item->{$this->primary_key});

		return $this;

	}


	public function _where($attr, $operator, $value){

		if($operator=="IN"){

			$this->where[] = "`".$attr."` ".$operator." (".$value.")";

		}else{

			$this->where[] = "`".$attr."`".$operator."'".$value."'";

		}

	}


	public function _innerjoin($sql){

		$this->innerjoin[] = $sql;

	}


	public function _from($sql){

		$this->from[] = $sql;

	}


	public function _select($sql){

		$this->select[] = $sql;

	}


	public function _limit($sql){

		$this->limit = $sql;

	}


	public function _order($sql){

		$this->order = $sql;

	}


	public function _reset(){

		$this->where = array();

		$this->innerjoin = array();

		$this->from = array();

		$this->select = array();

		$this->limit = "";

		$this->order = "";

	}


    public function _getCollection($return=PDO::FETCH_ASSOC){
        
        $collection = $this->pdo->query("SELECT ".(!empty($this->select)?implode(",",$this->select):"*")." 

                                                FROM ".(!empty($this->from)?implode(",",$this->from):$this->table)." ".(!empty($this->innerjoin)?" 

                                                    INNER JOIN ".implode(" ",$this->innerjoin):"")." ".(!empty($this->where)?" 

                                                    WHERE ".implode(" AND ",$this->where):"")." ".$this->limit." ".$this->order)->fetchAll($return);

		$this->_reset();
		
        if(!$collection)
		
        	return false;
		
        return $collection;
    
    }


	public function _getOne($return=PDO::FETCH_ASSOC){

        $one = $this->pdo->query("SELECT ".(!empty($this->select)?implode(",",$this->select):"*")." 

                                        FROM ".(!empty($this->from)?implode(",",$this->from):$this->table)." ".(!empty($this->innerjoin)?" 

                                            INNER JOIN ".implode(" ",$this->innerjoin):"")." ".(!empty($this->where)?" 

                                            WHERE ".implode(" AND ",$this->where):"")." ".$this->limit." ".$this->order)->fetch($return);
		$this->_reset();

		if(!$one)

			return false;

		return $one;

    }

   
    public function _update(){

		if(!empty($this->data)){

			$_set = array();

			$params = array();

			foreach($this->data as $attr=> $v){

				if($attr!=$this->primary_key)

					$_set[] = "`".$attr."`=:".$attr;

				$params[":".$attr] = $v;

                $this->{$attr} = $v;

			}
			
            $s = $this->pdo->prepare("UPDATE `".$this->table."` SET ".implode(", ", $_set)." WHERE `".$this->primary_key."`=:".$this->primary_key);

            return $s->execute($params);
            
		}
	}


	public function _create(){

		if(!empty($this->data)){

			$_set = array();

			$params = array();

			foreach($this->data as $attr=> $v){

				if($attr!=$this->primary_key)

					$_set[] = "`".$attr."`=:".$attr;

				$params[":".$attr] = $v;

                $this->{$attr} = $v;

			}
   
            $s = $this->pdo->prepare("INSERT INTO `".$this->table."` SET ".implode(", ", $_set));

            $e = $s->execute($params);
	
			if($e)

				return $this->pdo->lastInsertId();

            else

                return false;

		}

	}


    public function _delete(){

        $this->pdo->query("DELETE FROM `".$this->table."` WHERE `".$this->primary_key."`='".$this->{$this->primary_key}."'");

    }


    public function _createToken(){

        $token = bin2hex(openssl_random_pseudo_bytes(16));

        $exists = $this->pdo->query("SELECT `".$this->_token."` FROM `".$this->table."` WHERE `".$this->_token."`='".$token."'")->fetch(PDO::FETCH_OBJ);

        if(!$exists){

            return $token;

        }else

            $this->_createToken();

    }


    public function _appendCallback($callback){

        if(is_array($callback))

            $this->r6->callback = array_merge($this->r6->callback, $callback);

        else

            array_push($this->r6->callback, $callback);

    }


    public function _addFlash($newmessages){

		if($newmessages!="" || !empty($newmessages)){

			if(!is_array($newmessages)){

				$newmessages = array($newmessages);

			}

			$messages = array();

			$_messages = $this->_getFlash();
			
			if($_messages){

				foreach($_messages as $_message){

					foreach($newmessages as $newmessage)

						if($_message==$newmessage)$exist=true;

					if(!$exist)	

						$_messages[] = $message;

					$exist = false;

				}

			}else{

				$_messages = $newmessages;

			}

			$this->r6->session->set('flashmessage', $_messages);

			return true;

		}else{

			return false;

		}

    }


    public function _getFlash(){

        $messages = false;

        if($this->r6->session->exists('flashmessage')){

            $messages = $this->r6->session->get('flashmessage');

            $this->r6->session->delete('flashmessage');

            $messages = $this->r6->patron('htm/flashmessage.htm', array("messages"=>$messages));

        }

        return $messages;

    }


	public function _getRoute($route, $infos=array()){

		return $this->router->pathFor($route, $infos);

	}


}
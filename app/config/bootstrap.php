<?php

define("PATH",dirname(__FILE__)."/../../");

require PATH."vendor/autoload.php";

$config = require PATH."app/config/config.php";

$app = new \Slim\App($config);

$app->add(new \Slim\Middleware\Session([

  'name' => 'MIC_SESSION',

  'autorefresh' => true,

  'lifetime' => '24 hour'

]));


/*
DEFINE ALL DEPENDENCIES INJECTION : $di
*/

$di = $app->getContainer();

$di['session'] = function ($di) {

  $session = new \SlimSession\Helper;

  $session->set("token", "a954c3a01de2f7f84d53a65019a15e01");

  $session->delete("token");

  return $session;

};


$di['view'] = function ($di) {

    $view = new \Slim\Views\Twig(dirname(__FILE__).'/../../app/view');
    
    $basePath = rtrim(str_ireplace('index.php', '', $di['request']->getUri()->getBasePath()), '/');

    $view->addExtension(new Slim\Views\TwigExtension($di['router'], $basePath));

    return $view;
};


$di['pdo'] = function($di){

    $db = $di->get("settings")->get("db");

    $database  = "mysql:dbname=".$db["dbname"].";";

    $database .= "charset=".$db["charset"].";";
    
    $database .= "host=".$db["host"];

    $user = $db["user"];

    $passwd = $db["pass"];

    try{

        $pdo = new PDO($database, $user, $passwd);
	
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }catch (PDOException $e) {

        print_r($config);
	
    	echo $e->getMessage();
	
    }

    return $pdo;

};

$di["history"] = function($di){

    return new HistoryModel;

};

$di["user"] = function($di){

    $user = new UserModel;

    return $user->getUser();

};

$di["tv"] = function($di){

    return [];

};



spl_autoload_register(function ($classname) {

    $types = ["controller", "model", "middleware", "dependency"] ;

    foreach($types as $type)

        if(file_exists(PATH."app/".$type."/" . $classname . ".php"))

            require (PATH."app/".$type."/" . $classname . ".php");

});


//$di[UserModel::class] =  new UserModel($di);


require (PATH."/app/routes/main.php");

require (PATH."/app/routes/admin.php");

require (PATH."/app/routes/lawyer.php");

require (PATH."/app/routes/customer.php");
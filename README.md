This kind of VirtualHost can be useful !

<VirtualHost local.domain>
    DocumentRoot "PATH/TO/public"
    ServerName local.domain
    ErrorLog PATH/TO/LOG/error_log
    ErrorDocument 404 /error_404.htm
    DirectoryIndex index.html index.htm index.php
    <Directory "PATH/TO/public">
        AllowOverride All
        Order allow,deny
        Allow from all
        Options Indexes FollowSymLinks
        Require all granted
    </Directory>
</VirtualHost>


App is based on Slim Framework 3

After git clone, please run <pre>php composer.phar install</pre> 

app/config/bootstrap.php is the starter file for all configuration.

Routes are defined in app/routes folder

Each routes point to a controller::method 

Each controller extends app/controller/BaseController.php

BaseController.php get all the dependencies injected (di) so all children controllers inherit of these.

Dependencies injection or $di contains all common object for project 

like session, routes, user info, pdo, view, tv and others we need.

Controller must only receive data of model, place it in tv (template variable) and render a view.

Models are store in app/model folder.

Each model extends DalModel (Data Access Layout)

Dal is a collection of method to crud data from database.

This model is a completely custom code and can be changed to be optimized front of MIC project.

DalModel get all the dependencies too so all childrens can access to $di.

common css and js are placed in app/view/base.html

tv named js and css can be filled to inject custom per page css/js

css is less format, use <pre>$this->tv["css"][] = Less::compile("home");</pre> to inject it.

Please dry your code an be clear with name of class/method/var

Only comment if necessary, good code is like a good book !

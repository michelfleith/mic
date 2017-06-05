<?php

class Less{

    public static function compile($file){

        $less = new lessc;

        $less->setFormatter("compressed");

        $less->checkedCompile(PATH."public/css/".$file.".less", PATH."public/css/cache/".$file.".css");

        return $file.".css";
    }

}
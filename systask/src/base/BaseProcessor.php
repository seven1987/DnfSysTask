<?php
/**
 * Created by PhpStorm.
 * User: colen
 * Date: 2017/2/5
 * Time: 17:08
 */

namespace base;

class BaseProcessor {
    private $handlers;

    public function __construct(){
        $this->handlers = array();
        $handler = new BaseSwooleProcess('base\BaseSwooleProcess::swooleHandle');
        $handler->name = "colen";
        $handler->start();
//        $handler["aaa"] = "bbbbbb";
        array_push($this->handlers,$handler);
    }

    public function run(){
        foreach ($this->handlers as $handler){
//            $handler->start();
            echo "run ".$handler->name;
            $handler->exit(0);
        }
    }
}

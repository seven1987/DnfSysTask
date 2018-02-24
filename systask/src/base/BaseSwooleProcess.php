<?php
/**
 * Created by PhpStorm.
 * User: colen
 * Date: 2017/2/5
 * Time: 20:07
 */

namespace base;

use systask\handicap\ReckonHandler;

class BaseSwooleProcess {
    public $name;
    public $running;
    public $runHandler;
    private $runOnce;

    public function setHandler($handler,$runOnce = false){
        $this->runHandler = $handler;
        $this->runOnce = $runOnce;
    }

    public function run(){
        while (true){
            echo "iiiiiii ";
            if (is_null($this->runHandler)){
                sleep(3);
                continue;
            }
            $this->runHandler->run();
            //hanlder只执行一次:
            if ($this->runOnce){
                $this->runHandler = null;
            }
            sleep(1);
//            echo "process running \n";
        }
    }

    public static function handle($process,$aa,$mqService){
       echo $mqService;
//        $process->run();
        $process->exit(0);
    }

}
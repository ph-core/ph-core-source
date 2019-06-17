<?php
namespace Module\Board;

use Corelib\Method;

class Make_Controller extends \Controller\Make_Module_Controller{

    public function run(){
        $req = Method::request('GET','mode');

        switch($req['mode']){
            case 'view' :
            $run = new \Module\Board\View();
            break;

            case 'write' :
            $run = new \Module\Board\Write();
            break;

            case 'delete' :
            $run = new \Module\Board\Delete();
            break;

            default :
            $run = new \Module\Board\Lists();
        }

        $this->configure();
        $run->_init();
    }

}

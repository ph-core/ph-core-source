<?php
namespace Module\Contactform;

class Make_Controller extends \Controller\Make_Module_Controller{

    public function run(){
        $run = new \Module\Contactform\Form();
        $run->CONF = $this->configure();
        $run->_init();
    }

}

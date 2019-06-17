<?php
namespace Corelib;

use Corelib\Session;
use Corelib\Func;
use Make\Database\Pdosql;

class Statistic{

    static public function rec_visitcount(){
        global $MB;

        $sql = new Pdosql();

        $sql->scheme('Core\\Scheme');

        if(!Session::is_sess('VISIT_MB_IDX') || Session::sess('VISIT_MB_IDX')!=$MB['idx']){

            $device = Func::chkdevice();

            $sql->query(
                $sql->scheme->core('select:visitcount'),
                array(
                    $_SERVER['REMOTE_ADDR']
                )
            );

            if($sql->getcount()<1){
                $sql->query(
                    $sql->scheme->core('insert:visitcount'),
                    array(
                        $MB['idx'],$MB['id'],
                        $_SERVER['REMOTE_ADDR'],
                        $device,
                        $_SERVER['HTTP_USER_AGENT']
                    )
                );

            }else if($MB['idx']!=$sql->fetch('mb_idx') && $sql->fetch('mb_idx')!=''){

                $sql->query(
                    $sql->scheme->core('update:visitcount'),
                    array(
                        $MB['idx'],$MB['id'],
                        $device,
                        $_SERVER['HTTP_USER_AGENT'],
                        $_SERVER['REMOTE_ADDR']
                    )
                );

            }
            Session::set_sess('VISIT_MB_IDX',$MB['idx']);
        }
    }

}

Statistic::rec_visitcount();

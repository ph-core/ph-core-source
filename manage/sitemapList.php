<?php
use Make\Database\Pdosql;
use Manage\Func as Manage;

class SitemapList extends \Controller\Make_Controller{

    public function _init(){
        $this->_make();
        $this->load_tpl(PH_MANAGE_PATH.'/html/sitemapList.tpl.php');
    }

    public function _make(){
        $sql = new Pdosql();
        $sql2 = new Pdosql();
        $sql3 = new Pdosql();

        $sql->scheme('Manage\\Scheme');
        $sql2->scheme('Manage\\Scheme');
        $sql3->scheme('Manage\\Scheme');

        $sql->query(
            $sql->scheme->sitemap('select:list_1d'),''
        );
        $list_cnt = $sql->getcount();

        $print_arr = array();

        if($list_cnt>0){
            do{
                $arr = $sql->fetchs();

                //depth 2
                $print_arr2 = array();
                if($sql->fetch('children')>0){

                    $sql2->query(
                        $sql2->scheme->sitemap('select:list_2d'),array(
                            $arr['caidx']
                        )
                    );

                    do{
                        $arr2 = $sql2->fetchs();

                        //depth 3
                        $print_arr3 = array();
                        if($sql2->fetch('children')>0){
                            $sql3->query(
                                $sql3->scheme->sitemap('select:list_3d'),array(
                                    $arr2['caidx']
                                )
                            );

                            do{
                                $arr3 = $sql3->fetchs();

                                $print_arr3[] = $arr3;
                            }while($sql3->nextRec());
                        }
                        $arr2['3d'] = $print_arr3;

                        $print_arr2[] = $arr2;
                    }while($sql2->nextRec());
                }
                $arr['2d'] = $print_arr2;

                $print_arr[] = $arr;

            }while($sql->nextRec());
        }

        $this->set('print_arr',$print_arr);

    }

}

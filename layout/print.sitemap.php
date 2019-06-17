<?php
use Make\Database\Pdosql;

$stsql = new Pdosql();

$stsql->scheme('Core\\Scheme');

$stsql->query(
    $stsql->scheme->layout('select:sitemap_1d'),''
);
$list_cnt = $stsql->getcount();

$SITEMAP = array();
$link_dir = PH_DIR.'/';

if($list_cnt>0){
    do{
        $arr = $stsql->fetchs();

        $arr['href'] = $link_dir.$arr['href'];

        //depth 2
        $gnb_arr2 = array();
        if($stsql->fetch('children')>0){
            $stsql2 = new Pdosql();

            $stsql2->query(
                $stsql->scheme->layout('select:sitemap_2d'),
                array(
                    $arr['caidx']
                )
            );

            do{
                $arr2 = $stsql2->fetchs();

                $arr2['href'] = $link_dir.$arr2['href'];

                //depth 3
                $gnb_arr3 = array();
                if($stsql2->fetch('children')>0){
                    $stsql3 = new Pdosql();

                    $stsql3->query(
                        $stsql->scheme->layout('select:sitemap_3d'),
                        array(
                            $arr2['caidx']
                        )
                    );

                    do{
                        $arr3 = $stsql3->fetchs();

                        $arr3['href'] = $link_dir.$arr3['href'];

                        $gnb_arr3[] = $arr3;
                    }while($stsql3->nextRec());
                }
                $arr2['3d'] = $gnb_arr3;

                $gnb_arr2[] = $arr2;
            }while($stsql2->nextRec());
        }
        $arr['2d'] = $gnb_arr2;

        $SITEMAP[] = $arr;

    }while($stsql->nextRec());
}

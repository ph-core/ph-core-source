<?php
namespace Module\Contents;

class Scheme extends \Make\Database\Pdosql{

    public function view($type){

        if($type=="select:contents"){
            return "
                SELECT *
                FROM {$this->table("mod:contents")}
                WHERE data_key=:col1
            ";
        }

    }

    public function manage($type){

        if($type=="sort:contents"){
            return "
                SELECT
                (
                    SELECT COUNT(*)
                    FROM {$this->table("mod:contents")}
                ) contents_total
            ";
        }

        if($type=="select:contentslist"){
            global $sortby,$searchby,$orderby;
            return "
                SELECT *
                FROM {$this->table("mod:contents")}
                WHERE 1 $sortby $searchby
                ORDER BY $orderby
            ";
        }

        if($type=="update:contents"){
            return "
                UPDATE {$this->table("mod:contents")}
                SET title=:col1,html=:col2,mo_html=:col3,use_mo_html=:col4
                WHERE idx=:col5
            ";
        }

        if($type=="select:contents"){
            return "
                SELECT *
                FROM {$this->table("mod:contents")}
                WHERE idx=:col1
            ";
        }

        if($type=="select:chk_datakey"){
            return "
                SELECT *
                FROM {$this->table("mod:contents")}
                WHERE data_key=:col1
                ORDER BY regdate DESC
            ";
        }

        if($type=="delete:contents"){
            return "
                DELETE
                FROM {$this->table("mod:contents")}
                WHERE idx=:col1
            ";
        }

        if($type=="insert:contents"){
            return "
                INSERT INTO {$this->table("mod:contents")}
                (data_key,title,html,mo_html,use_mo_html,regdate)
                VALUES
                (:col1,:col2,:col3,:col4,:col5,now())
            ";
        }

    }

}

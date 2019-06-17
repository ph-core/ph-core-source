<?php
namespace Module\Contactform;

class Scheme extends \Make\Database\Pdosql{

    public function write($type){

        if($type=="insert:contact"){
            return "
                INSERT INTO {$this->table("mod:contactform")}
                (mb_idx,article,name,email,phone,regdate,contact_1,contact_2,contact_3,contact_4,contact_5,contact_6,contact_7,contact_8,contact_9,contact_10)
                VALUES
                (:col1,:col2,:col3,:col4,:col5,now(),:col6,:col7,:col8,:col9,:col10,:col11,:col12,:col13,:col14,:col15)
            ";
        }

    }

    public function manage($type){

        if($type=="sort:contact"){
            return "
                SELECT
                (
                SELECT COUNT(*)
                FROM {$this->table("mod:contactform")}
                WHERE name IS NOT NULL
                ) contactform_total
            ";
        }

        if($type=="select:contacts"){
            global $sortby,$searchby,$orderby;
            return "
                SELECT *
                FROM {$this->table("mod:contactform")}
                WHERE name IS NOT NULL $sortby $searchby
                ORDER BY $orderby
            ";
        }

        if($type=="select:contact"){
            return "
                SELECT *
                FROM {$this->table("mod:contactform")}
                WHERE idx=:col1
                LIMIT 1
            ";
        }

        if($type=="select:contact2"){
            return "
                SELECT idx
                FROM {$this->table("mod:contactform")}
                WHERE article=:col1
            ";
        }

        if($type=="insert:contact"){
            return "
                INSERT INTO
                {$this->table("mod:contactform")}
                (article,regdate)
                VALUES
                (:col1,now())
            ";
        }

        if($type=="update:rep"){
            return "
                UPDATE {$this->table("mod:contactform")}
                SET rep_idx=:col1
                WHERE idx=:col2
            ";
        }

        if($type=="delete:contact"){
            return "
                DELETE
                FROM {$this->table("mod:contactform")}
                WHERE idx=:col1
            ";
        }

    }

}

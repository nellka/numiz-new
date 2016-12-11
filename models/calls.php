<?php

class model_addcall extends Model_Base {
    public function isCallExist($phone){
        $sql = "select * from addcall where phone = '".$phone."' and DATE(from_unixtime(date )) = DATE(now())";

        return $this->db->fetchRow($sql);
    }
}
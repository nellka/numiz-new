<?php

/*Класс для получения информации о пользователе*/
class model_news extends Model_Base 
{		
    public $user_id;    
    public $username;   
     
	//получаем баланс пользователя
    public function getUserBalance($user_id){
	    $sql = "SELECT balance FROM user_bonus_balance WHERE user_id ='$user_id'";
    	return $this->db->getOne($sql);
	}
	 //получаем число пользователей на данный момент
    /*public function countAll(){
        $sql = "SELECT count(*) FROM news";
       // return $this->countAll();
    	return $this->db->getOne($sql);
        $count = $this->countAll();
   var_dump($count);
die();
	  //  $sql = "SELECT count(*) FROM from `user`";
    	//return $this->db->getOne($sql);
	}*/
	//проверяем, что пользователь залогинен
	
}
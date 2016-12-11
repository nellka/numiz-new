<?php

/*Класс для получения информации о пользователе*/
class model_subscribe extends Model_Base 
{	
    public $user_id;
   
    public function __construct($db,$user_id=0){
	    parent::__construct($db);
	    $this->user_id = $user_id;	    
	}
		
   /* //если пользователь залогинен и запрещено делать заказы, то проверяем те заказы, которые были
    public 	function setOrderusernow(){
    	 $select = $this->db->select()
    		               ->from('order',array('count(*)'))
    		               ->where('user =?',$this->user_id)
    		               ->where('`check`=1 and SendPost=0 and sum>=500');

        return  $this->db->fetchOne($select)?1:0;
    }
*/  
    public 	function getSubscribes(){
        $select = $this->db->select()
    		               ->from($this->table)
    		               ->where('user =?',$this->user_id);        
        return  $this->db->fetchRow($select);
    }
    public 	function updateSubscribes($data){
        if($this->getSubscribes()){
            $data['dateupdate'] = time();
            $this->db->update($this->table,$data,"user=".$this->user_id);
        } else $this->addSubscribes($data);
        
        return  true;
    }
    
    public 	function addSubscribes($data){
        
        $data['dateinsert'] = $data['dateupdate'] = time();
        
        $this->db->insert($this->table,$data);
        
        return  true;
    }
}
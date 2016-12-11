<?php

/*Класс для получения информации о пользователе*/
class model_seotext extends Model_Base 
{		
   /* //если пользователь залогинен и запрещено делать заказы, то проверяем те заказы, которые были
    public 	function setOrderusernow(){
    	 $select = $this->db->select()
    		               ->from('order',array('count(*)'))
    		               ->where('user =?',$this->user_id)
    		               ->where('`check`=1 and SendPost=0 and sum>=500');

        return  $this->db->fetchOne($select)?1:0;
    }
*/
   public 	function getNext($id,$limit=3){
       $select = $this->db->select()
    		               ->from($this->table,array('id','title','alias'))
    		               ->where('id > ?',$id)
    		               ->where('active = 1')
    		               ->order('id asc')
    		               ->limit($limit);
    	$data =  $this->db->fetchAll($select);
    	
    	if(count($data)<$limit){
    	    $select = $this->db->select()
    		               ->from($this->table,array('id','title','alias'))
    		               ->where('id <> ?',$id)
    		               ->where('active = 1')
    		               ->order('id asc')
    		               ->limit($limit-count($data));
    		$data_next =  $this->db->fetchAll($select);
    		foreach ($data_next as $row) {
    		    $data[] = $row;
    		}              
    	}
    	return $data;
   }
}
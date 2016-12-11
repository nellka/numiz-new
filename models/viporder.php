<?php

class model_shopcoinsvipclientanswer extends Model_Base {	

	public function getNewViporder(){
	    $select = $this->db->select()
                  ->from($this->table,array('max(viporder)'))                  
                  ->limit(1);
        return $this->db->fetchOne($select)+1;
	}
	
	public function addInOrder($viporder_id,$cid,$admin_id=0){
		$data = array(
                    'user_id' => 0, 
	                'viporder' => $viporder_id, 
	                'shopcoins' => $cid, 
	                'dateinsert' => time(),
	                'admin_id'   =>$admin_id);
		$this->db->insert($this->table,$data);
	}
	//������� ������ ������������� ��������
	public function clear(){
		$this->db->delete('user_vipopders','dateinsert < ( unix_timestamp(now()) -14 *3600 *24)');
		return $this->db->delete($this->table,'dateinsert < ( unix_timestamp(now()) -14 *3600 *24)');
	}

	public function getCoins($id){
		 $select = $this->db->select()
                  ->from($this->table)
                  ->join(array('shopcoins'),"shopcoins.shopcoins=$this->table.shopcoins")
                  ->join(array('group'),'shopcoins.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'))                  
                  ->where('viporder=?',$id)
                  ->order('shopcoins.dateinsert desc');
        return $this->db->fetchAll($select);
	}
	
	public function getVipOrders($user_id=0){
		if(!(int)$user_id) return false;
		
		$select = $this->db->select()
                  ->from('user_vipopders','viporder')                  
                  ->where('user_id=?',(int)$user_id);
          echo "<!--".$select->__toString()."-->";
        $vipopders = array();
                
        foreach ($this->db->fetchAll($select) as $u){
        	$vipopders[] = $u['viporder'];
        }
                
        return $vipopders;
	}
	
	public function getAdminInCoins($ids=array(),$user_id=0){
		if(!(int)$user_id||empty($ids)) return 0;
		
		$vipopders = $this->getVipOrders($user_id);
		
		if(!$vipopders||empty($vipopders)) return 0;
		
	    $idadmin = 0;
	    
	    if(!empty($ids)){
	         $select = $this->db->select()
                  ->from($this->table,array('admin_id'))
                  ->where('shopcoins in('.implode(',',$ids).')')
                  ->where('viporder in ('.implode(",",$vipopders).')')
                  ->order('admin_id desc');
                  echo "<!--".$select->__toString()."-->";
             $idadmin = (int) $this->db->fetchOne($select);             
	    }
	    
	    return $idadmin;
	}
	
}
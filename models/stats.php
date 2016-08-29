<?php

require_once('Zend/Db.php');
require_once('Zend/Registry.php');

class stats {
 
    protected $db;
    protected $session_id;
    protected $user_id;
    protected $session_int;
    
    static $newcoins_materialtype = 100;
    static $revaluation_materialtype = 200;
       
    function __construct($db,$user_id=0,$session_id=''){
        $this->db = $db;
	 	$this->cache = Zend_Registry::get('Memcached');	 
	 	$this->user_id = $user_id;
	    $this->session_id = $session_id;
	    $this->session_int = 0;
		$this->clearSession();
	}

	public function	clearSession(){
		if($this->user_id){
			$select  =  $this->db->select()
				->from('user_sessions',array('id'))
				->where('session_id=?',$this->session_id);
			$session_int = $this->db->fetchOne($select);
			if($session_int){
				$data = array('user_id'  => $this->user_id,
					          'session_id' => 0);
				$this->db->update('shopcoins_view_search',$data,'session_id="'.$session_int.'"');
				$this->db->update('shopcoins_view_shopcoins',$data,'session_id="'.$session_int.'"');
				$this->db->update('shopcoins_view_filter',$data,'session_id="'.$session_int.'"');
				$this->cache->remove( "searchcount_user_".$this->user_id);
				$this->cache->remove( "coinscount_user_".$this->user_id);
				$this->cache->remove( "filterscount_user_".$this->user_id);
				$this->session_id = '';
				$this->session_int = 0;
				$this->db->delete('user_sessions','id="'.$session_int.'"');
			}
		} else {
			$this->setSessionInt();
		}
	}

	protected function setSessionInt(){
		if($this->user_id) return 0;
 
		$select  =  $this->db->select()
                  ->from('user_sessions',array('id'))
                  ->where('session_id=?',$this->session_id);
        $session_int = $this->db->fetchOne($select);
        if($session_int) {
        	$this->session_int = $session_int;
        	return ;
        }
        $data = array('session_id' => $this->session_id,
                      'dateinsert' => time());  
              
        $this->db->insert('user_sessions',$data);   
        $this->session_int = $this->db->lastInsertId('user_sessions');
        return ;
	}
	
	public function saveSearch($text=''){    
	    $data = array('user_id'  => $this->user_id,
	                  'session_id' => $this->user_id?0:$this->session_int,
	                  'search'     => $text,
	                  'datetime'       => time());         
       $this->db->insert('shopcoins_view_search',$data);   
       if($this->user_id){
       		$this->cache->remove( "searchcount_user_".$this->user_id);
       } else $this->cache->remove( "searchcount_ses_".$this->session_id);    
	}
	
	public function saveCoins($shopcoins=0){
	    if($shopcoins){
    	   	$data = array('user_id'  => $this->user_id,
    	                  'session_id' => $this->user_id?0:$this->session_int,
    	                  'shopcoins'     => $shopcoins,
    	                  'datetime'       => time());         
           	$this->db->insert('shopcoins_view_shopcoins',$data); 
           	
           	if($this->user_id){
       			$this->cache->remove( "coinscount_user_".$this->user_id);
       		} else $this->cache->remove( "coinscount_ses_".$this->session_id);    
	    }      
	}
	
	public function getlastCoinsIds($page=1,$items_for_page=4){
		$ids = array();
		$select  =  $this->db->select()
                  ->from('shopcoins_view_shopcoins')
                  ->order('id desc')
                  ->limitPage($page, $items_for_page);
        if($this->user_id) {
        	$select->where('user_id=?',$this->user_id);
        } else $select->where('session_id=?',$this->session_int);
      
        $result = $this->db->fetchAll($select);
        foreach ($result as $row){
        	$ids[] = $row['shopcoins'];
        }        
        return $ids;
	}
	
	public function getlastFilters($page=1,$items_for_page=14){
		
		$select  =  $this->db->select()
                  ->from('shopcoins_view_filter')
                  ->order('id desc')
                  ->limitPage($page, $items_for_page);
        if($this->user_id) {
        	$select->where('user_id=?',$this->user_id);
        } else $select->where('session_id=?',$this->session_int);
       
        return $this->db->fetchAll($select);
	}
	
	public function getlastSearch($page=1,$items_for_page=14){
		
		$select  =  $this->db->select()
                  ->from('shopcoins_view_search')
                  ->order('id desc')
                  ->limitPage($page, $items_for_page);

        if($this->user_id) {
        	$select->where('user_id=?',$this->user_id);
        } else $select->where('session_id=?',$this->session_int);
       
        return $this->db->fetchAll($select);
	}
	
	public function saveFilter($data_filter = array()){    
		    if($data_filter['materialtype']=='revaluation') $data_filter['materialtype'] = self::$revaluation_materialtype;
		    if($data_filter['materialtype']=='newcoins') $data_filter['materialtype'] = self::$newcoins_materialtype;
		    if(count($data_filter)<=1) return;
    	    $data = array('user_id'      => $this->user_id,
    	                  'session_id'   => $this->user_id?0:$this->session_int,
    	                  'materialtype' => $data_filter['materialtype'],
    	                  'group_id'     => isset($data_filter['group_id'])?(int)$data_filter['group_id']:0,
    	                  'nominal_id'   => isset($data_filter['nominal_id'])?(int)$data_filter['nominal_id']:0,
    	                  'metal_id'     => isset($data_filter['metal_id'])?(int)$data_filter['metal_id']:0,
    	                  'condition_id' => isset($data_filter['condition_id'])?(int)$data_filter['condition_id']:0,
    	                  'theme_id'     => isset($data_filter['theme_id'])?(int)$data_filter['theme_id']:0,
    	                  'year'         => isset($data_filter['year'])?(int)$data_filter['year']:0,
    	                  'datetime'     => time());         
           	$this->db->insert('shopcoins_view_filter',$data); 
           	if($this->user_id){
       			$this->cache->remove( "filterscount_user_".$this->user_id);
			} else $this->cache->remove( "filterscount_ses_".$this->session_id);    
	}
}

?>
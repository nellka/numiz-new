<?php

class model_forum extends Model_Base  {
 
    protected $db;
    protected $user_id;
    
    function __construct($db,$user_id=0){
        parent::__construct($db,$user_id);
        /*$db['driver_options']  = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"');
		$this->db = Zend_Db::factory('PDO_MYSQL', $db);
	 	$this->db->query("SET names 'utf8'");	 
	 	$this->user_id = $user_id;*/
	}   
	
	public function getLast($limit=5){
	    $select  =  $this->db->select()
                  ->from('vbull_thread',array('threadid','title','dateline','lastposter','forumid'))
                  ->where('visible=1 AND open=1')
                  ->order('dateline DESC')
                  ->limit($limit);
        return  $this->db->fetchAll($select);
	}
	public function getPost($tid){
	    if($tid){
	        $select  =  $this->db->select()
                  ->from('vbull_post',array('postid','pagetext'))
                  ->where('threadid=?',$tid)
                  ->order('postid ASC');
            return  $this->db->fetchRow($select);
	    }
	    return false;
	}

	/*	
	public function saveSearch($text=''){    
	    $data = array('user_id'  => $this->user_id,
	                  'session_id' => $this->user_id?0:$this->session_id,
	                  'search'     => $text,
	                  'datetime'       => time());         
       $this->db->insert('shopcoins_view_search',$data);       
	}
	
	public function saveCoins($shopcoins=0){
	    if($shopcoins){
    	   $data = array('user_id'  => $this->user_id,
    	                  'session_id' => $this->user_id?0:$this->session_id,
    	                  'shopcoins'     => $shopcoins,
    	                  'datetime'       => time());         
           $this->db->insert('shopcoins_view_shopcoins',$data); 
	    }      
	}
	
	public function saveFilter($data_filter = array()){    
    	    $data = array('user_id'      => $this->user_id,
    	                  'session_id'   => $this->user_id?0:$this->session_id,
    	                  'materialtype' => $data_filter['materialtype'],
    	                  'group_id'     => isset($data_filter['group_id'])?(int)$data_filter['group_id']:0,
    	                  'nominal_id'   => isset($data_filter['nominal_id'])?(int)$data_filter['nominal_id']:0,
    	                  'metal_id'     => isset($data_filter['metal_id'])?(int)$data_filter['metal_id']:0,
    	                  'condition_id' => isset($data_filter['condition_id'])?(int)$data_filter['condition_id']:0,
    	                  'theme_id'     => isset($data_filter['theme_id'])?(int)$data_filter['theme_id']:0,
    	                  'year'         => isset($data_filter['year'])?(int)$data_filter['year']:0,
    	                  'datetime'     => time());         
           $this->db->insert('shopcoins_view_filter',$data);     
	}*/
}

?>
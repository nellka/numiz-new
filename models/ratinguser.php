<?php

class model_ratinguser extends Model_Base {	
	
	public function addUrl($data = array()){	
		$data = array( 'group' => $data['group'], 
	                'login' => $data['login'], 
	                'password' => $data['password'], 
	                'email' => $data['email'],
	                'name'   =>$data['name'],
	                'url'   =>$data['url'],
	                'description'   =>$data['description'],
	                'enterdate'   =>$data['enterdate'],
	                'check'=>0,
	                'keywords'=>$data['keywords'],
	                );
		$this->db->insert($this->table,$data);
		return $this->db->lastInsertId($this->table);
	}
	
}
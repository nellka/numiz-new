<?php


class model_shopcoins extends Model_Base
{	
    public $id;
    public $name;
    public $last_name;    	
	
	public function countByParams($where){
	   // $this->_getSelect($select) );	    
	    $sql = "Select count(*) from shopcoins $where;";
    	//$result=mysql_query($sql);
    	$result = $this->db->fetchOne($sql);
    	return $result;
	} 
   
	   
    /*	
	}	
?>
	
	public function countRows($seachArray){
		$where = $this->parseSearchArray($seachArray);
		if($where){
			$where = 'Where '.$where;
		}
		$sql = "select count(*) as number from History_Text
				$where";
		
		$res = $this->db->fetchAll($sql);	
		return $res[0]['number'];
	}
	
	
	public function migrationHistory(){
		$select = $this->db->select()             
					 ->from('HistoryText',array('count(id)'));
		if(!$this->db->fetchOne($select)){
			$sql = "(select FROM_UNIXTIME(his_datetime) as `datetime`, his_text,his_id, editor_id, 0 as editor_name  from History_Text)  union  (select `datetime`, his_text, 0 as  editor_id, 0 as his_id, editor_name	from History_Text_LU) order by `datetime` desc;";
	
			foreach ($this->db->fetchAll($sql) as $row){			
				if($row["editor_name"]){				
					$select = $this->db->select()             
						 ->from('liveuser_users',array('guid'))
						 ->where("handle =?",$row["editor_name"]);
		 			$row['editor_id'] = $this->db->fetchOne($select);
		 			if(!$row['editor_id']) continue;
				}
				
				$insert_array = array('datetime' => $row["datetime"],
								  'his_text'     =>$row["his_text"],
								  'his_id'     =>$row["his_id"],
								  'editor_id'  => $row["editor_id"]);
				$res = $this->db->insert('HistoryText',$insert_array);			
			}
		}	
	}	
	
	
	public function getHistoryText($orders,$ofset,$limit,$seachArray=array()){		
		$where ="";
		if($seachArray){
			$where = $this->parseSearchArray($seachArray);
			if($where){
				$where = 'WHERE '.$where;
			}
		}
		if($orders){
			$orders = "order by ".$orders; 
		}
		if($ofset||$limit) {
			$limitrool= "limit ".(integer)$ofset.",$limit";
		}
		$sql = "select *, handle as editor_name from HistoryText
				left join liveuser_users on guid=editor_id
				$where		
				$orders 				
				$limitrool";
		$result = $this->db->fetchAll($sql,2);
		return $result;

	}
	
// использовлась в старом менеджере до перехода на liveuser
	public function getEditor_OLD($editor_id){
		$sql = "SELECT editor_login FROM Editors
				WHERE editor_id = $editor_id";
	
		$result = $this->db->fetchAll($sql,2);
		return $result[0]['editor_login'];
	}
	
	public function getEditor($editor_id){
		if($editor_id == '0'){
			return 'dis';
		}
		$select = $this->db->select()             
					 ->from('liveuser_users',array('handle'));
   		$select->where("guid =?",$editor_id);
 		$editor_info = $this->db->fetchOne($select);

 		return ($editor_info)?$editor_info:$editor_id;
	}
	
	public  function parseSearchArray($seachArray=array()) { 
                $newSearchArray = array();
		foreach ($seachArray as $key=>$row){
			if(trim($row['value'])){
				$newSearchArray[] = "$key LIKE ".$this->db->quote("%".trim($row['value'])."%");	
			}			
		}		
		return implode(" AND ", $newSearchArray);
	} */
}
?>
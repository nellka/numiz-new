<?
require('Zend/Registry.php');

Abstract Class Model_Base {
 
    protected $db;
    protected $table;
    private $dataResult;
    protected  $cache;
    
    function __construct($db){
        $this->db = $db;
	 	$modelName = get_class($this);
	 	
	 	$this->cache = Zend_Registry::get('Memcached');
        
        $arrExp = explode('_', $modelName);
        $tableName = strtolower($arrExp[1]);
        $this->table = $tableName;        
	}   
	
    function unlockTable(){        
    	$this->db->getConnection()->exec('UNLOCK TABLES;');   
    }
    
	//получаем количество всех записей
    function countAll(){
        $select = $this->db->select()
		               ->from($this->table,array('count(*)'));
    	return $this->db->fetchOne($select);       
    }
    
    function getItem($id){
        $select = $this->db->select()
		               ->from($this->table)
		               ->where('id=?',$id);
    	return $this->db->fetchRow($select);       
    }
    
    function getItemTable($id,$table='',$primary=''){
    	
        $select = $this->db->select();
        if($table){
        	$select->from($table);
        } else $select->from($this->table);
		              
		if($primary){
        	$select->where("$primary=?",$id);
        } else $select->where('id=?',$id);
        
    	return $this->db->fetchRow($select);       
    }
    
    public function getTableName() {
        return $this->table;
    }
   
    public function addNewRecord($inserarray) {
		$this->db->insert($this->table,$inserarray);         
        return $this->db->lastInsertId($this->table);
    }
    
    public function addNewTableRecord($table,$inserarray) {
		$this->db->insert($table,$inserarray);         
        return $this->db->lastInsertId($table);
    }
    
    public function insertNewRecord($table,$inserarray) {
		$this->db->insert($table,$inserarray);         
        return $this->db->lastInsertId($this->table);
    }
    
    public function addNew($table,$inserarray) {
		$this->db->insert($table,$inserarray);         
        return $this->db->lastInsertId($this->table);
    }
    
    public function getRowByParams($params=array()){
        $select = $this->db->select()
		               ->from($this->table);
		foreach ($params as $key=>$value) {
		    $select->where("$key=?",$value) ;
		}         
    	return $this->db->fetchRow($select);       
    }
    
    public function getOneByParams($row_name,$params=array()){
        $select = $this->db->select()
		               ->from($this->table,array($row_name));
		foreach ($params as $key=>$value) {
		    $select->where("$key=?",$value) ;
		}         
    	return $this->db->fetchOne($select);       
    }
    
    public function getAllByParams($params=array()){
        $select = $this->db->select()
		               ->from($this->table);
		foreach ($params as $key=>$value) {
		    $select->where("$key=?",$value) ;
		}             
    	return $this->db->fetchAll($select);       
    }
    
    /*public function countAllByParams($params=array()){
        $select = $this->db->select()
		               ->from($this->table,array('count(*)'));
		foreach ($params as $key=>$value) {
		    $select->where("$key=?",$value) ;
		}             
    	return $this->db->fetchOne($select);       
    }*/
    
    public function updateRow($data,$where){
        $this->db->update($this->table,$data,$where);
    }
	public function updateTableRow($table,$data,$where){
		$this->db->update($table,$data,$where);
	}
    public function getRowSql($sql){
    	return $this->db->fetchRow($sql);
    }
    public function getDataSql($sql){
    	return $this->db->fetchAll($sql);
    }
    public function getOneSql($sql){
    	return $this->db->fetchOne($sql);
    }
    public function getRow($table,$params=array()){
        $select = $this->db->select()
		               ->from($table);
		foreach ($params as $key=>$value) {
		    $select->where("$key=?",$value) ;
		}             
    	return $this->db->fetchRow($select);  
    }
    public function deleteRow($table,$where){
         $this->db->delete($table, $where);	     
    }
    
    public function setQuery($sql){
	     $this->db->query($sql);  
	} 
	
	public function GetMetaField($keyfield="keywords", $descriptionfield="details", $where="", $orderby, $onpage=0, $limit=10)
    {
        $fields = array();        

        if($keyfield) $fields[] = $keyfield;
        if($descriptionfield) $fields[] = $descriptionfield;

        $select = $this->db->select()
            ->from(array('s'=>$this->table),$fields);
        //добавляем where
        if ($where)  $select->where(trim($where));
        if ($orderby)  $select->order($orderby);
        if ($onpage&&$limit) {
            $select->limitPage($onpage, $limit);
        } else if($limit) $select->limit($limit);

        $result = $this->db->fetchAll($select);

        $keywords = array();
        $details = '';
        foreach($result as $rows) {
            if ($rows[$keyfield]){
                $tmp = explode(",", $rows[$keyfield]);
                foreach ($tmp as $k=>$v) {
                    $v = trim($v);
                    if (!in_array($v, $keywords)) $keywords[] = $v;
                }
            }
            $details .= " ".trim(strip_tags($rows[$descriptionfield]));
        }

        if (sizeof($keywords)) {
            $tmp = implode(", ", $keywords);
            $keywords = $tmp;
        } else $keywords = '';

        $details = str_replace("\r","", $details);
        $details = str_replace("\n","", $details);

        $keywords = preg_replace("[\.>*<\"']", "", $keywords);
        $details = preg_replace("[\.>*<\"']", "", $details);

        if (!trim($keywords) and $details) $keywords = $details;

        if (!trim($details) and $keywords) $details = $keywords;

        $return[0] = mb_substr($keywords, 0, 200,'utf-8');
        $return[1] = mb_substr($details, 0, 200,'utf-8');
        return $return;
    }
      /*
    // уделение записей из базы данных по условию
    public function deleteBySelect($select){
        $sql = $this->_getSelect($select);
        try {
            $db = $this->db;
            $result = $db->exec("DELETE FROM $this->table " . $sql);
        }catch(PDOException $e){
            echo 'Error : '.$e->getMessage();
            echo '<br/>Error sql : ' . "'DELETE FROM $this->table " . $sql . "'"; 
            exit();
        }
        return $result;
    }
     
    // уделение строки из базы данных
    public function deleteRow(){
        $arrayAllFields = array_keys($this->fieldsTable());
        array_walk($arrayAllFields, function(&$val){
            $val = strtoupper($val);
        });
        if(in_array('ID', $arrayAllFields)){            
            try {
                $db = $this->db;
                $result = $db->exec("DELETE FROM $this->table WHERE `id` = $this->id");
                foreach($arrayAllFields as $one){
                    unset($this->$one);
                }
            }catch(PDOException $e){
                echo 'Error : '.$e->getMessage();
                echo '<br/>Error sql : ' . "'DELETE FROM $this->table WHERE `id` = $this->id'"; 
                exit();
            }           
        }else{
            echo "ID table `$this->table` not found!";
            exit;
        }
        return $result;
    }
     
    // обновление записи. Происходит по ID
    */
}
?>
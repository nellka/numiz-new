<?
require_once('Zend/Db.php');

class crons {
 
    protected $db;
  
    function __construct($db){
        $db['driver_options']  = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"');
		$this->db = Zend_Db::factory('PDO_MYSQL', $db);
	 	$this->db->query("SET names 'utf8'");	 	
	}   
	
	public function dropMycoinsTable(){
	     $sql = "delete from `mycoins`";
	     $this->db->query($sql);  
	}
	
	public function createTempTable(){
	    $this->db->query("TRUNCATE shopcoins_search;");
               
        $sql = "INSERT INTO  `shopcoins_search` 
        SELECT shopcoins, price,  `group` , YEAR, dateinsert,  `check` , number, materialtype, parent, materialtypecross, nominal_id, metal_id, condition_id,amount,amountparent,datereprice,dateinsert,theme,novelty 
        FROM  `shopcoins` WHERE (`check` =1 OR  `check` >=4);";
        $this->db->query($sql);        
        
        $this->db->query("TRUNCATE shopcoins_search_name;");
        
        $sql = "INSERT INTO `shopcoins_search_name` 
        SELECT DISTINCT id, name 
        FROM shopcoins_name, shopcoins_search 
        WHERE shopcoins_search.nominal_id = shopcoins_name.id;";
        $this->db->query($sql);  
        
        $this->db->query("TRUNCATE shopcoins_search_group;");
        
        $sql = "insert into shopcoins_search_group 
        SELECT DISTINCT `group`.`group`, `group`.name, `group`.name_en,`group`.groupparent 
        FROM `group`, shopcoins_search WHERE shopcoins_search.`group` = `group`.`group`;";
        $this->db->query($sql);         
        
        $this->db->query("TRUNCATE shopcoins_search_details;");
        
        $sql = "INSERT INTO shopcoins_search_details
        SELECT catalog, details 
        FROM shopcoins_details, shopcoins_search 
        WHERE shopcoins_search.shopcoins = shopcoins_details.catalog;";
        $this->db->query($sql);  
	}
	
	function getLastSubscribesystem(){
        $select = $this->db->select()
		               ->from('subscribesystem')
		               ->order('date desc')
		               ->limit(1);
    	return $this->db->fetchRow($select);       
    }
    
	public function getRowSql($sql){
    	return $this->db->fetchRow($sql);
    }
    
    public function getDataSql($sql){
    	return $this->db->fetchAll($sql);
    }
    
    public function saveMailDb($recipient, $subject, $SendMessage, $headers)
	{
	    $data = array('dateinsert' => time()+600, 
	                  'email'      => $recipient,
	                  'subject'    => $subject,
	                  'message'    => $SendMessage, 
	                  'headers'    => $headers,
	                  'priority'   => 5,
	                  'datesend'   =>0,
	                  'is_new_send_method'=>1);		
	    $this->db->insert('shopcoinssender',$data);
		return true;
	}
	
	public function addSubscribesystem($newsdate,$tboarddate,$blacklistdate,$buycoinsdate,$bibliodate,$advertisedate)
	{
	    $data = array('date'       => time(), 
	                  'news'      => $newsdate,
	                  'tboard'    => $tboarddate,
	                  'blacklist'    => $blacklistdate, 
	                  'buycoins'    => $buycoinsdate,
	                  'biblio'   => $bibliodate,
	                  'advertise'   =>$advertisedate);		
	    $this->db->insert('subscribesystem',$data);
		return true;
	}	
	
	/*
    function unlockTable(){        
    	$this->db->getConnection()->exec('UNLOCK TABLES;');   
    }
    
	//получаем количество всех записей
    
    public function getTableName() {
        return $this->table;
    }
   
    public function addNewRecord($inserarray) {
		$this->db->insert($this->table,$inserarray);         
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
      
    public function updateRow($data,$where){
        $this->db->update($this->table,$data,$where);
    }
	public function updateTableRow($table,$data,$where){
		$this->db->update($table,$data,$where);
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
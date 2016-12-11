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
	
	public function clearNovelty(){
		$select = $this->db->select()
		               ->from('shopcoins',array('shopcoins'))
		               ->where('novelty >0 AND dateinsert < ( unix_timestamp( ) -30 *24 *3600 )')
		               ->order('dateinsert');

		$ids = array();
		
		foreach ($this->db->fetchAll($select) as $row) {
			$ids[] = $row['shopcoins'];
		}
		
		
		if($ids){
			$sql = "delete from shopcoins_view_novelty WHERE shopcoins in ( ".implode(",",$ids)." )";
		    $this->db->query($sql); 
		}
		$sql = "update shopcoins set novelty=0 WHERE novelty >0 AND dateinsert < ( unix_timestamp( ) -30 *24 *3600 )";
	    $this->db->query($sql); 
	    $sql = "update shopcoins_search set novelty=0 WHERE novelty >0 AND dateinsert < ( unix_timestamp( ) -30 *24 *3600 )";
	    $this->db->query($sql); 
	    
	}
	
	public function migrateShopcoinsViewOld(){
		$page = 94;
		$select = $this->db->select()
		               ->from('shopcoins_view_shopcoins_old')
		               ->order('datetime asc')
		               ->limitPage($page, 100000);
		$i =    ($page*100000)+1;           
		foreach ($this->db->fetchAll($select) as $row) {
			$row['id'] = $i;
			$this->db->insert('shopcoins_view_shopcoins',$row);
			$i++;			
		}   
		die("$page");      
    	return ;  
	    

	}
	
	public function getDataForMigrate($start=0,$limit=5000){
		$reserved = " and (".time()." - s1.reserve < 18000) and doubletimereserve< ".time();
		
		$sql = "SELECT  s.shopcoins, s.price, s.group, s.year, s.number, s.materialtype, s.parent, s.nominal_id, s.metal_id, s.condition_id, s.amount, s.dateinsert, s.theme, s.novelty, s1.name, s1.image, s1.image_big, s1.dateorder, s1.emailcheck, s1.accessoryProducer, s1.accessoryColors, s1.accessorySize, s1.width, s1.height, s1.reserve, s1.reserveorder, s1.weight, s1.timereserved, s1.doubletimereserve, s1.userreserve,  details  FROM `shopcoins_search` s, shopcoins s1,shopcoins_details sd   where s.shopcoins=sd.catalog and s.shopcoins=s1.shopcoins and s.check=1 and s.dateinsert<(unix_timestamp()-3*24*3600) $reserved and realization=0 and (s.materialtype in(4,6,3) or (s.materialtype in(1,8) and s.price>35)) limit ".($start*$limit).",$limit";	
		//echo 	$sql;
		return $this->db->fetchAll($sql);
		
	}
	
	public function clearStatistic(){
		//1 месяц где есть user
		$sql = "delete from shopcoins_view_search where user_id>0 and datetime<".(time()-30*24*3600);
		//3 дня где сессия
		$this->db->query($sql);
		$sql = "delete from shopcoins_view_search where session_id>0 and datetime<".(time()-3*24*3600);
		$this->db->query($sql);
		
		$sql = "delete from shopcoins_view_filter where user_id>0 and datetime<".(time()-30*24*3600);
		$this->db->query($sql);
		
		$sql = "delete from shopcoins_view_filter where session_id>0 and datetime<".(time()-3*24*3600);
		$this->db->query($sql);
		
		$sql = "delete from shopcoins_view_shopcoins where user_id>0 and datetime<".(time()-30*24*3600);
		//3 дня где сессия
		$this->db->query($sql);
		
		$sql = "delete from shopcoins_view_shopcoins where session_id>0 and datetime<".(time()-3*24*3600);
		//3 дня где сессия
		$this->db->query($sql);
		
		$mc = memcache_connect('localhost', 11211);
		memcache_flush($mc);
		
		//echo $sql ;
		//
		//$data = array('user_id'  => $this->user_id,					          'session_id' => 0);
				//$this->db->update('shopcoins_view_search',$data,'session_id="'.$session_int.'"');
				//$this->db->update('shopcoins_view_shopcoins',$data,'session_id="'.$session_int.'"');
				//$this->db->update('shopcoins_view_filter',$data,'session_id="'.$session_int.'"');
	}
	
	public function createTempTable(){
	    $this->db->query("TRUNCATE shopcoins_search;");
	    
	    $dayOfWeek = date('w');	 
	    
	    $ifselect = "";
	      
	    $sql = "INSERT INTO  `shopcoins_search`    SELECT shopcoins, price,  `group` , YEAR, 0 as date,  `check` , number, materialtype, parent, materialtypecross, nominal_id, metal_id, condition_id,amount,amountparent,datereprice,IF(realization>0 and dateinsert<(unix_timestamp()-3*24*3600) and shopcoins.provider not in (34,51,55,54), unix_timestamp()-6*24*30*3600, dateinsert),dateorder,theme,novelty      FROM  `shopcoins` WHERE (`check` =1 OR  `check` >=4);";
        $this->db->query($sql);
            
	    if($dayOfWeek=='0'||$dayOfWeek=='6'){
	        //выходные
	        
	       $sql_rand = " SELECT shopcoins  FROM  `shopcoins` WHERE (`check` =1 and (realization=0 or (realization>0 and shopcoins.provider in (34,51,55,54)))) order by rand() limit 500;";
	       $ids = array();
	       foreach ($this->getDataSql($sql_rand) as $row){
	           $ids[] = $row['shopcoins'];
	       }	       
	       $sql_update = " update shopcoins_search  set dateinsert= unix_timestamp() where shopcoins in (".implode(",",$ids).")";
	       $this->db->query($sql_update);    
	    }  else {
	    	 $sql_rand = " SELECT shopcoins  FROM  `shopcoins` WHERE (`check` =1 and (realization=0 or (realization>0 and shopcoins.provider in (34,51,55,54)))) order by rand() limit 100;";
	       $ids = array();
	       foreach ($this->getDataSql($sql_rand) as $row){
	           $ids[] = $row['shopcoins'];
	       }
	       
	       $sql_update = "update shopcoins_search  set dateinsert= unix_timestamp(DATE(NOW())) where shopcoins in (".implode(",",$ids).")";
	       $this->db->query($sql_update);    
	    }    
           
        
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
    
    public function saveMailDb($recipient, $subject, $SendMessage, $headers='',$priority=5)
	{
	    $data = array('dateinsert' => time()+600, 
	                  'email'      => $recipient,
	                  'subject'    => $subject,
	                  'message'    => $SendMessage, 
	                  'headers'    => $headers,
	                  'priority'   => $priority,
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
	public function addRecord($table,$inserarray) {
		$this->db->insert($table,$inserarray);         
        return $this->db->lastInsertId($table);
    }
    public function updateRecord($table,$inserarray,$where) {
		$this->db->update($table,$inserarray,$where);         
        return true;
    }
    
    public function writeMessagePost($user, $message) {

    	$message = str_replace("Не сочтите это письмо рекламой.
    Вас беспокоит администратор Клуба Нумизмат Мандра Богдан.","",$message);
    	
    	$data = array('user'      => $user, 
	                  'message'   => $message,
	                  'datepost'    => time(),
	                  'dateread'    => 0, 
	                  'check'    => 0);		
	    $this->db->insert('messageusers',$data);   	
    	
    	if (file_exists($_SERVER["DOCUMENT_ROOT"]."/messageusers/".$user.".php")) 
    		include $_SERVER["DOCUMENT_ROOT"]."/messageusers/".$user.".php";
    	else 
    		$messageuser='';
    	
    	$messageusertemp = '<?php $messageuser="'.$message.'<br><br>'.$messageuser.'"; ?>';
    	
    	$fp=fopen($_SERVER["DOCUMENT_ROOT"]."/messageusers/".$user.".php" ,"w");
    	if ($fp){
    		fwrite($fp,$messageusertemp);
    		fclose($fp);
    	}
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
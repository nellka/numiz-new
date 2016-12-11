<?php

class model_priceshopcoins extends Model_Base
{	
        
    public function __construct($db){
	    parent::__construct($db);	   
  
	}	

	public function getItem($id){		
	    if(!(int)$id) return false;   
	       	
	    $select = $this->db->select()
                  ->from(array('s'=>$this->table))
                  ->where('priceshopcoins=?',$id)                 
                  ->limit(1);  

    	$select->join(array('group'),'s.group=group.group',array('gname'=>'group.name','ggroup'=>'group.groupparent'))
        		->join(array('pricename'),'s.name=pricename.pricename',array('aname'=>'pricename.name'))
		        ->join(array('pricemetal'),'s.metal=pricemetal.pricemetal',array('ametal'=>'pricemetal.metal'))
		        ->join(array('pricesimbols'),'s.simbols=pricesimbols.pricesimbols',array('asimbols'=>'pricesimbols.simbols'))
		        ->join(array('pricecondition'),'s.condition=pricecondition.pricecondition ',array('acondition'=>'pricecondition.condition')); 
		        
        return $this->db->fetchRow($select);
	}	
    
	public function countAllByParams($WhereParams=array()){	
		$select = $this->db->select();
		
		$select->from(array('s'=>'priceshopcoins'),array('count(*)')); 		
		           
		$select = $this->byParams($select,$WhereParams);			
       
        return $this->db->fetchOne($select);       
	}
		//,$searchid=''
		
		
	public function getItemsByParams($WhereParams=array(),$page=1, $items_for_page=30,$orderby=''){	 

		/*$sql_catalog = "select priceshopcoins.*, group.name as gname, `pricename`.name as aname,
		 `pricemetal`.metal as ametal, pricesimbols.simbols as asimbols, `pricecondition`.`condition` as acondition 
from `priceshopcoins`,`pricename`,pricemetal, pricesimbols, pricecondition, `group` $where and `priceshopcoins`.`name`=`pricename`.`pricename` and priceshopcoins.metal=pricemetal.pricemetal and priceshopcoins.simbols=pricesimbols.pricesimbols and priceshopcoins.condition=pricecondition.pricecondition 
	and priceshopcoins.group=group.group 
	$orderby 
	$limit;";*/	
		$select = $this->db->select();	
		
		$select->from(array('s'=>'priceshopcoins')) 
				->join(array('group'),'s.group=group.group',array('gname'=>'group.name'))
		        ->join(array('pricename'),'s.name=pricename.pricename',array('aname'=>'pricename.name'))
		        ->join(array('pricemetal'),'s.metal=pricemetal.pricemetal',array('ametal'=>'pricemetal.metal'))
		        ->join(array('pricesimbols'),'s.simbols=pricesimbols.pricesimbols',array('asimbols'=>'pricesimbols.simbols'))
		        ->join(array('pricecondition'),'s.condition=pricecondition.pricecondition ',array('acondition'=>'pricecondition.condition')); 
		
		$select = $this->byParams($select,$WhereParams);		
		        
 		
	   $select->order($orderby);

	   if($items_for_page!='all'){
	        $select->limitPage($page, $items_for_page);
	   } 
       

       return $this->db->fetchAll($select);
	} 	
	
	 
	//группы для выборки	
	public function getGroups($WhereParams=array()){	
		
            $select = $this->db->select();			

            $select->from(array('s'=>'priceshopcoins'),array('distinct(s.group)'));	
            $select->where("s.check = 1 and s.amountparent>0");
            return $this->db->fetchAll($select);       
	} 
	public function getConditions($WhereParams=array()){
            $select = $this->db->select();
	        $select->from(array('s'=>'priceshopcoins'),array('condition_id'=>'distinct(s.condition)'));
            $select->join(array('pricecondition'),'s.condition=pricecondition.pricecondition',array('name'=>'pricecondition.condition'))
                    ->order('pricecondition.condition');; 
                    
                   //  ->join(array('pricecondition'),'s.condition=pricecondition.pricecondition ',array('acondition'=>'pricecondition.condition')); 
            unset($WhereParams['condition']);            
            $select = $this->byParams($select,$WhereParams); 
	    return $this->db->fetchAll($select);    
	}
	
	protected function byParams($select,$WhereParams=array()){	
		
        if (isset($WhereParams['group'])&&$WhereParams['group']) {
                $select->where("s.`group` in (".implode(",",$WhereParams['group']).")");
        }  
	
        $select->where("s.check = 1 and s.amountparent>0");
        if (isset($WhereParams['nominal'])) {
        	$select->where("s.name in (".implode(",",$WhereParams['nominal']).")");
        }
		if (isset($WhereParams['condition'])) {             	
        	$select->where("s.condition in (".implode(",",$WhereParams['condition']).")");
        } 
        
		if (isset($WhereParams['simbol'])) {			
			$select->where("s.simbols in(".implode(' or ',$WhereParams['simbol']).')');
		}
		if (isset($WhereParams['metal'])) {              	
        	$select->where("s.metal in (".implode(",",$WhereParams['metal']).")");
        }
 		if (isset($WhereParams['year_p'])) {
        	$where_year = array();
        	//var_dump($WhereParams['year']);
        	foreach ($WhereParams['year_p'] as $year_int){
        		if($year_int[0]>0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
        			$where_year[] = "(`year` >={$year_int[0]} and `year` <={$year_int[1]})";
        		}elseif($year_int[0]==0&&$year_int[1]>0&&$year_int[1]<date('Y',time())){
        			$where_year[] = "(`year` >0 and `year` <={$year_int[1]})";
        		} elseif ($year_int[0]>0){
        			$where_year[] = "(`year` >={$year_int[0]})";
        		} else {
        			$where_year[] = "(`year` <={$year_int[1]})";
        		}        		
        	}
        	$select->where("(".implode(" or ",$where_year).")");
        }

		if (isset($WhereParams['year'])) {
			$select->where("s.year in (".implode(",",$WhereParams['year']).")");
		}
		 //echo "<br><br>".$select->__toString()."<br><br>";
        return  $select;    
	}
	
		
	//получаем металы для выборки
	public function getMetalls($WhereParams=array()){	  	   
	    if(!isset($WhereParams['group'])&&!$WhereParams['group']) return array();
	    
	    $select = $this->db->select();			
	    $select->from(array('s'=>'priceshopcoins'),array('distinct(s.metal)')); 
		$select->join(array('pricemetal'),'s.metal=pricemetal.pricemetal',array('name'=>'pricemetal.metal')); 
        $select->where("s.check = 1 and s.amountparent>0");
        $select->where("s.group in (".implode(",",$WhereParams['group']).")");    	   
    			    
	    return $this->db->fetchAll($select);       
	}
	
    public function getMinYear($WhereParams=array()){
            $select = $this->db->select();
            $select->from(array('s'=>'priceshopcoins'),array('min(year)'));         
            unset($WhereParams['year']);            
            $select = $this->byParams($select,$WhereParams);     	    	 
	    return $this->db->fetchOne($select);
    }
    
    public function getMaxYear($WhereParams=array()){
        $select = $this->db->select();
			
	$select->from(array('s'=>'priceshopcoins'),array('max(year)'));         
         
    	unset($WhereParams['year']);            
        $select = $this->byParams($select,$WhereParams); 

         return $this->db->fetchOne($select);
    }
    
    public function getYears($WhereParams=array()){
	    
	  $select = $this->db->select()
	  		  ->from(array('s'=>'priceshopcoins'),array('year'=>'distinct(year)'))
		      ->order('s.year desc');              
	   unset($WhereParams['year']);            
       $select = $this->byParams($select,$WhereParams); 	  
       
	   return $this->db->fetchAll($select);       
	}
	
	/*public function getMaxPrice($WhereParams=array()){     
 
        $select = $this->db->select()
                       ->from(array('s'=>'priceshopcoins'),array('max(price)'));

    	unset($WhereParams['year']);            
        $select = $this->byParams($select,$WhereParams);     
    	         
	     return $this->db->fetchOne($select);
    }
    
    public function getMinPrice($groups=array(),$nominals=array(),$bydate=0){
         $select = $this->db->select()
                       ->from(array('s'=>'priceshopcoins'),array('min(price)'));

    	unset($WhereParams['pricestart']);     
    	unset($WhereParams['priceend']);        
        $select = $this->byParams($select,$WhereParams);     

	     return $this->db->fetchOne($select);
    }*/
	
	public function getSimbols($WhereParams=array()){
	   $select = $this->db->select()
	  		  ->from(array('s'=>'priceshopcoins'),array('simbol'=>'distinct(s.simbols)'))
	  		  ->join(array('pricesimbols'),'s.simbols=pricesimbols.pricesimbols',array('name'=>'pricesimbols.simbols'))
		      ->order('name desc');              
	  
    	unset($WhereParams['simbol']);   
    	       
        $select = $this->byParams($select,$WhereParams);     
        //echo "<br><br>".$select->__toString()."<br><br>";
	    return $this->db->fetchAll($select);       
	}
	
	public function  getNominal($id){
		if(!(int)$id) return false;
	
		$select = $this->db->select()
	    		->from(array('s'=>'pricename'))
		 	   	->where('pricename=?',$id); 
		 	   	//echo $select->__toString();
		return  $this->db->fetchRow($select);   
		
	}
	
	public function  getMetal($id){
		if(!(int)$id) return false;
	
		$select = $this->db->select()
	    		->from(array('s'=>'pricemetal'))
		 	   	->where('pricemetal=?',$id); 
		 	   	//echo $select->__toString();
		return  $this->db->fetchRow($select);   
		
	}
	
	public function getNominals($WhereParams=array()){

	    if(!$WhereParams['group']) return array();
	    	    
	    $select = $this->db->select()
	    		->from(array('s'=>'priceshopcoins'),array('nominal_id'=>'distinct(s.name)'))
		 	   	->join(array('sn'=>'pricename'), 's.name=sn.pricename',array('name'=>'sn.name'))
		      	->order('position asc');    
        $select->where("s.group in (".implode(",",$WhereParams['group']).")");  
        $select->where("s.check = 1 and s.amountparent>0");
        if($WhereParams['metal']){
        	$select->where("s.metal in (".implode(",",$WhereParams['metal']).")");  
        }
       //$select = $this->byParams($select,$WhereParams);   
        // $WhereParams['group'],$value["metal"]
       $result = $this->db->fetchAll($select);      
    	       
	   return $result;    
	}
	   	
 	public function updateStatus($number=0,$user_id=0){
 		if(!(int)$number||!(int)$user_id) return ;
 		
 		$data = array('checkuser' => $user_id);
 		
		$this->db->update('priceshopcoins',$data,"priceshopcoins = '$number'");
 	}
 	
 	public function getLeftSeo($arraykeyword=array()){
 		if(!$arraykeyword) return array();
 		$sql = "select *,match(`keywords`,`name`,`text`) against('".implode(" ",$arraykeyword)."' in boolean mode) as `coefficient` from shopcoinsbiblio where match(`keywords`,`name`,`text`) against('".implode(" ",$arraykeyword)."' in boolean mode) order by `coefficient` desc, shopcoinsbiblio asc limit 5;";

		return $this->getDataSql($sql);
 	}
 	
 	public function getSeo($group_data=array(),$nominal_data=array(),$metal_data=array()){

    	$select = $this->db->select()
                  ->from('price_seotext')
                  ->limit(1);

    	if($group_data&&$nominal_data){
        	$select->where('group_id in ('.implode(",",$group_data).') and nominal_id in ('.implode(",",$nominal_data).')');
        	$data = $this->db->fetchRow($select);
        	if($data) return $data;
        } elseif($group_data){
        	$select->where('group_id in ('.implode(",",$group_data).') and nominal_id=0');        	
        } else {
        	$select->where('group_id =0 and nominal_id=0');        	
        }
        
        if($metal_data) {
        	$select->where('metal_id in ('.implode(",",$metal_data).')');
        } else $select->where('metal_id =0');
        
        if($year_data) {
        	$select->where('year in ('.implode(",",$metal_data).')');  
        } else $select->where('year = 0');  
            	
        $data = $this->db->fetchRow($select);
        echo "<!--".$select->__toString()."-->";
        
        return $data;
	}

}

?>
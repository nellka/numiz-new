<?php
class model_orderdetails extends Model_Base 
{	
	public $shopcoinsorder;
	
	static $WeightCoins = 5;
	static $reservetime = 18000;
	public function __construct($db,$shopcoinsorder){
	    parent::__construct($db);
	    $this->shopcoinsorder = $shopcoinsorder;	  
	}	
	
	public function getIdentity(){
	    return $this->shopcoinsorder;
    }
	 
	public function getMySum(){
		//получаем сумму по заказу
	  $select = $this->db->select()
		               ->from($this->table,array('sum(orderdetails.amount*shopcoins.price)'))
		               ->join(array('shopcoins'),'orderdetails.catalog=shopcoins.shopcoins')
		               ->where('orderdetails.order=?',$this->getIdentity()); 
		
       return (integer)$this->db->fetchOne($select);       
	}
	
	public function deletePostion($id){
	    $this->db->delete($this->table,"`order`=".$this->getIdentity()." and catalog=".$id);	   
	}
	public function deletePostionHelpshopcoinsorder($id){
	    $this->db->delete('helpshopcoinsorder',"shopcoins='$id' and reserveorder='".$this->getIdentity()."'");	   
	}
	public  function updateItemCount($data,$id){
	  	$this->updateRow($data,"orderdetails.order=".$this->getIdentity()." and catalog=".$id);    	
    	return ;
	}
	public  function removeOrderCache($user_id){
	    $clientdiscount = $this->getClientdiscount($user_id,$this->getIdentity());	    
	    $this->cache->remove("orderdetails_".$clientdiscount.'_'.$this->getIdentity().'_'.$user_id);
	    $this->cache->remove("bascet_".$this->getIdentity());
	}
	
	//пересчет карзины
	public function basket($user_id){	    
		$clientdiscount = $this->getClientdiscount($user_id,$this->getIdentity());		
		$dataBasket = $this->forBasket($clientdiscount);
		$bascetsum = $dataBasket["mysum"];
		$_SESSION['bascetsum'] = $bascetsum;
		
		$bascetsumclient = $dataBasket["mysumclient"];
		if ($bascetsumclient >= $bascetsum) 
			$bascetsumclient=0;
		$bascetweight = $dataBasket["myweight"];
		$bascetinsurance = $bascetsum * 0.04;
		
		$mymaterialtype =($bascetsum>0)?$dataBasket["mymaterialtype"]:1;
		
		$bascetamount = $this->getCounter();
		$_SESSION["shopcoinsorderamount"] =  $bascetamount;
		
		//var_dump($bascetamount);
		//DIE();
		/*$orderstarttime = $this->getMinDate();
		
		$bascetreservetime = (floor((self::$reservetime+$orderstarttime-time())/3600)>=1?floor((self::$reservetime+$orderstarttime-time())/3600)." ч. ":"").
		(floor((self::$reservetime+$orderstarttime-time()-floor((self::$reservetime+$orderstarttime-time())/3600)*3600)/60)." мин.");
		
		//расчет почтового сбора
		if ($mymaterialtype!=0)	{
			$bascetpostweightmin = $PostZone[1] + $PackageAddition[1]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
			$bascetpostweightmax = $PostZone[5] + $PackageAddition[5]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
		} else {
			$bascetpostweightmin = $PostZone1[1] + $PackageAddition[1]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
			$bascetpostweightmax = $PostZone1[5] + $PackageAddition[5]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
		}*/
		return  array('bascetsum'=>$bascetsum,'bascetamount'=>$bascetamount);		
    }
    
    public function getClientdiscount($user_id=0){			 	
	 	if($user_id){
	 		 $select = $this->db->select()
		               ->from('order','count(*)')
		               ->where('user =?',$user_id)
		               ->where('`user`<>811 and `check`=1')
		               ->where('`order`<>?',$this->getIdentity())
		               ->where('date>?',time()-365*24*60*60);
    		               
        	$orders = $this->db->fetchRow($select);    
			if ( $orders>=3) return  1;			
	 	}
	 	return  0;
	 }
	 
    public function forBasket($clientdiscount){	 	
	 	if(!$dataBasket = $this->cache->load("bascet_".$this->getIdentity())){ 	 
    	 	//var_dump($this->cache->load("bascet_".$this->getIdentity()));
    	 	//выборка корзины - вес считается по формуле pi*d^3*10.5/80
    		$sql = "select sum(orderdetails.amount*if
    				(orderdetails.amount>=shopcoins.amount5 and shopcoins.price5>0,shopcoins.price5,
    					if
    					(orderdetails.amount>=shopcoins.amount4 and shopcoins.price4>0,shopcoins.price4,
    						if
    						(orderdetails.amount>=shopcoins.amount3 and shopcoins.price3>0,shopcoins.price3,
    							if
    							(orderdetails.amount>=shopcoins.amount2 and shopcoins.price2>0,shopcoins.price2,
    								if
    								(orderdetails.amount>=shopcoins.amount1 and shopcoins.price1>0,shopcoins.price1,
    									".($clientdiscount==1?"if(shopcoins.clientprice>0,shopcoins.clientprice,shopcoins.price)":"shopcoins.price")."
    								)
    							)
    						)
    					)
    				)
    			) as mysum,  
    		sum(orderdetails.amount*if
    				(orderdetails.amount>=shopcoins.amount5 and shopcoins.price5>0,shopcoins.price5,
    					if
    					(orderdetails.amount>=shopcoins.amount4 and shopcoins.price4>0,shopcoins.price4,
    						if
    						(orderdetails.amount>=shopcoins.amount3 and shopcoins.price3>0,shopcoins.price3,
    							if
    							(orderdetails.amount>=shopcoins.amount2 and shopcoins.price2>0,shopcoins.price2,
    								if
    								(orderdetails.amount>=shopcoins.amount1 and shopcoins.price1>0,shopcoins.price1,
    									if
    									(shopcoins.clientprice>0,shopcoins.clientprice,shopcoins.price)
    								)
    							)
    						)
    					)
    				)
    			) as mysumclient,
    		sum(orderdetails.amount*if
    				(shopcoins.weight=0,
    					if
    					(
    					shopcoins.materialtype=1,round(0.412*shopcoins.width*shopcoins.width*shopcoins.width/1000),
    						if 
    						(
    						shopcoins.materialtype=2||shopcoins.materialtype=8||shopcoins.materialtype=6,1,
    							if
    							(
    								shopcoins.materialtype=4,100,
    								if
    								(
    									shopcoins.materialtype=7,40,".self::$WeightCoins."
    								)
    							)
    						)
    					)
    				,shopcoins.weight)
    			) as myweight,
    		sum(if(shopcoins.materialtype=2,0,1)) as mymaterialtype 
    		from orderdetails, shopcoins 
    		where orderdetails.order='".$this->getIdentity()."'  and orderdetails.status=0
    		and orderdetails.catalog=shopcoins.shopcoins;";
    		$dataBasket = $this->getRowSql($sql);
    		
    		$this->cache->save($dataBasket, "bascet_".$this->getIdentity());
	 	}	
		return $dataBasket;
	 }
	
	//получаем данные о товаре в заказе
	public function getPostion($id,$use_status = false){
	    $select = $this->db->select()
		               ->from($this->table)
		               ->where('`order`=?',$this->getIdentity())
		               ->where('catalog=?',$id);
	   if($use_status)  $select->where('status=0'); 		
       return $this->db->fetchRow($select);   	   
	}
	
	public function getCounter(){
		$select = $this->db->select()
		               ->from($this->table,array('count(catalog)'))
		               ->where($this->table.'.order=?',$this->getIdentity())
		               ->where('status=0'); 		
       return $this->db->fetchOne($select);       
	}	
	
	public function getMinDate(){		
		$select = $this->db->select()
		               ->from($this->table,array('min(date)'))
		               ->where($this->table.'.order=?',$this->getIdentity())
		               ->where('status=0'); 		
       return $this->db->fetchOne($select);       
	}
		
	public function getDetails($user_id){
	    $clientdiscount = $this->getClientdiscount($user_id);		
        if(!$orderdetails = $this->cache->load("orderdetails_".$clientdiscount.'_'.$this->getIdentity().'_'.$user_id)){	     
            $sql = "select o.*, o.amount as oamount, if
    				(o.amount>=c.amount5 and c.price5>0,c.price5,
    					if
    					(o.amount>=c.amount4 and c.price4>0,c.price4,
    						if
    						(o.amount>=c.amount3 and c.price3>0,c.price3,
    							if
    							(o.amount>=c.amount2 and c.price2>0,c.price2,
    								if
    								(o.amount>=c.amount1 and c.price1>0,c.price1,
    									".($clientdiscount==1?"if(c.clientprice>0,c.clientprice,c.price)":"c.price")."
    								)
    							)
    						)
    					)
    				)as price, g.name as gname, c.materialtype, c.year, c.metal, c.group,c.name,c.number,c.image_small,c.image_big,c.details,c.width,c.height,c.condition,c.accessoryProducer,c.accessoryColors,c.accessorySize,c.weight from `orderdetails` as o, 
    shopcoins as c, `group` as g 
    where o.order='".$this->getIdentity()."' and o.catalog = c.shopcoins and (c.`check` in(1,4,5) ".($user_id==811?"or c.`check`>3":"").") and g.`group`=c.`group`  and o.status=0 and o.order>0
    order by c.materialtype;";
    	     $orderdetails = $this->db->fetchAll($sql);
    	     $this->cache->save($orderdetails, "orderdetails_".$clientdiscount.'_'.$this->getIdentity().'_'.$user_id);	
        }
        return $orderdetails;
	}
	
}
<?php
class model_orderdetails extends Model_Base 
{	
	public $shopcoinsorder;
	
	static $WeightCoins = 5;
	static $reservetime = 18000;
	static $PriceLatter = 16;
	static  $PostZone = array(1 => 138.80,2 => 140.70,3 => 146.40,4 => 178.30,5 => 199.00);
	static  $PackageAddition= array(1 => 12.00,2 => 13.90,3 => 20.30,4 => 29.20,5 => 33.70);
	static  $WeightPostLatter = 28;
	static $WeightPostBox = 100;
	public function __construct($db,$shopcoinsorder=0){
	    parent::__construct($db);
	    $this->shopcoinsorder = $shopcoinsorder;	  
	}	
	
	public function getIdentity(){
	    return $this->shopcoinsorder;
    }
    
	public function setShopcoinsorder($order){
	    $this->shopcoinsorder = $order;
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
		$orderstarttime = $this->getMinDate();
		
		$bascetreservetime = (floor((self::$reservetime+$orderstarttime-time())/3600)>=1?floor((self::$reservetime+$orderstarttime-time())/3600)." ч. ":"").
		(floor((self::$reservetime+$orderstarttime-time()-floor((self::$reservetime+$orderstarttime-time())/3600)*3600)/60)." мин.");
		
		//расчет почтового сбора
		if ($mymaterialtype!=0)	{
			$bascetpostweightmin = self::$PostZone[1] + self::$PackageAddition[1]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
			$bascetpostweightmax = self::$PostZone[5] + self::$PackageAddition[5]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
		} else {
			$bascetpostweightmin = $PostZone1[1] + self::$PackageAddition[1]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
			$bascetpostweightmax = $PostZone1[5] + self::$PackageAddition[5]*($bascetweight<500?0:ceil(($bascetweight-500)/500));
		}

		return  array('bascetsum'=>$bascetsum,
		              'bascetamount'=>$bascetamount,
		              'bascetsumclient'=>$bascetsumclient,		              
		              'bascetweight'=>$bascetweight,
		              'bascetreservetime'=>$bascetreservetime,
		              'bascetpostweightmin'=>$bascetpostweightmin,
		              'bascetpostweightmax'=>$bascetpostweightmax,
		              'bascetinsurance'=>$bascetinsurance);		
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
	            sum(orderdetails.amount*
				if
				(orderdetails.amount>=shopcoins.amount5 and shopcoins.price5>0,shopcoins.price5,
					if
					(orderdetails.amount>=shopcoins.amount4 and shopcoins.price4>0,shopcoins.price4,
						if
						(orderdetails.amount>=shopcoins.amount3 and shopcoins.price3>0,shopcoins.price3,
							if
							(orderdetails.amount>=shopcoins.amount2 and shopcoins.price2>0,shopcoins.price2,
								if
								(orderdetails.amount>=shopcoins.amount1 and shopcoins.price1>0,shopcoins.price1,0)
							)
						)
					)
				)
			) as mysumamount,
			sum(orderdetails.amount*if(shopcoins.materialtype=12,shopcoins.price,0)) as vipcoinssum,
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

	//упаковка книг, аксесуров
/*
$sql = "select count(catalog) as postcounter from orderdetails as o, shopcoins as s
where ".(sizeof($shopcoinsorder)>1?"o.order in (".implode(",", $shopcoinsorder).")":"o.order='".$shopcoinsorder."'")."
and o.catalog=s.shopcoins ".($checking?"":"and s.`check`='1'")."
and s.materialtype<>'1' and s.materialtype<>2 and o.status=0;";*/

	public function getPaking(){
		$select = $this->db->select()
			->from($this->table,array('count(catalog)'))
			->join('shopcoins','orderdetails.catalog=shopcoins.shopcoins',array())
			->where($this->table.'.order=?',$this->getIdentity())
			->where('shopcoins.materialtype<>1 and shopcoins.materialtype<>2 and shopcoins.check=1 and orderdetails.status=0');
		return $this->db->fetchOne($select);
	}

	public function getPost($postindex){
		$select = $this->db->select()
			->from('Post')
			->where('PostIndex=?',$postindex);
		return $this->db->fetchRow($select);
	}

	public function getCounter(){
		$select = $this->db->select()
		               ->from($this->table,array('count(catalog)'))
		               ->where($this->table.'.order=?',$this->getIdentity())		               
		               ->where('status=0'); 
       return $this->db->fetchOne($select);       
	}
	/*
$sql = "select count(catalog) as counter from orderdetails as o, shopcoins as s
//where ".(sizeof($shopcoinsorder)>1?"o.order in (".implode(",", $shopcoinsorder).")":"o.order='".$shopcoinsorder."'")."
and o.catalog=s.shopcoins ".($checking?"":"and s.`check`='1'")." and o.status=0;";*/

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
	
	public function  getOrderDetails(){
	 /*$sql = "select o.*, s.*, g.name as gname, o.amount as orderamount, s.amount as samount 
				from  as o, shopcoins as s, `group` as g 
				where o.`order`='$shopcoinsorder'
				and o.catalog=s.shopcoins and o.status=0
				and (s.`check`=1 or s.`check`>3)
				and s.`group` = g.`group`;";
				$result = mysql_query($sql);*/
	 
	 	
	    $select = $this->db->select()
                  ->from(array('o'=>'orderdetails'),array('*', 'orderamount' => 'o.amount'))
                  ->join(array('s'=>'shopcoins'),'o.catalog=s.shopcoins',array('samount' => 's.amount','*'))
                  ->join(array('g'=>'group'),'s.group = g.group',array('gname' => 'g.name'))
                  ->where('o.order=?',$this->shopcoinsorder)
                  ->where('o.status=0 and (s.`check`=1 or s.`check`>3)');	

	    return $this->db->fetchAll($select);
	 }
	 public function  getDeleted(){
	 /*$sql = "select o.*, s.*
			from orderdetails as o, shopcoins as s
			where o.`order`='$shopcoinsorder'
			and o.catalog=s.shopcoins
			and s.`check`='0' and o.status=0;";*/
	   $select = $this->db->select()
                  ->from(array('o'=>'orderdetails'))
                  ->join(array('s'=>'shopcoins'),'o.catalog=s.shopcoins')                  
                  ->where('o.order=?',$this->shopcoinsorder)
                  ->where('o.status=0 and s.check=0');	
	    return $this->db->fetchAll($select);
	 }
	 public function  deleteItem($id){
	     $this->db->delete($this->table,"`order` = '".$this->shopcoinsorder."' and catalog=$id");	     
	 }
	 
	public function PostSum($postindex,$clientdiscount,$shopcoinsorder=0){
	    if($shopcoinsorder) $this->shopcoinsorder = $shopcoinsorder;
	    
        $rows = $this->forBasket($clientdiscount);

        $bascetsum = $rows["mysum"];
        $amountbascetsum = $rows['mysumamount'];
        $vipcoinssum = $rows['vipcoinssum'];
        
        
    	$bascetweight = $rows["myweight"];
    	$bascetamount = $this->getCounter();
        $postcounter = $this->getPaking();

        $sql = "select coupon.* from ordercoupon, coupon where ".(sizeof($this->shopcoinsorder)>1?"ordercoupon.order in (".implode(",",$this->shopcoinsorder).")":"ordercoupon.order='".$this->shopcoinsorder."'")." and ordercoupon.order>0 and ordercoupon.`check`=1 and coupon.coupon=ordercoupon.coupon group by coupon.coupon order by coupon.type desc, coupon.dateinsert desc;";

    	$discountcoupon = 0;
    	$arraycoupcode = array();
		$typec = 1;
		foreach ($this->db->fetchAll($sql) as $rows2) {
			
			if ($rows2['type']==2 && $typec==1) {

				$discountcoupon = floor(($bascetsum-$amountbascetsum-$vipcoinssum)*$rows2['sum']/100);
				$typec = 2;
				$arraycoupcode[] = "VIP";
			}
			elseif ($rows2['type']==1 && ($typec==1 || ($typec==2 && $rows2['order']==0))) {
			
				$discountcoupon += $rows2['sum'];
				$arraycoupcode[] = strtoupper($rows2['code']);
			}
		}

    	if ($discountcoupon<0)
    		$discountcoupon = 0;

    	$bascetsum = $bascetsum - $discountcoupon;
    	if ($bascetsum<0)
    		$bascetsum = 0;
    
    	$bascetweight = $rows["myweight"];
    	
    	if ($bascetsum>0)
    		$mymaterialtype = $rows["mymaterialtype"];
    	else
    		$mymaterialtype = 1;
    	
    	
        $suminsurance = $this->getSuminsurance();
        if ($suminsurance>0){
        	$bascetinsurance = $suminsurance * 0.04;
        } else {
        	$bascetinsurance = $bascetsum * 0.04;
        }
        
    	if ($postcounter)
        	$bascetpostweight = $bascetweight + self::$WeightPostBox;
        else
        	$bascetpostweight = $bascetweight + self::$WeightPostLatter;
        	
    	$rows = $this->getPost($postindex);
    	$PostZoneNumber = 5;
    	if($postindex){
        	//тариф по зоне обслуживания
        	//select * from Post where PostIndex='600023';
        	$PostZoneNumber = $rows["PostZone"];
        	$PostRegion = ($rows["Region"]?$rows["Region"]:$rows["Autonom"]);
        	$PostCity = ($rows["City"]?$rows["City"]:$PostRegion);
        }
    
        if (!$PostZoneNumber)	$PostZoneNumber = 5;
        if ($mymaterialtype!=0){
        	$PostZonePrice = self::$PostZone[$PostZoneNumber] + self::$PackageAddition[$PostZoneNumber]*($bascetpostweight<500?0:ceil(($bascetpostweight-500)/500));
        }else {
        	$PostZonePrice = self::$PostZone1[$PostZoneNumber] + self::$PackageAddition[$PostZoneNumber]*($bascetpostweight<500?0:ceil(($bascetpostweight-500)/500));
        }
        $PostAllPrice = $PostZonePrice + self::$PriceLatter + $bascetinsurance + $bascetsum;

    	/*if ($checking) {
    		$sql = "select s.* from orderdetails as o, shopcoins as s 
    		where ".(sizeof($shopcoinsorder)>1?"o.order in (".implode(",", $shopcoinsorder).")":"o.order='".$shopcoinsorder."'")."
    		and o.catalog=s.shopcoins and o.status=0;";
    		$result = mysql_query($sql);
    		$BascetNameArray = Array();
    		while ($rows = mysql_fetch_array($result))
    			$BascetNameArray[] = $rows["name"];
    		
    		$BascetName = implode(", ", $BascetNameArray);
    	}*/

    	return array('bascetamount'=>$bascetamount,'PostZoneNumber'=>$PostZoneNumber,'PostZonePrice'=>$PostZonePrice,'PostAllPrice'=>$PostAllPrice,'suminsurance'=>$suminsurance,'amountbascetsum'=>$amountbascetsum,'bascetsum'=>$bascetsum,'bascetpostweight'=>$bascetpostweight,'PostAllPrice'=>$PostAllPrice);

	 }
	 
	 public function getSuminsurance(){
		$select = $this->db->select()
			->from('order',array('sum(suminsurance)'))
			->where('`order`=?',$this->shopcoinsorder);
		return $this->db->fetchOne($select);
	}
}
<?php

class model_order extends Model_Base 
{	
    
    public function __construct($db,$shopcoinsorder=0,$user_id=0){
	    parent::__construct($db);
	    $this->shopcoinsorder = $shopcoinsorder;
	    $this->user_id = $user_id;
    }	
	public function getUserfio(){
	    $select = $this->db->select()
                  ->from('order',array('userfio'))
                  ->where('user=?',$this->user_id)
                  ->order('date desc')
                  ->limit(1);
        return $this->db->fetchOne($select);
	}

	public function getPreviosOrder(){
		/*select * from `order` where `user`='$cookiesuser' and `check`=1 and SendPost=0 and sum>=500 order by  limit 1;";*/
		$select = $this->db->select()
			->from('order')
			->where('user =?',$this->user_id)
			->where('`check`=1 and SendPost=0 and sum>=500')
			->order('date desc')
			->limit (1);
		
		return  $this->db->fetchRow($select);
	}
	public function getAdminOrderDetails($order){
		/*select o.*, o.admincheck, u.fio, u.email from `order` as o left join user as u on o.user=u.user	where o.order='".$order."";*/
		$select = $this->db->select()
			->from(array('o'=>'order'))
			->join(array('u'=>'user'),'o.user=u.user',array('u.fio', 'u.email'))
			->where('o.order =?',$order);		
		return  $this->db->fetchRow($select);
	}

	public function sumOrders($id){
		$sql = "select sum(orderdetails.amount*shopcoins.price) as mysum
				from orderdetails, shopcoins
				where orderdetails.order in('".$this->shopcoinsorder."','".$id."')
				and orderdetails.catalog=shopcoins.shopcoins and orderdetails.status=0;";
		return  $this->db->fetchOne($sql);
	}

	public function alreadyByeCoins(){
		$sql = "select shopcoins1.*, group.name as gname from shopcoins,`catalogshopcoinsrelation`, `order`, orderdetails, orderdetails as orderdetails1,
		catalogshopcoinsrelation as catalogshopcoinsrelation1,shopcoins as shopcoins1, `group`
		where
			orderdetails.catalog=shopcoins.shopcoins
			and `orderdetails`.`order` = '".$this->shopcoinsorder."'
			and shopcoins.shopcoins=catalogshopcoinsrelation.shopcoins
			and catalogshopcoinsrelation.catalog=catalogshopcoinsrelation1.catalog
			and catalogshopcoinsrelation1.shopcoins=orderdetails1.catalog
			and orderdetails1.order=order.order
			and order.user='".$this->user_id."'
			and order.check=1
			and order.order<>'".$this->shopcoinsorder."'
			and orderdetails1.catalog=shopcoins1.shopcoins
			and shopcoins1.materialtype in(1,4,7,8)
				and shopcoins1.group=group.group;";

		/*$select = $this->db->select()
			->from(array('s'=>'shopcoins'))
			->join(array('g'=>'group'),'s.group = g.group',array('gname' => 'g.name'))
			->join(array('o'=>'order'),'o.catalog=s.shopcoins',array())
			->join(array('od'=>'orderdetails'),'cr.shopcoins=od.catalog',array())
			->join(array('cr'=>'catalogshopcoinsrelation'),'cr.shopcoins=od.catalog',array())
			->where('o.order=?',$this->shopcoinsorder)
			->where('o.status=0 and (s.`check`=1 or s.`check`>3)');*/

		return $this->db->fetchAll($sql);
	}
	public function alreadyByeCoins2($arraycoins=array())
	{
		$sql = "select shopcoins1.*, group.name as gname from shopcoins, `order`, orderdetails, orderdetails as orderdetails1,
		shopcoins as shopcoins1, `group`
		where
			orderdetails.catalog=shopcoins.shopcoins
			and `orderdetails`.`order` = '".$this->shopcoinsorder."'
			and shopcoins.parent=shopcoins1.parent
			and orderdetails1.order=order.order
			and order.user='".$this->user_id."'
			and order.check=1
			and order.order<>'".$this->shopcoinsorder."'
			and orderdetails1.catalog=shopcoins1.shopcoins
			and ((shopcoins1.materialtype=1 and shopcoins1.parent>0) or (shopcoins1.materialtype in(4,7,8)))
			and shopcoins1.group=group.group " . (sizeof($arraycoins) ? "and shopcoins1.shopcoins not in(" . implode(",", $arraycoins) . ")" : "") . ";";

		return $this->db->fetchAll($sql);
	}

	public function getOrder(){
	    $select = $this->db->select()
                  ->from($this->table)
                  ->where('`order`=?',$this->shopcoinsorder);
        return $this->db->fetchRow($select);
	}

	public function getUseCoupon($coupon)
	{
		$select = $this->db->select()
			->from('ordercoupon')
			->where('`order` =?',$this->shopcoinsorder)
			->where('coupon =?',$coupon);

		return $this->db->fetchRow($select);
	}
	public function getOrdergiftcertificate($order)
	{
		$select = $this->db->select()
			->from('ordergiftcertificate')
			->where('`order` =?',$order)
			->where('`check`=1');

		return $this->db->fetchRow($select);
	}

	public function tempUseCoupon($coupon){
		$select = $this->db->select()
			->from('ordercoupon',array('count(*)'))
			->where('`order` =?',$this->shopcoinsorder)
			->where('coupon =?',$coupon);
		if (!$this->db->fetchOne($select)) {
			$data = array(
				'coupon'=>$coupon,
				'order' =>$this->shopcoinsorder,
				'dateinsert'=> time(),
				'check'=>1);
			$this->db->insert('ordercoupon',$data);
		}
	}

	public function getInOffice(){
	    $select = $this->db->select()
                  ->from('order')
                  ->where('user=?',$this->user_id)
                  ->where('order.check=1 and SendPost>0')
                  ->order('date desc')
                  ->limit(10);
        return $this->db->fetchAll($select);
	}
	
	
	public function getLastOrders(){
	    $select = $this->db->select()
                  ->from('order',array('*','OrderOrder'=>'if (`order`.`order`>`order`.ParentOrder, `order`.`order`, `order`.ParentOrder)'))
                  ->where('user=?',$this->user_id)
                  ->where('`check`=1')
                  ->order(array('OrderOrder desc','date desc'));
	    if ($this->user_id == 245796) {
		     $select->where('date>?',time()-86400*365*2)
		         ->where('payment<2');			
		} elseif ($this->user_id == 811) {
		     $select->where('date>?',time()-86400*100*2);			
		} elseif($this->user_id != 279931) {
		      $select->where('date>?',time()-86400*365*2);				
		}
		
	    return $this->db->fetchAll($select);
	 }
	 
	 public function getSumOfOrder(){
	     $select = $this->db->select()
                  ->from('order',array('sumallorder'=>'sum(orderdetails.amount*shopcoins.price)','ReminderComment'))
                  ->join('orderdetails','orderdetails.order=order.order',array())
                  ->join('shopcoins','orderdetails.catalog=shopcoins.shopcoins',array())
                  ->where('(order.user='.$this->user_id.' and order.user<>811 and order.check=1 and (order.ReceiptMoney=0 and order.Reminder!=3 and order.SendPost=0)) or (order.order='.$this->shopcoinsorder.')')                
                  ->where('orderdetails.status=0');
         return   $this->db->fetchRow($select);  
	 }
	 
	 public function countFullAmount(){
        $select = $this->db->select()
		               ->from('order',array('count(orderdetails.amount)'))
		               ->join('orderdetails','order.order=orderdetails.order')
		               ->where('order.user=?',$this->user_id)		               
		               ->where('order.check=1 and orderdetails.status=0'); 	
		               	
       return $this->db->fetchOne($select);       
    }
    
    public function OrderSum(){
        $sql = "select sum(orderdetails.amount*shopcoins.price) as mysum, sum(orderdetails.amount*if
        		(orderdetails.amount>=shopcoins.amount5 and shopcoins.price5>0,shopcoins.price5,
        			if
        			(orderdetails.amount>=shopcoins.amount4 and shopcoins.price4>0,shopcoins.price4,
        				if
        				(orderdetails.amount>=shopcoins.amount3 and shopcoins.price3>0,shopcoins.price3,
        					if
        					(orderdetails.amount>=shopcoins.amount2 and shopcoins.price2>0,shopcoins.price2,
        						if
        						(orderdetails.amount>=shopcoins.amount1 and shopcoins.price1>0,shopcoins.price1,
        							if(shopcoins.clientprice>0,shopcoins.clientprice,shopcoins.price)
        						)
        					)
        				)
        			)
        		)
        	) as mysumclient
        from orderdetails, shopcoins 
        where orderdetails.order='".$this->shopcoinsorder."' 
        and orderdetails.catalog=shopcoins.shopcoins and orderdetails.status=0
        and (shopcoins.`check` in(1,4,5) ".($this->user_id==811?"or shopcoins.`check`>3":"").");";
        return $this->db->fetchRow($sql);
	 }
	 
	 public function OrderShopcoinsDetails($clientdiscount,$order){
	      $sql = "select o.*, c.name, if(o.amount>=c.amount5 and c.price5>0,c.price5,
				if 
				(o.amount>=c.amount4 and c.price4>0,c.price4,
					if
					(o.amount>=c.amount3 and c.price3>0,c.price3,
						if
						(o.amount>=c.amount2 and c.price2>0,c.price2,
							if
							(o.amount>=c.amount1 and c.price1>0,c.price1,".($clientdiscount==1?"if(c.clientprice>0, c.clientprice, c.price)":"c.price").")
						)
					)
				)
			) as price, c.image, c.metal, c.year, 
			c.condition, c.number, c.shopcoins, g.name as gname, c.materialtype, c.details
			 from `orderdetails` as o left join shopcoins as c 
			on o.catalog = c.shopcoins 
			left join `group` as g on c.group=g.group 
			where o.order='".$order."' and o.typeorder=1 and o.status=0 order by c.materialtype, c.number;";
	      return $this->db->fetchAll($sql);
	 }
	 
	 public function OrderSumDetails($clientdiscount){
	   $sql = "select o.*, o.amount as oamount, s.number, s.name, s.year, s.materialtype, if(o.amount>=s.amount5 and s.price5>0,s.price5,
					if 
					(o.amount>=s.amount4 and s.price4>0,s.price4,
						if
						(o.amount>=s.amount3 and s.price3>0,s.price3,
							if
							(o.amount>=s.amount2 and s.price2>0,s.price2,
								if
								(o.amount>=s.amount1 and s.price1>0,s.price1,".($clientdiscount==1?"if(s.clientprice>0, s.clientprice, s.price)":"s.price").")
							)
						)
					)
				) as price, g.name as gname,
			if
			(o.amount>=s.amount5 and s.price5>0,s.price5,
				if
				(o.amount>=s.amount4 and s.price4>0,s.price4,
					if
					(o.amount>=s.amount3 and s.price3>0,s.price3,
						if
						(o.amount>=s.amount2 and s.price2>0,s.price2,
							if
							(o.amount>=s.amount1 and s.price1>0,s.price1,0)
						)
					)
				)
			) as priceamount 
		from orderdetails as o, shopcoins as s, `group` as g 
		where o.`order`='".$this->shopcoinsorder."'
		and o.catalog=s.shopcoins
		and (s.`check`=1 or s.`check`>3)
		and s.`group` = g.`group` and o.status=0
		order by s.materialtype;";
	 
		return $this->db->fetchAll($sql);
	 }
		
	 public function getDelivery(){
	      $select = $this->db->select()
                  ->from('order',array('count(*)'))
                  ->where('user=?',$this->user_id)
                  ->where('delivery=3 and order.order!='.$this->shopcoinsorder.' and SendPost=0 and ReceiptMoney=0');	   
	       return $this->db->fetchOne($select);
	 }
    //Страховки
	//where ".(sizeof($shopcoinsorder)>1?"`order`.`order` in (".implode(",",$shopcoinsorder).")":"`order`.`order`='".$shopcoinsorder."'").";"
	public function getSuminsurance(){
		$select = $this->db->select()
			->from('order',array('sum(suminsurance)'))
			->where('`order`=?',$this->shopcoinsorder);
		return $this->db->fetchOne($select);
	}

	 public function getMetro(){
	      $select = $this->db->select()
                  ->from('metro')
                  ->order('metro');
	       return $this->db->fetchAll($select);
	 }
    public function getMetroName($id){
	      $select = $this->db->select()
                  ->from('metro',array('name','price'))
                  ->where('metro=?',$id);
	       return $this->db->fetchRow($select);
	 }

	 
	 public function getCurrentOrders(){	     
	    $select = $this->db->select()
                  ->from('order',array('order','ReminderComment'))
                  ->where('user=?',$this->user_id)
                  ->where('user<>811 and markadmincheck=2 and mark=2')
                  ->where('`check`=1')
                  ->order(array('order'));	   
	    return $this->db->fetchAll($select);
	 }
}
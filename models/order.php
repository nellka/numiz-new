<?php

class model_order extends Model_Base 
{	
    
    public function __construct($db,$shopcoinsorder=0,$user_id=0){
	    parent::__construct($db);
	    $this->shopcoinsorder = $shopcoinsorder;
	    $this->user_id = $shopcoinsorder;
    }	
	public function getUserfio(){
	    $select = $this->db->select()
                  ->from('order',array('userfio'))
                  ->where('user=?',$this->user_id)
                  ->order('date desc')
                  ->limit(1);
        return $this->db->fetchOne($select);
	}
	
	public function getInOffice(){
	    $select = $this->db->select()
                  ->from('order')
                  ->where('user=?',$this->user_id)
                  ->where('order.check=1 and SendPost>0')
                  ->order('date desc')
                  ->limit(10);
        return $this->db->fetchRow($select);
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
<?php

class model_order extends Model_Base 
{	
	public function getClientdiscount($user_id=0,$shopcoinsorder){			 	
	 	if($user_id){
	 		 $select = $this->db->select()
		               ->from('order','count(*)')
		               ->where('user =?',$user_id)
		               ->where('`user`<>811 and `check`=1')
		               ->where('`order`<>?',$shopcoinsorder)
		               ->where('date>?',time()-365*24*60*60);
    		               
        	$orders = $this->db->fetchRow($select);    
			if ( $orders>=3) return  1;			
	 	}
	 	return  0;
	 }
	 
	 public function forBasket($clientdiscount,$shopcoinsorder,$WeightCoins=5){
	 	
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
									shopcoins.materialtype=7,40,$WeightCoins
								)
							)
						)
					)
				,shopcoins.weight)
			) as myweight,
		sum(if(shopcoins.materialtype=2,0,1)) as mymaterialtype 
		from orderdetails, shopcoins 
		where orderdetails.order='".$shopcoinsorder."'  and orderdetails.status=0
		and orderdetails.catalog=shopcoins.shopcoins;";
		return $this->getRowSql($sql);
	 }
	 
	 public function getDetails($clientdiscount,$shopcoinsorder,$user_id){	     
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
where o.order='".$shopcoinsorder."' and o.catalog = c.shopcoins and (c.`check` in(1,4,5) ".($user_id==811?"or c.`check`>3":"").") and g.`group`=c.`group`  and o.status=0 and o.order>0
order by c.materialtype;";
	     return $this->db->fetchAll($sql);
	 }
}
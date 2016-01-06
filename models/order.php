<?php

class model_order extends Model_Base 
{	
	
	 
	 
	 
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
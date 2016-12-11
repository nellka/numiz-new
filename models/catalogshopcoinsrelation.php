<?php

class model_catalogshopcoinsrelation extends Model_Base 
{		   
	public function getRelations2($id,$mycatalog1){
		$sql = "SELECT sum(`orderdetails1`.amount) as odsum, catalogshopcoinsrelation1.catalog, shopcoins1.*, g.name as gname 
		FROM 
		`catalogshopcoinsrelation`, `order`, orderdetails, orderdetails as orderdetails1, 
		catalogshopcoinsrelation as catalogshopcoinsrelation1, shopcoins as shopcoins1, `group` as g  
		where 
			catalogshopcoinsrelation.catalog= '$mycatalog1' 
			and `orderdetails`.catalog = catalogshopcoinsrelation.shopcoins 
			and `orderdetails`.`order` = `order`.order 
			and `order`.`check`=1 
			and orderdetails1.`order` = `order`.`order` 
			and `orderdetails1`.catalog= catalogshopcoinsrelation1.shopcoins 
			and catalogshopcoinsrelation1.catalog <> '$mycatalog1' 
			and catalogshopcoinsrelation1.shopcoins = shopcoins1.shopcoins and shopcoins1.shopcoins<>'$id' 
			and shopcoins1.`check`=1 and shopcoins1.`group` = `g`.`group` group by shopcoins1.shopcoins order by odsum desc limit 10;";
		return $this->db->fetchAll($sql);
	}
	public function getReserved($id,$user_id){
	   /*$sql_info = "select shopcoins.*, catalogshopcoinssubscribe.catalog 
						from `shopcoins`, catalogshopcoinsrelation, catalogshopcoinssubscribe  
						WHERE shopcoins.shopcoins = '".$rows['shopcoins']."' and shopcoins.materialtype = '1'
						and catalogshopcoinsrelation.shopcoins = shopcoins.shopcoins
						and catalogshopcoinssubscribe.catalog = catalogshopcoinsrelation.catalog 
						and catalogshopcoinssubscribe.user = '".$rows['userreserve']."';";*/
						
	   $select = $this->db->select()
                  ->from(array('catalogshopcoinssubscribe'),array('catalog'))
                  ->join(array('catalogshopcoinsrelation'),'catalogshopcoinssubscribe.catalog = catalogshopcoinsrelation.catalog',array())
                  ->join(array('shopcoins'),'catalogshopcoinsrelation.shopcoins = shopcoins.shopcoins',array())
                  ->where('catalogshopcoinssubscribe.user=?',$user_id)
                  ->where('shopcoins.shopcoins=?',$id)
                  ->where('shopcoins.materialtype = 1');	
	    return $this->db->fetchOne($select);
	}
		
}
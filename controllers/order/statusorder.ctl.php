<?
$useradmin=1;
$type = "shopcoins";

$tpl['order']['_Title'] = "Клуб Нумизмат | Монетная лавка";
$tpl['order']['_Keywords'] = "Доллар, Евро, Серебро, Золото, Платина, Палладий, нумизматика, фалеристика, бонистика, монеты, каталог, император, государь, ордена, медаль, значки, Петр, антиквариат, коллекция, Николай II, Екатерина II, Романовы, копейка, рубль, деньга, серебро, золото, медь, империя, ОБМЕН ВАЛЮТЫ, рефераты, Великая отечественная война";
$tpl['order']['_Description'] = "Интернет портал включает большой каталог монет царской России с подробным описанием, возможность создать свой нумизматический интернет магазин, доска обьявлений, статьи и публикации по нумизматике и истории России, адреса магазинов торгующих антиквариатом, ссылки на другие сайты.";

$user_admin_email = "administrator@numizmatik.ru";

$ReminderKey = request('ReminderKey');
$Reminder = (int) request('Reminder');
$submit =  request('submit');
$ReminderComment =  request('ReminderComment');
$complected = (int) request('complected');

$mark = (int) request('mark');

if ($ReminderKey) $ReminderKey = str_replace("'","",$ReminderKey);
	
$user = (int) request('user');

$sql = "select `order`.*, user.fio from `order` 
		left join user on order.user = user.user 
		where `order`.user='$user' and ReminderKey='".$ReminderKey."';";

$rows = $shopcoins_class->getRowSql($sql);
if(!$tpl['user']['user_id']){
	$tpl['error'] = "Пожалуйста, авторизуйтесь на сайте.";	
} else if (!$rows){
	$tpl['error']= "Извините, ошибочный запрос...";	
} else {
	if (!$submit) {	
		$sum = 0;	
		$type = $rows["type"];
		//выбираем содержимое заказа
		if ($type=="shopcoins")	{
			// монеты ----------------------------------------------------------------
			$sql_content = "select o.*, c.name, c.price, c.image, c.year,
			g.name as gname	 from `orderdetails` as o left join shopcoins as c 
			on o.catalog = c.shopcoins 
			left join `group` as g on c.group=g.group 
			where o.order='".$rows["order"]."' and o.typeorder=1 and o.status=0 order by o.orderdetails;";
			
			$tpl['result_content'] = $shopcoins_class->getDataSql($sql_content);									
		} elseif ($type == "Book") {
			//книги - ----------------------------------------------------------------
			$sql_content = "select o.*, c.*
			 from `orderdetails` as o left join Book as c 
			on o.catalog = c.BookID where o.order='".$rows["order"]."' and o.typeorder=1 and o.status=0 order by o.orderdetails;";
			$tpl['result_content'] = $shopcoins_class->getDataSql($sql_content);		
		} elseif ($type == "Album") {
			//аксессуары -------------------------------------------------------------
			$sql_content = "select o.*, c.*
			 from `orderdetails` as o left join Album as c 
			on o.catalog = c.AlbumID where o.order='".$rows["order"]."' and o.typeorder=1 and o.status=0 order by o.orderdetails;";
			$tpl['result_content'] = $shopcoins_class->getDataSql($sql_content);
		}		
	} else {		
		if ($Reminder!=3) $complected = 0;
		$data_update = array('Reminder'=>$Reminder,
		                     'ReminderComment'=>strip_tags($ReminderComment),
		                     'ReminderCommentDate'=>time(),
		                     'mark'=>$mark,
		                     'complected'=>$complected);
		                     
		/*$sql = "update `order` set Reminder='$Reminder', ReminderComment='".strip_tags($ReminderComment)."', 
		ReminderCommentDate = '".time()."', mark='".intval($mark)."',complected=$complected 
		where `order`.user='$user' and `order`.ReminderKey='".$ReminderKey."';";
		//echo $sql;*/
		$shopcoins_class->updateTableRow('order',$data_update,"order.user='$user' and order.ReminderKey='".$ReminderKey."'");
		//$result = mysql_query($sql);		
	}
}
?>
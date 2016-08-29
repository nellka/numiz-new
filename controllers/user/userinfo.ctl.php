<?

$user_id = (integer)request('user_id');

$data = $user_class->getUserDataByID($user_id);

if ($data){
	
	$sql = "select sum(if(type=1,1,0)) as changeuser, 
	sum(if(type=1,0,1)) as collectionuser 
	from catalognewmycatalog where user='$user';";
	
	$rows = $user_class->getRowSql($sql);
	$data["changeuser"] = $rows["changeuser"];
	$data["collectionuser"] = $rows["collectionuser"];	
}
?>
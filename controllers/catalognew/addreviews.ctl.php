<?

require_once($cfg['path'].'/models/catalognew.php');

$catalog = (integer)request('catalog');
$details = request('details');

$tpl['addreviews']['error'] = array();
$tpl['subscribe']['send_status'] = false;

if(!$tpl['user']['user_id']){
	$tpl['addreviews']['error'][] = "Только авторизированные пользователи могут отставлять отзыв!";
}

if ($_SERVER['REQUEST_METHOD']=='POST'){
	
	
	if (!$details)	$tpl['addreviews']['error'][] = "Заполните поле отзыв";

	if (!sizeof($tpl['addreviews']['error'])){
		$catalognew_class = new model_catalognew($db_class);
		$catalognew_class->addReview($catalog,$tpl['user']['user_id'],$details);
		$tpl['subscribe']['send_status'] = true;	
	}
	
}
?>
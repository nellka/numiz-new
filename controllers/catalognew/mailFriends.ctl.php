<?
header('Pragma: no-cache');

require_once($cfg['path'] . '/models/mails.php');
require_once('Zend/Validate/EmailAddress.php');

$validator = new Zend_Validate_EmailAddress();
$mail_class = new mails(true);

$data_result = array();
$data_result['error'] = null;
$data_result['value'] = false;

$fio = request('fio');
$email = request('email');
$emailfriend =  request('emailfriend');
$messageform =  request('messageform');
$link =  request('link');

if (!$validator->isValid($email)) {
    $data_result['error'] = "Неверный Email отправителя";
} else if (!$validator->isValid($emailfriend)) {
    $data_result['error'] = "Неверный Email друга";
} else if ($fio&&$email&&$emailfriend&&$messageform&&$link){
    $data['mailfrom'] = $email;
    $data['fiofrom'] = $fio;
    $data['mailfriend'] = $emailfriend;
    $data['message'] = $messageform;
    $data['link'] = $link;
    //echo $recipient;
    $mail_class->CatalogCoinsLetter($data);
    //mail($recipient, $subject, $message, $headers); //покупателю
} else {
    $data_result['error'] = "Заполните все поля!";
}

echo json_encode($data_result);
die();
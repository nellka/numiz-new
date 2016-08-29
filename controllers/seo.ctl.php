<?
$id = (int) request('id');
if(!$id) $id = 1;

require($cfg['path'].'/models/seotext.php');

$seotext_class = new model_seotext($db_class);

$tpl['seo'] = $seotext_class->getItem($id);

$tpl['seo']['_Description'] = $tpl['seo']["description"]?$tpl['seo']["description"]:$tpl['seo']["title"];
$tpl['seo']['_Title'] = $tpl['seo']["title"];
$tpl['seo']['_Keywords'] = $tpl['seo']["keywords"]?$tpl['seo']["keywords"]:$tpl['seo']["title"];

$tpl['seo']['next'] = $seotext_class->getNext($id);

?>
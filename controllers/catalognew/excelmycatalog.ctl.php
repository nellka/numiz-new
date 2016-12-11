<?
if ($tpl['user']['user_id']){
	$user = $tpl['user']['user_id'];
} else die();

$body = '<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--[if gte mso 9]><xml>
 <o:OfficeDocumentSettings>
  <o:DownloadComponents/>
  <o:LocationOfComponents HRef="msowc.cab"/>
  <o:Colors>
   <o:Color>
    <o:Index>4</o:Index>
    <o:RGB>#006699</o:RGB>
   </o:Color>
   <o:Color>
    <o:Index>35</o:Index>
    <o:RGB>#EBE4D4</o:RGB>
   </o:Color>
  </o:Colors>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
<style>
<!--table
	{mso-displayed-decimal-separator:"\,";
	mso-displayed-thousand-separator:" ";}
@page
	{margin:.98in .79in .98in .79in;
	mso-header-margin:.5in;
	mso-footer-margin:.5in;}
tr
	{mso-height-source:auto;}
col
	{mso-width-source:auto;}
br
	{mso-data-placement:same-cell;}
.style21
	{color:#006699;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Arial Cyr";
	mso-generic-font-family:auto;
	mso-font-charset:204;
	mso-style-name:Гиперссылка;
	mso-style-id:8;}
a:link
	{color:#006699;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Arial Cyr";
	mso-generic-font-family:auto;
	mso-font-charset:204;}
a:visited
	{color:teal;
	font-size:7.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Arial, sans-serif;
	mso-font-charset:0;}
.style0
	{mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	white-space:nowrap;
	mso-rotate:0;
	mso-background-source:auto;
	mso-pattern:auto;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Cyr";
	mso-generic-font-family:auto;
	mso-font-charset:204;
	mso-protection:locked visible;
	mso-style-name:Обычный;
	mso-style-id:0;}
td
	{mso-style-parent:style0;
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Cyr";
	mso-generic-font-family:auto;
	mso-font-charset:204;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	mso-protection:locked visible;
	white-space:nowrap;
	mso-rotate:0;}
-->
</style>
</head>

<body>';

$sql = "select catalognew.*, group.name as gname, shopcoins_metal.name as mname from catalognew, `group`, shopcoins_metal , catalognewmycatalog where catalognew.agreement >= 0 and catalognew.materialtype in(1,8) and catalognewmycatalog.user = '$user' and catalognewmycatalog.catalog = catalognew.catalog and catalognew.group=group.group and catalognew.metal = shopcoins_metal.id";
$result = $shopcoins_class->getDataSql($sql);

$body .= "<table border=1><tr><td><strong>Страна</strong></td><td><strong>Номинал</strong></td><td><strong>Год</strong></td><td><strong>Металл</strong></td><td><strong>Описание</strong></td></tr>";

foreach ($result as $rows) {
	$body .= "<tr><td>".$rows['gname']."</td><td>".$rows['name']."</td><td>".$rows['yearstart']."</td><td>".$rows['mname']."</td><td>".$rows['details']."</td></tr>";
}
$body .= "</table></body></html>";

header("Content-Type: application/txt");
header("Content-Disposition: attachment; filename=mycatalog.xls");

header("Content-Length: ".strlen($body));

echo $body;

?>
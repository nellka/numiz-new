<?
header('Content-type: text/html; charset=utf-8');
?><HTML><HEAD><TITLE>Квитанция</TITLE>
<meta http-equiv="content-type" content="text/html; charset=windows-1251">
</HEAD>
<BODY bgColor=#ffffff>

<?

if (!$FIO)
	$FIO = "";
if (!$NUMBER)
	$NUMBER = "";
if (!$ADRESS)
	$ADRESS = "";
if (!$SUM)
	$SUM = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if (!$KOP)
	$KOP = "";

$tmp = explode(".", $SUM);
$SUM = $tmp[0];
if (strlen($tmp[1])>0)
{
	if (strlen($tmp[1])==1)
		$KOP = $tmp[1]."0";
	else
		$KOP = $tmp[1];
}
else
{
	$KOP = "&nbsp;&nbsp;";
}


$sbrfblank = "
<STYLE type=text/css>
H1 {FONT-SIZE: 12pt}
P {MARGIN-TOP: 6px; MARGIN-BOTTOM: 6px}
UL {MARGIN-TOP: 6px; MARGIN-BOTTOM: 6px}
OL {MARGIN-TOP: 6px; MARGIN-BOTTOM: 6px}
H1 {MARGIN-TOP: 6px; MARGIN-BOTTOM: 6px}
TD {FONT-SIZE: 9pt}
SMALL {FONT-SIZE: 7pt}
BODY {FONT-SIZE: 10pt}
</STYLE>

<TABLE style=\"WIDTH: 180mm; HEIGHT: 145mm\" cellSpacing=0 cellPadding=0 
  border=0 bgColor=#ffffff><TBODY>
  <TR vAlign=top>
    <TD style=\"BORDER-RIGHT: medium none; BORDER-TOP: #000000 1pt solid; BORDER-LEFT: #000000 1pt solid; WIDTH: 50mm; BORDER-BOTTOM: medium none; HEIGHT: 70mm\" align=middle><B>Извещение</B>
	<BR><FONT style=\"FONT-SIZE: 53mm\">&nbsp;<BR></FONT><B>Кассир</B> </TD>
    <TD style=\"BORDER-RIGHT: #000000 1pt solid; BORDER-TOP: #000000 1pt solid; BORDER-LEFT: #000000 1pt solid; BORDER-BOTTOM: medium none\" align=middle>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD align=right><SMALL><I>Форма № ПД-4</I></SMALL></TD></TR>
        <TR>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\">Индивидуальный Предприниматель Мандра Богдан Михайлович</TD></TR>
        <TR>
          <TD align=middle><SMALL>(наименование получателя платежа)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD style=\"WIDTH: 37mm; BORDER-BOTTOM: #000000 1pt solid\">504908219824</TD>
          <TD style=\"WIDTH: 9mm\">&nbsp;</TD>
          <TD 
        style=\"BORDER-BOTTOM: #000000 1pt solid\">40802810538050005372</TD></TR>
        <TR>
          <TD align=middle><SMALL>(ИНН получателя платежа)</SMALL></TD>
          <TD><SMALL>&nbsp;</SMALL></TD>
          <TD align=middle><SMALL>(номер счета получателя 
        платежа)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD>в&nbsp;</TD>
          <TD style=\"WIDTH: 73mm; BORDER-BOTTOM: #000000 1pt solid\">Московский банк ОАО Сбербанк России 3805/01702</TD>
          <TD align=right>БИК&nbsp;&nbsp;</TD>
          <TD 
          style=\"WIDTH: 33mm; BORDER-BOTTOM: #000000 1pt solid\">044525225</TD></TR>
        <TR>
          <TD></TD>
          <TD align=middle><SMALL>(наименование банка получателя платежа)</SMALL></TD>
          <TD></TD>
          <TD></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Номер кор./сч. банка получателя 
          платежа&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" width=\"100%\">30101810400000000225</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD style=\"WIDTH: 60mm; BORDER-BOTTOM: #000000 1pt solid\">Оплата услуг.(НДС не облагается) Заказ № ___NUMBER___ &nbsp;</TD>
          <TD style=\"WIDTH: 2mm\">&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\">&nbsp;</TD></TR>
        <TR>
          <TD align=middle><SMALL>(наименование платежа)</SMALL></TD>
          <TD><SMALL>&nbsp;</SMALL></TD>
          <TD align=middle><SMALL>(номер лицевого счета (код) 
            плательщика)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Ф.И.О. плательщика&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" width=\"100%\">___FIO___&nbsp;</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Адрес плательщика&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" width=\"100%\">___ADRESS___</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD>Сумма платежа&nbsp;<FONT 
            style=\"TEXT-DECORATION: underline\">&nbsp;___SUM___&nbsp;</FONT>&nbsp;руб.&nbsp;<FONT 
            style=\"TEXT-DECORATION: underline\">&nbsp;___KOP___&nbsp;</FONT>&nbsp;коп.</TD>
          <TD align=right>&nbsp;&nbsp;Сумма платы за 
            услуги&nbsp;&nbsp;_____&nbsp;руб.&nbsp;____&nbsp;коп.</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD>Итого&nbsp;&nbsp;_______&nbsp;руб.&nbsp;____&nbsp;коп.</TD>
          <TD align=right>&nbsp;&nbsp;<______>________________ 20".date("y")." 
        г.</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD><SMALL>С условиями приема указанной в платежном документе суммы, 
            в т.ч. с суммой взимаемой платы за услуги банка, ознакомлен и 
            согласен.</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD align=right><B>Подпись плательщика 
        _____________________</B></TD></TR></TBODY></TABLE></TD></TR>
  <TR vAlign=top>
    <TD 
    style=\"BORDER-RIGHT: medium none; BORDER-TOP: #000000 1pt solid; BORDER-LEFT: #000000 1pt solid; WIDTH: 50mm; BORDER-BOTTOM: #000000 1pt solid; HEIGHT: 70mm\" 
    align=middle><FONT 
      style=\"FONT-SIZE: 50mm\">&nbsp;<BR></FONT><B>Квитанция</B><BR><FONT 
      style=\"FONT-SIZE: 8pt\">&nbsp;<BR></FONT><B>Кассир</B> </TD>
    <TD 
    style=\"BORDER-RIGHT: #000000 1pt solid; BORDER-TOP: #000000 1pt solid; BORDER-LEFT: #000000 1pt solid; BORDER-BOTTOM: #000000 1pt solid\" 
    align=middle>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD align=right><SMALL>&nbsp;</SMALL></TD></TR>
        <TR>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\">Индивидуальный Предприниматель Мандра Богдан Михайлович</TD></TR>
        <TR>
          <TD align=middle><SMALL>(наименование получателя платежа)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD 
            style=\"WIDTH: 37mm; BORDER-BOTTOM: #000000 1pt solid\">504908219824</TD>
          <TD style=\"WIDTH: 9mm\">&nbsp;</TD>
          <TD 
        style=\"BORDER-BOTTOM: #000000 1pt solid\">40802810538050005372</TD></TR>
        <TR>
          <TD align=middle><SMALL>(ИНН получателя платежа)</SMALL></TD>
          <TD><SMALL>&nbsp;</SMALL></TD>
          <TD align=middle><SMALL>(номер счета получателя 
        платежа)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD>в&nbsp;</TD>
          <TD style=\"WIDTH: 73mm; BORDER-BOTTOM: #000000 1pt solid\">Московский банк ОАО Сбербанк России 3805/01702</TD>
          <TD align=right>БИК&nbsp;&nbsp;</TD>
          <TD 
          style=\"WIDTH: 33mm; BORDER-BOTTOM: #000000 1pt solid\">044525225</TD></TR>
        <TR>
          <TD></TD>
          <TD align=middle><SMALL>(наименование банка получателя 
            платежа)</SMALL></TD>
          <TD></TD>
          <TD></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Номер кор./сч. банка получателя 
          платежа&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" 
            width=\"100%\">30101810400000000225</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD style=\"WIDTH: 60mm; BORDER-BOTTOM: #000000 1pt solid\">Оплата услуг. (НДС не облагается) Заказ № ___NUMBER___&nbsp;</TD>
          <TD style=\"WIDTH: 2mm\">&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\">&nbsp;</TD></TR>
        <TR>
          <TD align=middle><SMALL>(наименование платежа)</SMALL></TD>
          <TD><SMALL>&nbsp;</SMALL></TD>
          <TD align=middle><SMALL>(номер лицевого счета (код) 
            плательщика)</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Ф.И.О. плательщика&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" width=\"100%\">___FIO___&nbsp;</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD noWrap width=\"1%\">Адрес плательщика&nbsp;&nbsp;</TD>
          <TD style=\"BORDER-BOTTOM: #000000 1pt solid\" width=\"100%\">___ADRESS___&nbsp;</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD>Сумма платежа&nbsp;<FONT 
            style=\"TEXT-DECORATION: underline\">&nbsp;___SUM___&nbsp;</FONT>&nbsp;руб.&nbsp;<FONT 
            style=\"TEXT-DECORATION: underline\">&nbsp;___KOP___&nbsp;</FONT>&nbsp;коп.</TD>
          <TD align=right>&nbsp;&nbsp;Сумма платы за 
            услуги&nbsp;&nbsp;_____&nbsp;руб.&nbsp;____&nbsp;коп.</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD>Итого&nbsp;&nbsp;_______&nbsp;руб.&nbsp;____&nbsp;коп.</TD>
          <TD align=right>&nbsp;&nbsp;<______>________________ 20".date("y")." 
        г.</TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD><SMALL>С условиями приема указанной в платежном документе суммы, 
            в т.ч. с суммой взимаемой платы за услуги банка, ознакомлен и 
            согласен.</SMALL></TD></TR></TBODY></TABLE>
      <TABLE style=\"MARGIN-TOP: 3pt; WIDTH: 122mm\" cellSpacing=0 cellPadding=0 
      border=0>
        <TBODY>
        <TR>
          <TD align=right><B>Подпись плательщика 
        _____________________</B></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H1>Внимание! В стоимость заказа не включена комиссия банка.</H1>
<OL>
  <LI>Распечатайте квитанцию. Если у вас нет принтера, перепишите верхнюю часть 
  квитанции и заполните <br>по этому образцу стандартный бланк квитанции в вашем 
  банке. 
  <LI>Вырежьте по контуру квитанцию. 
  <LI>Оплатите квитанцию в любом отделении банка. 
  <LI>Сохраните квитанцию до получения заказа. </LI></OL>";

$sbrfblank = str_replace("___FIO___", $FIO, $sbrfblank);
$sbrfblank = str_replace("___ADRESS___", $ADRESS, $sbrfblank);
$sbrfblank = str_replace("___NUMBER___", $NUMBER, $sbrfblank);
$sbrfblank = str_replace("___SUM___", $SUM, $sbrfblank);
$sbrfblank = str_replace("___KOP___", $KOP, $sbrfblank);

echo $sbrfblank;
?>
<script language=JavaScript>
	window.print();
</script>
</BODY></HTML>
<?die();?>

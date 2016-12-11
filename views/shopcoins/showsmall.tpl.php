<div class="wraper clearfix showitem" >  
<?
if($tpl['show']['error']['no_coins']){?>
    <div class="error">К сожалению, указанного товара не существует</div>
<?} else {
	$rows_main['notSRB'] = true;
    include($cfg['path'].'/views/shopcoins/item/item-showsmall.tpl.php');  
   
}?>
<br style="clear: both;">
<center><a class="qwk button24" href='<?=$cfg['site_dir']?>shopcoins/<?=$rows_main['rehref']?>' style="width:300px;margin: 20px 0 0;color: white;">Полная информация о монете</a></center>
</div>

<script type="text/javascript">

$(document).ready(function() {
    $('.down').click(function () {
        var input = $(this).parent().find('input[type=text]');
        var id = $(input).attr('id');
        var $amountall = $(this).parent().find('input[type=hidden]');
        var count = parseInt(input.val()) - 1;
        count = count < 1 ? 1 : count;
        input.val(count);
        $('#'+id).val(count);
        input.change();
        return false;
    });
    $('.up').click(function () {
        var input = $(this).parent().find('input[type=text]');
        var $amountall = $(this).parent().find('input[type=hidden]');
        count = parseInt(input.val()) + 1;
        count = count > $amountall.val() ? $amountall.val() : count;
        var id = $(input).attr('id');
        input.val(count);
        $('#'+id).val(count);
        input.change();
        return false;
    });
});
</script>
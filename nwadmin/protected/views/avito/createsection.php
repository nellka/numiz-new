<div class="form">

<?php 

$form=$this->beginWidget('CActiveForm',array('id'=>'post-form','action'=>Yii::app()->createUrl('//avito/'.($modelcreate->sid?'update/?id='.$modelcreate->sid:'createsection')),)); ?>
	<?php if (Yii::app()->user->hasFlash('error')) { ?>
	<div class="flash-error">
	    <?php echo Yii::app()->user->getFlash('error') ?>
	</div>
	<?php } ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php  echo CHtml::errorSummary($modelcreate); ?>	
   
	<div class="row">
	       <?php echo $form->dropDownList($modelcreate,'materialtype',Shopcoins::$sections,array('onchange'=>"getGroups()",'style'=>'width:150px')) ?>
		<?php echo $form->error($modelcreate,'group_id'); ?>
		<?php echo $form->dropDownList($modelcreate,'group_id',ShopcoinsCurrent::model()->getGroups(1),array('onchange'=>"getNominals();")); ?>
		<?php echo $form->error($modelcreate,'group_id'); ?>
		<?php echo $form->dropDownList($modelcreate,'nominal_id',ShopcoinsCurrent::model()->getNominals(),array('onchange'=>"getMetals();",'encode'=>false,'style'=>'width:300px')); ?>
		
		<?php echo $form->dropDownList($modelcreate,'metal_id',array(),array('onchange'=>"getYears()",'encode'=>false)); ?>
		<?php echo $form->error($modelcreate,'nominal_id'); ?>
		<?php echo $form->error($modelcreate,'metal_id'); ?>
	</div>
	<div class="row">
   		Год: от
   		<?php echo $form->dropDownList($modelcreate,'year_from',array(),array('onchange'=>"getCount()")); ?>
   		до 
   		<?php echo $form->dropDownList($modelcreate,'year_to',array(),array('onchange'=>"getCount()")); ?>
   		Приоретет: <?php echo $form->textField($modelcreate,'priority',array('size'=>10,'maxlength'=>4)); ?>
   		<?php echo $form->error($modelcreate,'priority'); ?>
   		<?php echo $form->error($modelcreate,'year_from'); ?>
   		<?php echo $form->error($modelcreate,'year_to'); ?>
   		Количество: <span id='mcount'></span>
   		<? echo CHtml::Button('Показать',array('onclick'=>'showItems()')); ?>

   		
   	</div>   	

	<div class="row buttons">
	
		<?php echo CHtml::submitButton('Создать' ); ?>
	</div>

<?php $this->endWidget(); ?>

<script>
$(document ).ready(function() {
    getMetals()
    getYears();
    getCount();
});
function getNominals(){
     $.ajax({
        "type":"POST",
        "url":"<?=CHtml::normalizeUrl(array("/shopcoinscurrent/getnominals"))?>",
        "data":$('#post-form').serialize(),
        "success":function(data){
        	$('#post-form #Avitosection_nominal_id').html(data);
        	getMetals();        	
        }
     });
}

function getGroups(){
     $.ajax({
        "type":"POST",
        "url":"<?=CHtml::normalizeUrl(array("/shopcoinscurrent/getgroups"))?>",
        "data":$('#post-form').serialize(),
        "success":function(data){
        	$('#post-form #Avitosection_group_id').html(data);        	
        	getNominals();        	
        }
     });
}
function getMetals(){
     $.ajax({
        "type":"POST",
        "url":"<?=CHtml::normalizeUrl(array("/shopcoinscurrent/getmetals"))?>",
        "data":$('#post-form').serialize(),
        "success":function(data){
        	$('#post-form #Avitosection_metal_id').html(data);        	
        	getYears();
        }
     });
}

function getCount(){
     $.ajax({
        "type":"POST",
        "url":"<?=CHtml::normalizeUrl(array("/shopcoinscurrent/getcount"))?>",
        "data":$('#post-form').serialize(),
        "success":function(data){
        	$('#mcount').text(data);
        }
     });
}

function getYears(){
     $.ajax({
        "type":"POST",
        "url":"<?=CHtml::normalizeUrl(array("/shopcoinscurrent/getyears"))?>",
        "data":$('#post-form').serialize(),
        "success":function(data){

        	$('#post-form #Avitosection_year_from').html(data);
        	$('#post-form #Avitosection_year_to').html(data);
        	getCount();
        }
     });
}
function showItems(){
	
	window.open('<?=CHtml::normalizeUrl(array("avito/showtemp"))?>'+'/?'+$('#post-form').serialize());
}

</script>
</div>
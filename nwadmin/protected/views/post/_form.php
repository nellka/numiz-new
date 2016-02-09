<?
Yii::import('ext.redactor.ImperaviRedactorWidget');
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm',array('id'=>'post-form')); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo CHtml::errorSummary($model); ?>
	
    <div class="row">
		<?php echo $form->labelEx($model,'materialtype'); ?>
		<?php echo $form->dropDownList($model,'materialtype',Shopcoins::$sections, array('ajax' => array(
                        'type'   => 'POST',
                        'url'    => $this->createUrl('/shopcoins/getgroups'),
                        'data'=>array('materialtype'=>'js:this.value'),
                        'update' => '#'.CHtml::activeId($model,'group_id')                    
                        )
                    )); ?>
		<?php echo $form->error($model,'materialtype'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'group_id'); ?>
		<?php echo $form->dropDownList($model,'group_id',Shopcoins::model()->getGroups($model->materialtype?$model->materialtype:Shopcoins::SECTION_COINS), 
		array('encode'=>false,
		      'ajax' => array(
                        'type'   => 'POST',
                        'url'    => $this->createUrl('/shopcoins/getnominals'),
                        'data'=>array(
                            'materialtype'=>'js:$("#Shopcoinsseotext_materialtype").val()',
                            'group_id'=>'js:this.value'
                         ),
                        'update' => '#'.CHtml::activeId($model,'nominal_id')                    
                        )
                    )
        ); ?>
		<?php echo $form->error($model,'group_id'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'nominal_id'); ?>
		<?php echo $form->dropDownList($model,'nominal_id',Shopcoins::model()->getNominals($model->materialtype,$model->group_id),array('encode'=>false)); ?>
		<?php echo $form->error($model,'nominal_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>80,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
	 <?php echo $form->labelEx($model,'text'); ?>
	 <?  $this->widget('ImperaviRedactorWidget', array(
                // Используем атрибут модели
                'model' => $model,
                'attribute' => 'text',
                // Доки тут: <a href="http://imperavi.com/redactor/docs/">http://imperavi.com/redactor/docs/</a>
                'options' => array(
                  'lang' => 'ru',
                  'toolbar' => true,
                ),
              ));?>
    <?php echo $form->error($model,'text'); ?>	
	</div>	

	<div class="row">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->dropDownList($model,'active',Shopcoinsseotext::$statuses); ?>
		<?php echo $form->error($model,'active'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

<script>
function showGroups(){
     $.ajax({
        "type":"POST",
        "url":"<?=CHtml::normalizeUrl(array("/shopcoins/getgroups"))?>",
        "data":{'materialtype':$('#Shopcoinsseotext_materialtype').val()},
        "success":function(data){
          console.log(data);
        }
     });
}

</script>
</div><!-- form -->
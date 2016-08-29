<?
Yii::import('ext.redactor.ImperaviRedactorWidget');
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm',array('id'=>'post-form')); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo CHtml::errorSummary($model); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>80,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
   <div class="row">
		<?php echo $form->labelEx($model,'alias'); ?>
		<?php echo $form->textField($model,'alias',array('size'=>80,'maxlength'=>255,'placeholder'=>'Если поле пустое то автоматически')); ?>
		<?php echo $form->error($model,'alias'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'keywords'); ?>
		<?php echo $form->textField($model,'keywords',array('size'=>80,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'keywords'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>80,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	

	<div class="row">
	 <?php echo $form->labelEx($model,'text'); ?>
	 <?$this->widget('ImperaviRedactorWidget', array(
                // Используем атрибут модели
                'model' => $model,
                'attribute' => 'text',
                // Доки тут: <a href="http://imperavi.com/redactor/docs/">http://imperavi.com/redactor/docs/</a>
                'options' => array(
                  'lang' => 'ru',
                  'toolbar' => true,
                  'imageGetJson' => Yii::app()->createAbsoluteUrl('/post/imageGetJson'),
                    'imageUpload' => Yii::app()->createAbsoluteUrl('/post/imageUpload'),
                    'clipboardUploadUrl' => Yii::app()->createAbsoluteUrl('/post/imageUpload'),
                    'fileUpload' => Yii::app()->createAbsoluteUrl('/post/fileUpload')
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
</script>
</div><!-- form -->
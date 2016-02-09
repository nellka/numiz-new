<?php
$this->pageTitle=Yii::app()->name . ' - Login';
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>true,
)); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'Login'); ?>
		<?php echo $form->textField($model,'Login'); ?>
		<?php echo $form->error($model,'Login'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Password'); ?>
		<?php echo $form->passwordField($model,'Password'); ?>
		<?php echo $form->error($model,'Password'); ?>		
	</div>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row submit">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<?php
$this->pageTitle=Yii::app()->name . ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
);
?>

<h1>Административная панель</h1>
Здравствуйте, <?= Yii::app()->user->name?>!<br><br>
<?php echo CHtml::link('Seo тексты',array('post/index')); ?>
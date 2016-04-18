<?php
$this->renderPartial('_menuLoad');
echo "<h3>Допустимые ip (боты)</h3>";
?>

<h1>Редактировать бота <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_formbot', array('model'=>$model)); ?>
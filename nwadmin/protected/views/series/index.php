<?php 
$this->renderPartial('_menuLoad');
echo "<h3>Допустимые ip (боты)</h3>";


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#ip-bot-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

echo CHtml::link("Создать",array('createbot'));
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ip-bot-grid',
	'dataProvider'=>$model->search(),
//	'filter'=>$model,
    'filter'=>null,
	'columns'=>array(
		array(
            'name'=>'id',
            'filter'=>false
        ),
        array(
            'name'=>'ip',
            'value'=>'CHtml::link($data->ip,"http://www.nic.ru/whois/?query={$data->ip}",array("target"=>"_blank"))',
            'type'=>'raw',
            'filter'=>false,
        ),
        array(
            'name' =>         'timestamp',
            'value'=>'date("d.m.Y H:i",$data->timestamp)',
            'filter'=>false,
        ),

		'description',
		array(
			'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
             'buttons'=>array(
		        'update' => array(		           
		            'url'=>'Yii::app()->createUrl("cp/tools/updatebot", array("id"=>$data->id))',
		        ),
		        'delete' => array(
		            'url'=>'Yii::app()->createUrl("cp/tools/deletebot", array("id"=>$data->id))',
		        ),
			),
		),
	),
)); ?>

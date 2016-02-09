<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class ShopcoinsController extends CController
{
	/**
	 * Index action is the default action in a controller.
	 */
	public function actionGetgroups()
	{
	    $materialtype = $_POST['materialtype'];
		$data = Shopcoins::model()->getGroups($materialtype);
		foreach($data as $value=>$text)  {
            echo CHtml::tag('option', array('value'=>$value),CHtml::encode($text),true);
        }
	}
	
	public function actionGetnominals()
	{
	
	    $materialtype = $_REQUEST['materialtype'];
	    $group_id = $_REQUEST['group_id'];
		$data = Shopcoins::model()->getNominals($materialtype,$group_id);
		foreach($data as $value=>$text)  {
            echo CHtml::tag('option', array('value'=>$value),CHtml::encode($text),true);
        }
	}
}
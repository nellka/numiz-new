<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class ShopcoinscurrentController extends Controller
{
	/**
	 * Index action is the default action in a controller.
	 */
	
	public function actionGetnominals()
	{	
	    $group_id = $_REQUEST["Avitosection"]['group_id'];
	    $materialtype = $_REQUEST["Avitosection"]['materialtype'];
		$data = ShopcoinsCurrent::model()->getNominals($materialtype,$group_id);
		foreach($data as $value=>$text)  {
            echo CHtml::tag('option', array('value'=>$value),CHtml::encode($text),true);
        }
	}
	public function actionGetcount(){	
	    $data = $_REQUEST["Avitosection"];
		echo ShopcoinsCurrent::model()->getCount($data);		
	}
	
	public function actionGetYears(){	
	    $req = $_REQUEST["Avitosection"];
		$data = ShopcoinsCurrent::model()->getYears($req);	
		echo CHtml::tag('option', array('value'=>'0'),'--||--',true);
		foreach($data as $value)  {
		    echo CHtml::tag('option', array('value'=>$value->year),$value->year,true);
        }	
	}
	
	public function actionGetgroups(){	
	    $materialtype = $_REQUEST["Avitosection"]['materialtype'];
		$data = ShopcoinsCurrent::model()->getGroups($materialtype);
		foreach($data as $value=>$text)  {
            echo CHtml::tag('option', array('value'=>$value),CHtml::encode($text),true);
        }
	}
	public function actionGetmetals()
	{
		$data = ShopcoinsCurrent::model()->getMetalls($_REQUEST["Avitosection"]);		
		 echo CHtml::tag('option', array('value'=>'0'),'--Металл--',true);
		foreach($data as $value)  {
		    if($value->metal_id)  echo CHtml::tag('option', array('value'=>$value->metal_id),CHtml::encode($value->metal->name),true);
        }
	}
}
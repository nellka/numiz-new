<?php
class SeriesController extends CController
{
	/**
	 * Index action is the default action in a controller.
	 */
	public function actionGetgroups()
	{
	    $materialtype = $_POST['materialtype'];
		return Shopcoins::model()->getGroups($materialtype);
	}
	
	public function actionIndex()
	{
	    die('jjj');
	}
}
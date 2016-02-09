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
		return Shopcoins::model()->getGroups($materialtype);
	}
}
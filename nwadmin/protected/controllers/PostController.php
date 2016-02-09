<?php

class PostController extends Controller
{
	public $layout='column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to access 'index' and 'view' actions.
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated users to access all actions
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$Shopcoinsseotext= $this->loadModel();
		
		$this->render('view',array(
			'model'=>$Shopcoinsseotext
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Shopcoinsseotext('add');
		if(isset($_POST['Shopcoinsseotext']))
		{
			$model->attributes=$_POST['Shopcoinsseotext'];			
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
		
		if(isset($_POST['Shopcoinsseotext']))
		{
			$model->attributes=$_POST['Shopcoinsseotext'];
		
			if($model->save()){			    	
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isShopcoinsseotextRequest)
		{
			// we only allow deletion via Shopcoinsseotext request
			$this->loadModel()->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
	    
	   $model=new Shopcoinsseotext('search');
		if(isset($_GET['Shopcoinsseotext']))
			$model->attributes=$_GET['Shopcoinsseotext'];

		$this->render('shopcoinsseotext',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Suggests tags based on the current user input.
	 * This is called via AJAX when the user is entering the tags input.
	
	public function actionSuggestTags()
	{
		if(isset($_GET['q']) && ($keyword=trim($_GET['q']))!=='')
		{
			$tags=Tag::model()->suggestTags($keyword);
			if($tags!==array())
				echo implode("\n",$tags);
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
			{
				if(Yii::app()->user->isGuest)
					$condition='active='.Shopcoinsseotext::STATUS_PUBLISHED.' OR active='.Shopcoinsseotext::STATUS_ARCHIVED;
				else
					$condition='';
				$this->_model=Shopcoinsseotext::model()->findByPk($_GET['id'], $condition);
			}
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Creates a new comment.
	 * This method attempts to create a new comment based on the user input.
	 * If the comment is successfully created, the browser will be redirected
	 * to show the created comment.
	 * @param Shopcoinsseotext the Shopcoinsseotext that the new comment belongs to
	 * @return Comment the comment instance
	 */
	/*protected function newComment($Shopcoinsseotext)
	{
		$comment=new Comment;
		if(isset($_Shopcoinsseotext['ajax']) && $_Shopcoinsseotext['ajax']==='comment-form')
		{
			echo CActiveForm::validate($comment);
			Yii::app()->end();
		}
		if(isset($_Shopcoinsseotext['Comment']))
		{
			$comment->attributes=$_Shopcoinsseotext['Comment'];
			if($Shopcoinsseotext->addComment($comment))
			{
				if($comment->status==Comment::STATUS_PENDING)
					Yii::app()->user->setFlash('commentSubmitted','Thank you for your comment. Your comment will be Shopcoinsseotexted once it is approved.');
				$this->refresh();
			}
		}
		return $comment;
	}*/
}

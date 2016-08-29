<?php
class SeriesController extends Controller
{
    public $layout='column1';
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
	    $model=new Series('search');
        $model->unsetAttributes();  // clear any default values

        if(isset($_GET['Series'])) {
            $model->attributes=$_GET['Series'];            
        }         
        
        $this->render('index',array(
            'model'=>$model
        ));
	}
	
	
	public function actionCreate()
	{
		$model=new Series;

		if(isset($_POST['Series'])) {
			$model->attributes=$_POST['Series'];
            // если загружен файл - сохраняем во временный каталог
            $image_file = CUploadedFile::getInstance($model,'image');
            
            if ( $image_file) {               
                   $path = Series::$path_to_file.'/'.$image_file->getName();                  
                   
                    $image_file->saveAs($path);
                    $model->image = $image_file->getName();   
                    Yii::app()->user->setState('seriesPhoto',$model->image)    ;            

            } 
            // файл уже был загружен - заполним  file
            if (Yii::app()->user->hasState('seriesPhoto')) $model->image = Yii::app()->user->getState('seriesPhoto');
			if($model->save()) {
			    $this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function loadModel($id)
	{
		$model=Series::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function actionDeletePhoto($mid){
	    $model=$this->loadModel($mid);
	    $imageName = $model->image;
	    if($model->image){
	        $model->image = null;
                        
	        unlink(Series::$path_to_file.'/'.$imageName);
	        $model->saveAttributes(array('image'));
	        $this->redirect(Yii::app()->request->urlReferrer);
	    }
	}
	
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['Series']))
		{
			$model->attributes=$_POST['Series'];
			$image_file = CUploadedFile::getInstance($model,'image');
			
            if($model->save()){
                if($image_file){
                    $path = Series::$path_to_file.'/'.$image_file->getName();                  
                    $image_file->saveAs($path);
                    $model->image = $image_file->getName();
                   
                }           
                // перенаправляем на страницу, где выводим сообщение об
            }
            // файл уже был загружен - заполним  file
            
			if($model->save())  $this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}
}
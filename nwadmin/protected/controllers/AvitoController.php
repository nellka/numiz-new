<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class AvitoController extends Controller
{
	public function actionIndex()
	{
	    
	    $model=new Avitosection('search');
	   	$model->unsetAttributes(); 
		if(isset($_REQUEST['Avitosection'])){
			$model->attributes=$_REQUEST['Avitosection'];
		}
		$this->render('index',array('model'=>$model,'modelcreate'=>new Avitosection,'sections'=>array()));
	}
	
	public function loadModel($id)
	{
		$model=Avitosection::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function loadAvitosection($data)
	{
		unset($data['priority']);
		unset($data['sid']);
		$model=Avitosection::model()->findByAttributes($data);
		
		return $model;
	}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function actionCreatesection()
	{
		$model=new Avitosection();

		if(isset($_POST['Avitosection'])) {
			
			$model->attributes=$_POST['Avitosection'];
			$modelCheck = $this->loadAvitosection($_POST['Avitosection']);
			
			if($modelCheck->sid) {				
				Yii::app()->user->setFlash('error','Такая запись уже существует!');
			} elseif($model->save()) {				
			    $this->redirect(array('index'));
			} 
			
		}   
		$modelSearch = new Avitosection('search');
		$modelSearch->unsetAttributes(); 
		$this->render('index',array(
			'model'=>$modelSearch,'modelcreate'=>$model,'sections'=>array()
		));
	}
	
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if(isset($_POST['Avitosection'])) {			
			$model->attributes=$_POST['Avitosection'];

			$modelCheck = $this->loadAvitosection($_POST['Avitosection']);
			
			if($modelCheck->sid&&$modelCheck->sid!=$id) {				
				Yii::app()->user->setFlash('error','Такая запись уже существует!');
			} elseif($model->save()) {				
			    $this->redirect(array('index'));
			} 
			
		}   

		$modelSearch = new Avitosection('search');
		$modelSearch->unsetAttributes(); 
		$this->render('index',array(
			'model'=>$modelSearch,'modelcreate'=>$model,'sections'=>array()
		));
	}
	
	public function actionSavesections()
	{
		$modelSearch = new Avitosection('search');
		$sections = array();
		$avitoItems  = array();
		if(isset($_POST['avito-grid_c0'])) {
			$sids = $_POST['avito-grid_c0'];
			$sections = Avitosection::model()->findAllByPk($sids,array('index'=>'sid'));	
			$data = array();
			
			$sids_with_data = array();
			$errors = array();
			$items = array();
			foreach ($sections as $section)	{
			    if($section->materialtype) $data[':materialtype'] = $section->materialtype;
			    if($section->group_id) $data[':group_id'] = $section->group_id;
			    if($section->metal_id) $data[':metal_id'] = $section->metal_id;
			    if($section->nominal_id) $data[':nominal_id'] = $section->nominal_id;
			    if($section->year_from) $data[':year_from'] = $section->year_from;
			    if($section->year_to) $data[':year_to'] = $section->year_to;
			    $s_items = ShopcoinsCurrent::model()->getCoinsByParams($data);
			    if(!count($s_items)){
			        $errors[] = "Нет позиций для заказаза в выбоке $section->sid";
			    } else {
			        $sids_with_data[] = $section->sid; 
			        foreach ($s_items as $item){			           
			            $avitoItem = new Avitoitem;
			            $avitoItem->sid = $section->sid;
			            $avitoItem->save();
			            $items[] = $avitoItem->id;			           
			            
			            $avitoItemshop = new Avitoitemshop;
			            $avitoItemshop->avitoitem = $avitoItem->id;
			            $avitoItemshop->shopcoins = $item->shopcoins;
			            $avitoItemshop->save();					                   
			        }
			    }			   
			}
			if($sids_with_data) {
			    $sections = Avitosection::model()->findAllByPk($sids_with_data,array('index'=>'sid'));	
			} else $sections = array();
			
			if($items) {
			    $avitoItems = Avitoitem::model()->findAllByPk($items,array('index'=>'id'));	
			}
			if($errors) {
			    Yii::app()->user->setFlash('error',implode("<br>",$errors));	
			}	
		}  else {
		    Yii::app()->user->setFlash('error','Нет секций для записи');
		    $this->redirect(array('index'));
		}
		
		$this->render('sections',array('sections'=>$sections,'avitoitems'=>$avitoItems));
	}

	public function actionShowtemp()
	{
		if(isset($_REQUEST['Avitosection'])) {
			$data = $_REQUEST['Avitosection'];
			foreach ($data as $key=>$val){
				if(!in_array($key,array('group_id','nominal_id','metal_id','materialtype','year_from','year_to'))) {
					unset($data[$key]);
					continue;
				}
				if($val) $data[':'.$key] = $val;
				unset($data[$key]);
			}

			$criteria=new CDbCriteria;

			if($data[':group_id']) $criteria->addCondition('t.group=:group_id');
			if($data[':nominal_id']) $criteria->addCondition('t.nominal_id=:nominal_id');
			if($data[':metal_id']) $criteria->addCondition('t.metal_id=:metal_id');
			if($data[':materialtype']) $criteria->addCondition('t.materialtype=:materialtype');
			if($data[':year_from']) $criteria->addCondition('t.year>=:year_from');
			if($data[':year_to']) $criteria->addCondition('t.year<=:year_to');
			$criteria->with=array('shop');
			$criteria->join='inner join shopcoins_search_details as d on d.catalog = t.shopcoins';  	
			$criteria->addCondition('t.check=1 and shop.dateinsert<'.(time()-10*86400).' and (shop.realization = 0 or (shop.realization>0 and (shop.provider=34 or shop.provider=51 or shop.provider=55 or shop.provider=54)))');
					
			$criteria->order = "shop.name, t.metal_id, t.year, d.details";
			$criteria->group = "d.details";
			//$criteria->order = "order by t.name, t.metal, year, shopcoins.details";
			$criteria->params = $data;	
			$items = ShopcoinsCurrent::model()->findAll($criteria);			

//
//group by shopcoins.details

		}

		$this->render('showtemp',array('items'=>$items ));
	}
}
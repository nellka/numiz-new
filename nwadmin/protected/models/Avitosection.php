<?php
/**
CREATE TABLE `avitosection` (
 `sid` int(11) NOT NULL  AUTO_INCREMENT,
 `group` int(11) NOT NULL DEFAULT '0',
 `nominal_id` int(11) NOT NULL DEFAULT '0',
 `metal_id` int(11) NOT NULL DEFAULT '0',
 `year_from` int(11) NOT NULL DEFAULT '0',
 `year_to` int(11) NOT NULL DEFAULT '0',
 `priority` int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (`sid`),
 KEY `group` (`group`),
 KEY `nominal_id` (`nominal_id`),
 KEY `metal_id` (`metal_id`),
 KEY `year_from` (`year_from`),
 KEY `year_to` (`year_to`),
 Unique key `s`(`group`,`nominal_id`,`metal_id`,`year_from`,`year_to`)
) 


CREATE TABLE `avitoitemshopcoins` (
 `id` int(11) NOT NULL  AUTO_INCREMENT,
 `aid` int(11) NOT NULL DEFAULT '0',
 `shopcoins` int(11) NOT NULL DEFAULT '0'
 PRIMARY KEY (`id`)
 KEY `aid` (`aid`),
 KEY `shopcoins` (`shopcoins`)
) 
 */
class Avitosection extends CActiveRecord
{
	       
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'avitosection';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('group_id,materialtype', 'required'),
			//array('materialtype', 'in', 'range'=>array(1,2,3)),
			array('group_id,nominal_id,metal_id,year_from,year_to,priority,materialtype', 'numerical', 'integerOnly'=>true),
			array('group_id,materialtype', 'compare', 'operator'=>'>', 'compareValue'=>0),
			//array('title, text,active,group_id,nominal_id,materialtype', 'safe', 'on' => 'add,update'),
			array('id,group_id,nominal_id,metal_id,year_from,year_to,priority,materialtype', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'group' => array(self::BELONGS_TO, 'Groups',array('group_id'=>'group')),
			'metal' => array(self::BELONGS_TO, 'Metalls',array('metal_id'=>'id')),
			'nominal' => array(self::BELONGS_TO, 'Nominals',array('nominal_id'=>'id')),
		);
	}	

	public function getMetalList(){
		
        $data= Yii::app()->db->createCommand()
            ->select()
            ->from($this->tableName().' t')
            ->join('shopcoins_metal p', 't.metal_id=p.id')
            ->order('p.name asc')
            ->queryAll();       
         
       // $metals = array('0'=>'--||--');
        foreach ($data as $row) {
              $metals[$row["metal_id"]] = $row["name"];
        }
        return $metals;          
    }
    
	public function getNominalList(){

        $data= Yii::app()->db->createCommand()
            ->select()
            ->from($this->tableName().' t')
            ->join('shopcoins_name p', 't.nominal_id=p.id')
            ->order('p.name asc')
            ->queryAll();       
         
        //$nominals = array('0'=>'--||--');
        foreach ($data as $row) {
              $nominals[$row["nominal_id"]] = $row["name"];
        }
        return $nominals;            
    }
    
    public function getGroupList(){

        $data= Yii::app()->db->createCommand()
            ->select()
            ->from($this->tableName().' t')
            ->join('group as p', 't.group_id=p.group')
            ->order('p.name asc')
            ->queryAll();       

        //$groups = array('0'=>'--||--');
        foreach ($data as $row) {
              $groups[$row["group_id"]] = $row["name"];
        }
        return $groups;            
    }
    
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(		
			'id' => 'Id',
			'group_id' => 'Страна',
			'nominal_id' => 'Название',
			'metal_id' => 'Метал',			
			'year_from' => 'Год от',
			'year_to' => 'Год до',			
			'priority' => 'Сортировка',
			'materialtype' => 'Категория'
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('group_id',$this->group_id);		
        $criteria->compare('nominal_id',$this->nominal_id);
		$criteria->compare('metal_id',$this->metal_id);		
        $criteria->compare('year_from',$this->year_from);
        $criteria->compare('year_to',$this->year_to);	
        $criteria->compare('materialtype',$this->materialtype);	
        
        $dataprovider = new CActiveDataProvider($this->tableName(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'priority DESC,sid DESC',
			),
		));
		return $dataprovider;
	}
}
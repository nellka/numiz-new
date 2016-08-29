<?php
/**
CREATE TABLE `avitoitemshopcoins` (
`id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`avitoitem` int( 11 ) NOT NULL ,
`shopcoins` int( 11 ) NOT NULL,
PRIMARY KEY ( `id` ) ,
Unique KEY `avito` ( `avitoitem` ,`shopcoins`)
)
 */
class Avitoitemshop extends CActiveRecord
{
	       
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'avitoitemshopcoins';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('avitoitem,shopcoins', 'required'),
			array('avitoitem,shopcoins', 'numerical', 'min'=>1),
			array('avitoitem,shopcoins', 'numerical', 'integerOnly'=>true),
			array('id,avitoitem,shopcoins', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'avitoitem' => array(self::BELONGS_TO, 'Avitoitem',array('avitoid'=>'id')),
			'shop' => array(self::BELONGS_TO, 'Shopcoins',array('shopcoins'=>'shopcoins')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(			
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);		
        $criteria->compare('avitoitem',$this->avitoitem);
		$criteria->compare('shopcoins',$this->shopcoins);	

        $dataprovider = new CActiveDataProvider('Avitoitem', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
		));
		return $dataprovider;
	}	
}
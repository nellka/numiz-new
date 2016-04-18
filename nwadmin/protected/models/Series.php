<?php
/**
CREATE TABLE `shopcoinsbyseries` (
`id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`name` varchar( 255 ) NOT NULL ,
`countrygroup` int( 11 ) NOT NULL ,
`whereselect` varchar( 255 ) NOT NULL ,
`image` varchar( 255 ) ,
`details` varchar( 255 ) ,
`status` tinyint( 1 ) default 1,
PRIMARY KEY ( `id` ) ,
KEY `countrygroup` ( `countrygroup` ) ,
KEY `status` ( `status` )
) ENGINE = MYISAM DEFAULT CHARSET = cp1251
 */
class Series extends CActiveRecord
{

	const STATUS_PUBLISHED=1;
	const STATUS_RESERVE=0;
	
    public static $statuses = array (
        self::STATUS_PUBLISHED => "Опубликовано",
        self::STATUS_RESERVE => 'Скрыто'
    );    

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'shopcoinsbyseries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, whereselect, countrygroup', 'required'),
			//array('materialtype', 'in', 'range'=>array(1,2,3)),
			array('name,whereselect,details,image,', 'length', 'max'=>255),
			array('id,countrygroup,status', 'numerical', 'integerOnly'=>true),
			//array('title, text,active,group_id,nominal_id,materialtype', 'safe', 'on' => 'add,update'),
			array('name,whereselect,details,image,id,countrygroup,status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'group' => array(self::BELONGS_TO, 'Groups',array('countrygroup'=>'group_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'name' => 'Название',
			'status' => 'Статус',
			'countrygroup' => 'Страна',			
			'whereselect' => 'Запрос',
			'details' => 'Описание',			
			'image' => 'изображение'
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('name',$this->name,true);		
        $criteria->compare('status',$this->status,true);
		$criteria->compare('countrygroup',$this->countrygroup,true);		
       
        $dataprovider = new CActiveDataProvider('Series', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
		));
		return $dataprovider;
	}
}
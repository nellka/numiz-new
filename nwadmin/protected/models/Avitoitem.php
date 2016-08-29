<?php
/**
CREATE TABLE `avitoitem` (
`id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`sid` int( 11 ) NOT NULL ,
`avitoid` int( 11 ) NOT NULL default 0,
`show` int( 11 ) NOT NULL default 0,
`dateinsert` int( 11 ) NOT NULL default 0,
`dateinsertavito` int( 11 ) NOT NULL default 0,
PRIMARY KEY ( `id` ) ,
KEY `sid` ( `sid` ) ,
KEY `avitoid` ( `avitoid` )
)
 */
class Avitoitem extends CActiveRecord
{
	       
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'avitoitem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('sid', 'required'),
			array('sid','numerical', 'min'=>1),
			array('id,sid,avitoid,show,dateinsert,dateinsertavito', 'numerical', 'integerOnly'=>true),
			array('id,sid,avitoid,show,dateinsert,dateinsertavito', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'section' => array(self::BELONGS_TO, 'Avitosection',array('sid'=>'sid')),
			'avitoshop' => array(self::BELONGS_TO, 'Avitoitemshop',array('id'=>'avitoitem')),
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

		$criteria->compare('id',$this->id);		
        $criteria->compare('sid',$this->sid);
		$criteria->compare('avitoid',$this->avitoid);	
       		
        $dataprovider = new CActiveDataProvider('Avitoitem', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
		));
		return $dataprovider;
	}
	protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord) {
                $this->dateinsert = time();
            }           
            return true;
        } else return false;
    }
}
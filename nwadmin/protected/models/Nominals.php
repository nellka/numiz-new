<?php
/**
 * The followings are the available columns in table 'tbl_post':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $author_id
 */
class Nominals extends CActiveRecord
{

	//private $_oldTags;

	/**
	 * Returns the static model of the specified AR class.
	 * @return static the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nominals';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{		
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{		
		return array(
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		
	}
	
	/**
	 * Retrieves the list of posts based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the needed posts.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		/*$criteria->compare('title',$this->title,true);
		//array('title, text,active,dateinsert,group_id,nominal_id,materialtype', 'safe', 'on'=>'search'),
        $criteria->compare('active',$this->active);
		$criteria->compare('group_id',$this->group_id);
        $criteria->compare('nominal_id',$this->nominal_id);
        $criteria->compare('materialtype',$this->materialtype);*/
        $dataprovider = new CActiveDataProvider('Nominals', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
		));
		return $dataprovider;
	}
}
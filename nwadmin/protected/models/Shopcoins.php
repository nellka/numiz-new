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
class Shopcoins extends CActiveRecord
{

	const STATUS_ACTIVE=1;
	const STATUS_NO=0;
	
	const SECTION_COINS=1;
	const SECTION_MELOCH=8;
    const SECTION_CVET=6;
    const SECTION_NOTGELMI=10;
    const SECTION_NABORY_MONET=7;
    const SECTION_LOTI=9;
    const SECTION_ACSESSUARY=3;
    const SECTION_BONI=2;
    const SECTION_PODAROK=4;
    const SECTION_BOOK=5;
    const SECTION_BARAHOLKA=11;
    
   
	
    public static $statuses = array (
        self::STATUS_ACTIVE => "В продаже",
        self::STATUS_NO => 'Нет в продаже'
    );
    
    public static $sections = array (
        self::SECTION_COINS  => "Монеты",
        self::SECTION_MELOCH  => "Мелочь",
        self::SECTION_CVET  => "Цветные монеты",
        self::SECTION_NOTGELMI => 'Нотгельды',
        self::SECTION_NABORY_MONET=>'Наборы монет',
        self::SECTION_LOTI=> 'Лоты монет для начинающих нумизматов',
        self::SECTION_BONI=> 'Боны',
        self::SECTION_ACSESSUARY=> 'Аксессуары для монет',
        self::SECTION_PODAROK=> 'Подарочные наборы',
        self::SECTION_BOOK=> 'Книги о монетах',
        self::SECTION_BARAHOLKA=> 'Барахолка',
        
    );

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
		return 'shopcoins';
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
			'groups' => array(self::BELONGS_TO, 'Groups',array('group_id'=>'group')),
			'metal' => array(self::BELONGS_TO, 'Metalls',array('metal_id'=>'id')),
			'nominal' => array(self::BELONGS_TO, 'Nominals',array('nominal_id'=>'id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'title' => 'Заголовок',
			'text' => 'Текст',
			'materialtype' => 'Раздел',
			'active' => 'Status',
			'dateinsert' => 'Create Time',
			'update_time' => 'Update Time',
			'group_id' => 'Страна',
			'nominal_id' => 'Номинал',
		);
	}

	/**
	 * @return string the URL that shows the detail of the post
	 */
	public function getUrl()
	{
		return Yii::app()->createUrl('post/view', array(
			'id'=>$this->id,
			'title'=>$this->title,
		));
	}
    public function getNominals($materialtype,$group_id){

        $data= Yii::app()->db->createCommand()
            ->select('distinct(t.nominal_id),p.name')
            ->from('shopcoins t')
            ->join('shopcoins_name p', 't.nominal_id=p.id')
            ->where('materialtype=:materialtype and t.group=:group', array(':materialtype'=>$materialtype,':group'=>$group_id))
            ->order('p.name asc')
            ->queryAll();       
         
        $nominals = array('0'=>'--||--');
        foreach ($data as $row) {
              $nominals[$row["nominal_id"]] = $row["name"];
        }
        return $nominals;
         
    }
    
    public function getGroupsFullList($type='shopcoins')
	{
	    $select =  Yii::app()->db->createCommand()
            ->select('distinct(`group`),name')
            ->from('group')          
            ->order('name asc');
        if($type){
            $select->where("groupparent=0 and type=:type",array(":type"=>$type));   
        } else {
            $select->where("groupparent=0");   
        }
         
	    $data = $select->queryAll();
        
                  
          $groups = array('0'=>'--||--');
          
          foreach ($data as $row) {
              $groups[$row["group"]] = $row["name"];
              $select = Yii::app()->db->createCommand()
                    ->select('group,name')
                    ->from('group')
                    ->order('name asc');
                    
              if($type) {
                  $select->where('groupparent=:groupparent and type=:type', array(':groupparent'=>$row['group'],":type"=>$type));                  
              } else {
                  $select->where('groupparent=:groupparent', array(':groupparent'=>$row['group']));
              }
                       
              $data_child = $select->queryAll();
              
              foreach ($data_child as $row_child) {
                   $groups[$row_child["group"]] = "-  ".$row_child["name"];
              }
               
          }

          return $groups;
	}
    
	public function getGroups($materialtype)
	{
		$data = Yii::app()->db->createCommand()
            ->select('distinct(t.group),p.name')
            ->from('shopcoins t')
            ->join('group p', 't.group=p.group')
            ->where('materialtype=:materialtype  and groupparent=0', array(':materialtype'=>$materialtype))
            ->order('p.name asc')
            ->queryAll();
            
          $groups = array('0'=>'--||--');
          
          foreach ($data as $row) {
              $groups[$row["group"]] = $row["name"];
              
              $data_child = Yii::app()->db->createCommand()
                    ->select('distinct(t.group),p.name')
                    ->from('shopcoins t')
                    ->join('group p', 't.group=p.group')
                    ->where('materialtype=:materialtype and groupparent=:groupparent', array(':materialtype'=>$materialtype,':groupparent'=>$row['group']))
                    ->order('p.name asc')
                    ->queryAll();
              foreach ($data_child as $row_child) {
                   $groups[$row_child["group"]] = "-  ".$row_child["name"];
              }
               
          }

          return $groups;
    
    }

	
	/**
	 * Retrieves the list of posts based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the needed posts.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('title',$this->title,true);
		//array('title, text,active,dateinsert,group_id,nominal_id,materialtype', 'safe', 'on'=>'search'),
        $criteria->compare('active',$this->active);
		$criteria->compare('group_id',$this->group_id);
        $criteria->compare('nominal_id',$this->nominal_id);
        $criteria->compare('materialtype',$this->materialtype);
        $dataprovider = new CActiveDataProvider('Shopcoinsseotext', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
		));
		return $dataprovider;
	}
}
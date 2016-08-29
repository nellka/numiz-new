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
class ShopcoinsCurrent extends CActiveRecord
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
		return 'shopcoins_search';
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
			'groups' => array(self::BELONGS_TO, 'Groups',array('group'=>'group')),
			'metal' => array(self::BELONGS_TO, 'Metalls',array('metal_id'=>'id')),
			'nominal' => array(self::BELONGS_TO, 'Nominals',array('nominal_id'=>'id')),
			'shop'=> array(self::HAS_ONE, 'Shopcoins',array('shopcoins'=>'shopcoins')),
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
    public function getNominals($materialtype=1, $group_id=0){
		$select = Yii::app()->db->createCommand()
            ->select('distinct(t.nominal_id),p.name')
            ->from($this->tableName().' t')
            ->join('shopcoins shop', 'shop.shopcoins=t.shopcoins')
            ->join('shopcoins_name p', 't.nominal_id=p.id')
            ->order('p.name asc');  
        if($group_id) {
        	$select->where('t.check=1 and shop.dateinsert<'.(time()-10*86400).' and (shop.realization = 0 or (shop.realization>0 and (shop.provider=34 or shop.provider=51 or shop.provider=55 or shop.provider=54))) and t.group=:group and t.materialtype=:materialtype', array(':group'=>$group_id,':materialtype'=>$materialtype));
        }  else {
           $select->where('t.check=1 and shop.dateinsert<'.(time()-10*86400).' and (shop.realization = 0 or (shop.realization>0 and (shop.provider=34 or shop.provider=51 or shop.provider=55 or shop.provider=54))) and t.materialtype=:materialtype', array(':materialtype'=>$materialtype)); 
        }                   
        $data= $select->queryAll();
        
        $nominals = array('0'=>'--Название--');
        foreach ($data as $row) {
              $nominals[$row["nominal_id"]] = $row["name"];
        }
        return $nominals;
         
    }
    
    public function getYears($data){
       $criteria = new CDbCriteria; 
       $criteria->distinct = true;
       $criteria->select = "t.year";
       $criteria->order = "t.year asc";
       foreach ($data as $key=>$val){
           if(!in_array($key,array('group_id','nominal_id','metal_id','materialtype'))) {
               unset($data[$key]);
               continue;
           }
           if($val) $data[':'.$key] = $val;
           unset($data[$key]);           
       }

       if($data[':group_id'])$criteria->addCondition('t.group=:group_id');      
       if($data[':nominal_id']) $criteria->addCondition('t.nominal_id=:nominal_id');
       if($data[':metal_id']) $criteria->addCondition('t.metal_id=:metal_id');    
       if($data[':materialtype']) $criteria->addCondition('t.materialtype=:materialtype');
        $criteria->join='inner join shopcoins as shop on shop.shopcoins = t.shopcoins';  
        $criteria->addCondition('t.check=1 and shop.dateinsert<'.(time()-10*86400).' and (shop.realization = 0 or (shop.realization>0 and (shop.provider=34 or shop.provider=51 or shop.provider=55 or shop.provider=54)))');        		 
	   $criteria->params = $data;	
	   	   
	   return ShopcoinsCurrent::model()->findAll($criteria);	   
    }
    
    public function getCount($data){   
    	$criteria = new CDbCriteria;        
       	foreach ($data as $key=>$val){
           if(!in_array($key,array('group_id','nominal_id','metal_id','materialtype','year_from','year_to'))) {
               unset($data[$key]);
               continue;
           }
           if($val) $data[':'.$key] = $val;
           unset($data[$key]);           
       	}

       	if($data[':group_id'])$criteria->addCondition('t.group=:group_id');      
       	if($data[':nominal_id']) $criteria->addCondition('t.nominal_id=:nominal_id');
       	if($data[':metal_id']) $criteria->addCondition('t.metal_id=:metal_id');    
       	if($data[':materialtype']) $criteria->addCondition('t.materialtype=:materialtype');
       	if($data[':year_from']) $criteria->addCondition('t.year>=:year_from');
       	if($data[':year_to']) $criteria->addCondition('t.year<=:year_to');
        $criteria->join='inner join shopcoins as shop on shop.shopcoins = t.shopcoins';  
        $criteria->addCondition('t.check=1 and shop.dateinsert<'.(time()-10*86400).' and (shop.realization = 0 or (shop.realization>0 and (shop.provider=34 or shop.provider=51 or shop.provider=55 or shop.provider=54)))');        		
	   	$criteria->params = $data;	

	      
        /*$Attributes = array('check'=>1);
        if($data['materialtype']) $Attributes['materialtype'] = $data['materialtype'];
        if($data['group_id']) $Attributes['group'] = $data['group_id'];
        if($data['nominal_id']) $Attributes['nominal_id'] = $data['nominal_id'];
        if($data['metal_id']) $Attributes['metal_id'] = $data['metal_id'];
        if($data['metal_id']) $Attributes['metal_id'] = $data['metal_id'];*/
        
        return ShopcoinsCurrent::model()->count($criteria);        
    }
    
   
    public function getCoinsByParams($data){
       $criteria=new CDbCriteria; 
       
       if($data[':group_id']) $criteria->addCondition('t.group=:group_id');
       if($data[':nominal_id']) $criteria->addCondition('t.nominal_id=:nominal_id');
       if($data[':metal_id']) $criteria->addCondition('t.metal_id=:metal_id');    
       if($data[':materialtype']) $criteria->addCondition('t.materialtype=:materialtype');
       if($data[':year_from']) $criteria->addCondition('t.year>=:year_from');
       if($data[':year_to']) $criteria->addCondition('t.year<=:year_to');
       $criteria->join='inner join shopcoins as shop on shop.shopcoins = t.shopcoins';  
	   $criteria->addCondition('t.check=1 and shop.dateinsert<'.(time()-10*86400).' and (shop.realization = 0 or (shop.realization>0 and (shop.provider=34 or shop.provider=51 or shop.provider=55 or shop.provider=54)))');
	   $criteria->order = "shop.name, t.metal_id, t.year";
	   //$criteria->order = "order by t.name, t.metal, year, shopcoins.details";
	   
	   $criteria->params = $data;	
	   $items = ShopcoinsCurrent::model()->findAll($criteria);
       
       return  $items;
         
    }
    
    public function getMetalls($data){
        $criteria = new CDbCriteria; 
        $criteria->distinct = true;
        $criteria->select = "t.metal_id";
        $criteria->order = "metal_id asc";
        foreach ($data as $key=>$val){
           if(!in_array($key,array('group_id','nominal_id','materialtype'))) {
               unset($data[$key]);
               continue;
           }
           if($val) $data[':'.$key] = $val;
           unset($data[$key]);           
        }

        if($data[':group_id'])$criteria->addCondition('t.group=:group_id');      
        if($data[':nominal_id']) $criteria->addCondition('t.nominal_id=:nominal_id');       
        if($data[':materialtype']) $criteria->addCondition('t.materialtype=:materialtype');
        $criteria->join='inner join shopcoins as shop on shop.shopcoins = t.shopcoins';  
        $criteria->addCondition('t.check=1 and shop.dateinsert<'.(time()-10*86400).' and (shop.realization = 0 or (shop.realization>0 and (shop.provider=34 or shop.provider=51 or shop.provider=55 or shop.provider=54)))');        		
        $criteria->order = "shop.name, t.metal_id, t.year";
        //$criteria->order = "order by t.name, t.metal, year, shopcoins.details";       
	   $criteria->params = $data;		   	   
	   return ShopcoinsCurrent::model()->findAll($criteria);        
    }
    
	public function getGroups($materialtype)
	{
		$data = Yii::app()->db->createCommand()
            ->select('distinct(t.group),p.name')
            ->from($this->tableName().' t')
            ->join('group p', 't.group=p.group')
            ->join('shopcoins shop', 'shop.shopcoins=t.shopcoins')
            ->where('t.check=1 and shop.dateinsert<'.(time()-10*86400).' and (shop.realization = 0 or (shop.realization>0 and (shop.provider=34 or shop.provider=51 or shop.provider=55 or shop.provider=54))) and groupparent=0 and t.materialtype=:materialtype',array(':materialtype'=>$materialtype))
            ->order('p.name asc')
            ->queryAll();
            
          $groups = array('0'=>'--Страна--');
          
          foreach ($data as $row) {
              $groups[$row["group"]] = $row["name"];
              
              $data_child = Yii::app()->db->createCommand()
                    ->select('distinct(t.group),p.name')
                    ->from($this->tableName().' t')
                    ->join('group p', 't.group=p.group')
                    ->join('shopcoins shop', 'shop.shopcoins=t.shopcoins')
                    ->where('t.check=1 and shop.dateinsert<'.(time()-10*86400).' and (shop.realization = 0 or (shop.realization>0 and (shop.provider=34 or shop.provider=51 or shop.provider=55 or shop.provider=54))) and groupparent=:groupparent and t.materialtype=:materialtype', array(':groupparent'=>$row['group'],':materialtype'=>$materialtype))
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
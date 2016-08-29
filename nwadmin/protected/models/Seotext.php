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
class Shopcoinsseotext extends CActiveRecord
{

	const STATUS_PUBLISHED=1;
	const STATUS_RESERVE=0;
	
    public static $statuses = array (
        self::STATUS_PUBLISHED => "Опубликовано",
        self::STATUS_RESERVE => 'Черновик'
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
		return 'shopcoinsseotext';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		/*
		
		 `materialtype`  int(11) NOT NULL,
 `group_id`  int(11) NOT NULL default 0,
 `nominal_id`  int(11) NOT NULL default 0,
 `title` varchar(255),
` text` text,
 `dateinsert`  int(11) NOT NULL default 0,
 `active` tinyint(4),
		*/
		return array(
			array('title, text, materialtype', 'required'),
			//array('materialtype', 'in', 'range'=>array(1,2,3)),
			array('title', 'length', 'max'=>255),
			//array('title', 'length', 'max'=>255),
			//array('text', 'match', 'pattern'=>'/^[\w\s,]+$/', 'message'=>''),
			array('active,group_id,nominal_id,year', 'numerical', 'integerOnly'=>true),
			//array('title, text,active,group_id,nominal_id,materialtype', 'safe', 'on' => 'add,update'),
			array('title, text,active,dateinsert,group_id,nominal_id,materialtype,year', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'group' => array(self::BELONGS_TO, 'Groups', 'group_id'),
			'nominal' => array(self::BELONGS_TO, 'Nominals', 'nominal_id'),
			/*'comments' => array(self::HAS_MANY, 'Comment', 'post_id', 'condition'=>'comments.status='.Comment::STATUS_APPROVED, 'order'=>'comments.create_time DESC'),
			'commentCount' => array(self::STAT, 'Comment', 'post_id', 'condition'=>'status='.Comment::STATUS_APPROVED),*/
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
			'text' => 'Описание',
			'materialtype' => 'Раздел',
			'active' => 'Status',
			'dateinsert' => 'Create Time',
			'update_time' => 'Update Time',
			'group_id' => 'Страна',
			'nominal_id' => 'Номинал',
			'year'       => 'Год'
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

	/**
	 * @return array a list of links that point to the post list filtered by every tag of this post
	
	public function getTagLinks()
	{
		$links=array();
		foreach(Tag::string2array($this->tags) as $tag)
			$links[]=CHtml::link(CHtml::encode($tag), array('post/index', 'tag'=>$tag));
		return $links;
	}

	/**
	 * Normalizes the user-entered tags.
	
	public function normalizeTags($attribute,$params)
	{
		$this->tags=Tag::array2string(array_unique(Tag::string2array($this->tags)));
	}

	/**
	 * Adds a new comment to this post.
	 * This method will set status and post_id of the comment accordingly.
	 * @param Comment the comment to be added
	 * @return boolean whether the comment is saved successfully
	 
	public function addComment($comment)
	{
		if(Yii::app()->params['commentNeedApproval'])
			$comment->status=Comment::STATUS_PENDING;
		else
			$comment->status=Comment::STATUS_APPROVED;
		$comment->post_id=$this->id;
		return $comment->save();
	}

	/**
	 * This is invoked when a record is populated with data from a find() call.
	
	protected function afterFind()
	{
		parent::afterFind();
		$this->_oldTags=$this->tags;
	}

	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->dateinsert=time();
				//$this->author_id=Yii::app()->user->id;
			}
			else
				$this->dateinsert=time();
			return true;
		}
		else
			return false;
	}

	/**
	 * This is invoked after the record is saved.
	 */
	protected function afterSave()
	{
		parent::afterSave();
		//Tag::model()->updateFrequency($this->_oldTags, $this->tags);
	}

	/**
	 * This is invoked after the record is deleted.
	 */
	protected function afterDelete()
	{
		parent::afterDelete();
		Comment::model()->deleteAll('post_id='.$this->id);
		//Tag::model()->updateFrequency($this->tags, '');
	}

	/**
	 * Retrieves the list of posts based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the needed posts.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('title',$this->title,true);
		
        $criteria->compare('active',$this->active,true);
		//$criteria->compare('group_id',$this->group_id,true);
        //$criteria->compare('nominal_id',$this->nominal_id,true);
        $criteria->compare('materialtype',$this->materialtype,true);
        $dataprovider = new CActiveDataProvider('Shopcoinsseotext', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
		));
		return $dataprovider;
	}
}
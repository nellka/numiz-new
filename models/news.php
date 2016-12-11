<?php

/*Класс для получения информации о пользователе*/
class model_news extends Model_Base 
{		
    public $user_id;

    public function __construct($db,$user_id=0){
        parent::__construct($db);
        $this->user_id = $user_id;
    }
    
    public function getCountries($WhereParams=array()){
        $select = $this->db->select()
                            ->from(array('s'=>'news'),array())
                            ->join(array('newsshopcoinsrelation'),'newsshopcoinsrelation.news = s.news',array('distinct(group.group)'))
                            ->join(array('group'),'newsshopcoinsrelation.group = group.group',array('group','name'))
                            ->where("type='shopcoins'")
                            ->order("name");                            
       
        /*if (isset($WhereParams['theme'])) {            
            $select->where("theme in (".implode(",",$WhereParams['theme']).")");
        }
        */  
        if (isset($WhereParams['years'])) {
            $where_year = array();
            foreach ($WhereParams['years'] as $year){
                $year = (int) $year;
                $where_year[] = "(date_format(from_unixtime(date), '%Y')=$year)";
            }
            $select->where("(".implode(" or ",$where_year).")");
        }                 
        return $this->db->fetchAll($select);
    }  
         
    public function getThemes($WhereParams=array()){
        $select = $this->db->select()
                            ->from(array('s'=>'news'),array())
                            ->join(array('newsshopcoinsrelation'),'newsshopcoinsrelation.news = s.news',array('distinct(theme)'))
                            ->where('theme>0 and s.check=1')
                            ->order('theme desc');
                            
        if (isset($WhereParams['group'])) {
            $select->where("`group` in (".implode(",",$WhereParams['group']).")");           
        }
        
        if (isset($WhereParams['years'])) {
            $where_year = array();
            foreach ($WhereParams['years'] as $year){
                $year = (int) $year;
                $where_year[] = "(date_format(from_unixtime(date), '%Y')=$year)";
            }
            $select->where("(".implode(" or ",$where_year).")");
        }
        
        return  $this->db->fetchAll($select);
    }
    
    public function getGroups($id){
        if((int)$id){
             $select = $this->db->select()
                            ->from(array('s'=>'newsshopcoinsrelation'),array())
                            ->join(array('group'),'s.group = group.group',array('group','name'));                            
            $select->where("news=?",$id);
            return $this->db->fetchAll($select);
        }
    }
    
    public function getParams($id){
        if((int)$id){
             $select = $this->db->select()
                            ->from(array('s'=>'newsshopcoinsrelation'));                            
            $select->where("news=?",$id);
            return $this->db->fetchAll($select);
        }
    }
    
    private function byWhereParams($select, $WhereParams=array()){
        //var_dump($WhereParams);
        $join = false;
        
        if (isset($WhereParams['group'])) {
            $join = true;
            $select->join(array('newsshopcoinsrelation'),'newsshopcoinsrelation.news = s.news',array());
            $select->where("`group` in (".implode(",",$WhereParams['group']).")");
           
        }
       
        if (isset($WhereParams['theme'])) {
            if(!$join) $select->join(array('newsshopcoinsrelation'),'newsshopcoinsrelation.news = s.news',array());
            $select->where("theme in (".implode(",",$WhereParams['theme']).")");
        }
        
        if (isset($WhereParams['years'])) {
            $where_year = array();
            foreach ($WhereParams['years'] as $year){
                $year = (int) $year;
                $where_year[] = "(date_format(from_unixtime(date), '%Y')=$year)";
            }
            $select->where("(".implode(" or ",$where_year).")");
        }
        if (isset($WhereParams['text'])) {
            $text = $this->db->quote('%'.$WhereParams['text'].'%');
            $whereText = array() ;
            foreach ($WhereParams['sp_s'] as $type){
                if($type==1){
                    $whereText[] ="name like ".$text."";
                } else if($type==2){
                    $whereText[] ="text like ".$text."";
                } elseif($type==3){
                    $whereText[] ="keywords like ".$text."";
                }
            }            
            $select->where(" (".implode(" or ",$whereText).")");
        }
            
            return $select;
        }

    public function countAllByParams($WhereParams=array()){

        $select = $this->db->select()
                            ->from(array('s'=>$this->table),array('count(*)'));

        $select = $this->byWhereParams($select,$WhereParams);
        //echo $select->__toString();
        return $this->db->fetchOne($select);
    }

    public function GetMeta ($keyfield="keywords", $descriptionfield="details", $where="", $orderby, $onpage=0, $limit=10)
    {
        $fields = array();

        if($keyfield) $fields[] = $keyfield;
        if($descriptionfield) $fields[] = $descriptionfield;

        $select = $this->db->select()
            ->from(array('s'=>$this->table),$fields);
        //добавляем where
        if ($where)  $select->where(trim($where));
        if ($orderby)  $select->order($orderby);
        if ($onpage&&$limit) {
            $select->limitPage($onpage, $limit);
        } else if($limit) $select->limit($limit);

        $result = $this->db->fetchAll($select);

        $keywords = array();
        $details = '';
        foreach($result as $rows) {
            if ($rows[$keyfield]){
                $tmp = explode(",", $rows[$keyfield]);
                foreach ($tmp as $k=>$v) {
                    $v = trim($v);
                    if (!in_array($v, $keywords)) $keywords[] = $v;
                }
            }
            $details .= " ".trim(strip_tags($rows[$descriptionfield]));
        }

        if (sizeof($keywords)) {
            $tmp = implode(", ", $keywords);
            $keywords = $tmp;
        } else $keywords = '';

        $details = str_replace("\r","", $details);
        $details = str_replace("\n","", $details);

        $keywords = preg_replace("[\.>*<\"']", "", $keywords);
        $details = preg_replace("[\.>*<\"']", "", $details);

        if (!trim($keywords) and $details) $keywords = $details;

        if (!trim($details) and $keywords) $details = $keywords;

        $return[0] = mb_substr($keywords, 0, 200,'utf-8');
        $return[1] = mb_substr($details, 0, 200,'utf-8');
        return $return;
    }
    /**
     * @return mixed
     */
    public function getImg($id=0,$text='')
    {
        /*$select = $this->db->select()
            ->from(array('s'=>'news_img'),array('src'))
            ->where('news_id=?',$id);
        $src =  $this->db->fetchOne($select);
        
        if(!$src){   */         
           preg_match('#(<img\s(?>(?!src=)[^>])*?src=")(.*?)("[^>]*>)#',$text,$res);
	       $src = $res[2];
       // }
        
        return $src;
    }

    public function getItemsByParams($WhereParams=array(),$page=1, $items_for_page=30,$orderby='',$searchid=''){

        $select = $this->db->select()
            ->from(array('s'=>$this->table))
            ->where('s.check=1')
            ->order('date desc');
        $select = $this->byWhereParams($select,$WhereParams);
       // echo $select->__toString();
        if($items_for_page!='all'){
            $select->limitPage($page, $items_for_page);
        }

        return $this->db->fetchAll($select);
    }
    
     public function getItem($id){
        $id = (int) $id;
        
        if(!$id) return false;
        
        $select = $this->db->select()
            ->from(array('s'=>$this->table))
            ->where('news=?',$id);
        
        return $this->db->fetchRow($select);
    }
    
    public function getBiblioByKeywords($keywords,$id){
        $news = array();
        $id = (int) $id;
        $keywordsb = explode(", ", $keywords);
        if(empty($keywordsb)||!is_array($keywordsb)) return array();
        
        $select = $this->db->select()
            ->from(array('s'=>'biblio'))
            ->limit(5);
            
        $whereK =  array() ;   
        foreach ($keywordsb as $kew){
            $text = $this->db->quote('%'.$kew.'%');
            $whereK[] = " ( keywords like ".$text.") ";            
        } 
        if($whereK) $select->where(implode(" or ",$whereK));
        return $this->db->fetchAll($select);
    }
    
    public function getNewsByKeywords($keywords, $id){
        $news = array();
        $id = (int) $id;
        $keywordsb = explode(", ", $keywords);
        if(empty($keywordsb)||!is_array($keywordsb)) return array();
        
        $select = $this->db->select()
            ->from(array('s'=>$this->table))
            ->where("news!='$id'")
            ->limit(5);
            
        $whereK =  array() ;   
        foreach ($keywordsb as $kew){
            $text = $this->db->quote('%'.$kew.'%');
            $whereK[] = " ( keywords like ".$text.") ";            
        } 
        if($whereK) $select->where(implode(" or ",$whereK));
        return $this->db->fetchAll($select);
    }
    
}
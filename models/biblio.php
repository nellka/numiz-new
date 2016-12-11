<?php

/*Класс для получения информации о пользователе*/
class model_biblio extends Model_Base 
{		
    public $user_id;

    public function __construct($db,$user_id=0){
        parent::__construct($db);
        $this->user_id = $user_id;
    }
    /*
    public function getCountries($WhereParams=array()){
        $select = $this->db->select()
                            ->from(array('s'=>'biblio'),array())
                            ->join(array('newsshopcoinsrelation'),'newsshopcoinsrelation.news = s.news',array('distinct(group.group)'))
                            ->join(array('group'),'newsshopcoinsrelation.group = group.group',array('group','name'))
                            ->where("type='shopcoins'")
                            ->order("name");                            
       
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
    }*/
    
    public function getGroups(){
        $select = $this->db->select()
                        ->from(array('group'))
                        ->join(array(''),'s.group = group.group',array('group','name'));                            
        $select->where("type='biblio' and `check`=1");
        return $this->db->fetchAll($select);
    }
    /*
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
        }*/

    public function countAllByParams($WhereParams=array()){

        $select = $this->db->select()
                            ->from(array('s'=>$this->table),array('count(*)'));

        $select = $this->byWhereParams($select,$WhereParams);
        return $this->db->fetchOne($select);
    }

    /**

   
    public function getImg($id=0,$text='')
    {
         
           preg_match('#(<img\s(?>(?!src=)[^>])*?src=")(.*?)("[^>]*>)#',$text,$res);
	       $src = $res[2];
        
        return $src;
    }*/

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
            ->where('biblio=?',$id);
        
        return $this->db->fetchRow($select);
    }
    
    public function getNewsByKeywords($keywords,$id){
        $news = array();
        $id = (int) $id;
        $keywordsb = explode(", ", $keywords);
        if(empty($keywordsb)||!is_array($keywordsb)) return array();
        
        $select = $this->db->select()
            ->from(array('s'=>'news'))
            ->order('date desc')
            ->limit(5);
            
        $whereK =  array() ;   
        foreach ($keywordsb as $kew){
            $text = $this->db->quote('%'.$kew.'%');
            $whereK[] = " ( keywords like ".$text.") ";            
        } 
        if($whereK) $select->where(implode(" or ",$whereK));
        return $this->db->fetchAll($select);
    }
    
    public function getBiblioByKeywords($keywords, $id){
        $news = array();
        $id = (int) $id;
        $keywordsb = explode(", ", $keywords);
        
        if(empty($keywordsb)||!is_array($keywordsb)) return array();
        
        $select = $this->db->select()
            ->from(array('s'=>$this->table))
            ->where("biblio!='$id'")
            ->order('date desc')
            ->limit(5);
            
        $whereK =  array() ;   
        
        foreach ($keywordsb as $kew){
            $text = $this->db->quote('%'.$kew.'%');
            $whereK[] = " ( keywords like ".$text.") ";            
        } 
        
        if($whereK) $select->where(implode(" or ",$whereK));
        
       // echo $select->__toString();
        return $this->db->fetchAll($select);
    }
    
}
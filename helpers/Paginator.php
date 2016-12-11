<?php
class Paginator
{
    const DEFAULT_RESULTS_PER_PAGE = 10;
    const DEFAULT_BORDER = 15;
    private $border;
    
    private $baseUrl,
            $resultsPerPage,
            $resultsCount,
            $currentPage,
            $onclick;
            

    function __construct($options = array()) {
        
        $this->baseUrl        = '';
        $this->resultsPerPage = self::DEFAULT_RESULTS_PER_PAGE;
        $this->resultsCount   = 0;
        $this->currentPage    = 1;
        $this->border = self::DEFAULT_BORDER;
        
        if (is_array($options)) {
            if (array_key_exists('url', $options)) {
                $this->setBaseUrl($options['url']);
            }
            if (array_key_exists('count', $options)) {
                $this->setResultsCount($options['count']);
            }
            if (array_key_exists('per_page', $options)) {
                $this->setResultsPerPage($options['per_page']);
            }
            if (array_key_exists('page', $options)) {
                $this->setCurrentPage($options['page']);
            }
            if (array_key_exists('border', $options)) {
                $this->setBorder($options['border']);
            }
            if (array_key_exists('onclick', $options)) {
                $this->setOnclick($options['onclick']);
            }
            
        }        
       
    }
    public function setBorder($num) {
        if ($num > 0) { $this->border = (int) $num; }
    }
    
    public function setOnclick($func) { 
        $this->onclick = $func; 
    }
    
    public function getOnclick() { 
        return $this->onclick; 
    }
    
    public function getBorder() { 
        return $this->border; 
    }
        
    public function setBaseUrl($url) 
        { $this->baseUrl = $url; }
    
    public function getBaseUrl($prefix=true) 
    { 
        if(!$prefix) return $this->baseUrl;
        
        if(strpos($this->baseUrl,'?')!==false){
            return $this->baseUrl.'&'; 
        }
        return $this->baseUrl.'?'; 
    
    }
    
    public function setResultsPerPage($value) { 
        
        if ($value > 0) {
            $this->resultsPerPage = (int) $value;
        }
    }
    
    public function getResultsPerPage() 
        { return $this->resultsPerPage; }
    
    public function setResultsCount($value) 
        { $this->resultsCount = abs($value); }
    
    public function getResultsCount() 
        { return $this->resultsCount; }
    
    public function isCurrentPage($value) 
        { return $this->currentPage == (int) $value; }
    
    public function setCurrentPage($value) {
        
        $value = abs($value);
        
        if ($value < $this->getFirstPage()) {
            $this->currentPage = $this->getFirstPage();
        } else if ($value > $this->getLastPage()) {
            $this->currentPage = $this->getLastPage();
        } else {
            $this->currentPage = $value;
        }
    }
    
    public function pageInRange($value) {
        
        return $value >= $this->getFirstPage() && 
               $value <= $this->getLastPage();
    }
    
    public function getCurrentPage() 
        { return $this->currentPage; }
    
    public function getFirstPage() {
        
        if ($this->resultsCount == 0) 
            { return 0; }
        return 1;
    }
    
    public function getLastPage() {
        
        if ($this->resultsCount == 0) 
            { return 0; }
        return (floor($this->resultsCount / $this->resultsPerPage) + 
               ($this->resultsCount % $this->resultsPerPage == 0 ? 0 : 1));
    }
    
    public function getResultsOffset() {
        
        if ($this->getCurrentPage() == 0) {
            return 0;
        }
        return ($this->getCurrentPage() - 1) * 
            $this->getResultsPerPage();
    }
    
    public function getResultsNumber() 
        { return $this->getResultsPerPage(); }
    
    public function getPagesList() {
        
        $first = $this->getFirstPage();
        $last  = $this->getLastPage();
       
        if ($last == 0) 
            { return array(); }
            
        $page = $this->getCurrentPage();

        $from = $page - $this->border;
        $to   = $page + $this->border;
        
        if ($from < $first){            
            $d = $first-$from;
            $from = $first;             
        }
            
        if ($to > $last){ 
            $to = $last; 
            if(($from-$d)>=1) {
                $from = $from-$d;
            } else $from=1;
        }
            
        $list = array();
        for ($i = $from; $i <= $to; $i++) 
            { $list[] = $i; }
        return $list;
    }
    
    public function printPager(){
        $pages = $this->getPagesList();
        $onclick = '';
        
        if($this->getOnclick()){
            $onclick = "onclick='".$this->getOnclick()."'";
        }
         if (count($pages) > 1) {        
            $refs = array();
    
            if ( $pages[0] != 1 ) {
    
                $refs[] = "<a class='normal_ref_color' href='{$this->getBaseUrl()}page=" . ($pages[0] - 1) . "' $onclick>&lt;</a>";
            }
    
            foreach ($pages as $page) {
    
                if ($this->isCurrentPage($page)) {
                    $refs[] = "<span class=active>{$page}</span>";
                } else {
                    $refs[] = "<a class='normal_ref_color' href='{$this->getBaseUrl()}pagenum={$page}' $onclick>$page</a>";
                }
            }
    
            if ( $pages[ count($pages) - 1 ] != $this->getLastPage() ) {
    
                $refs[] = "<a class='normal_ref_color' href='{$this->getBaseUrl()}pagenum=" .($pages[count($pages) - 1] + 1) . "' $onclick>&gt;</a>";
            }
            //Страницы: 
            print '<p class="paginator">' . implode('', $refs) . '</p>';
            //print '<p class="paginator">' . implode('<span class="page_n"></span>', $refs) . '</p>';
        }
    }
    
     public function printPagerForShowPage(){
        $pages = $this->getPagesList();
        
        if (count($pages) > 1) {        
            $refs = array();
    
            if ( $pages[0] != 1 ) {
    
                $refs[] = "<a class='normal_ref_color' href='{$this->getBaseUrl(false)}_pp" . 
                      ($pages[0] - 1) . ".html'>&lt;</a>";
            }
    
            foreach ($pages as $page) {
    
                if ($this->isCurrentPage($page)) {
                    $refs[] = "<span>{$page}</span>";
                } else {
                    $refs[] = "<a class='normal_ref_color' href='{$this->getBaseUrl(false)}_pp{$page}.html'>$page</a>";
                }
            }
    
            if ( $pages[ count($pages) - 1 ] != $this->getLastPage() ) {
    
                $refs[] = "<a class='normal_ref_color' href='{$this->getBaseUrl(false)}_pp" . 
                      ($pages[count($pages) - 1] + 1) . ".html'>&gt;</a>";
            }
            print '<p>Страницы: ' . implode('<span style="padding-right:3px;padding-left:3px;"></span>', $refs) . '</p>';
        }
        /*
        if ($pagenumparent>2*$numpages) $page_string .= "<a href='".$rehrefpage."_pp1.html'>[в начало]</a> | ";
	if ($frompage>$numpages) $page_string .= "<a href='".$rehrefpage."_pp".($frompage-1).".html'><<пред</a> | ";
	for ($i=$frompage;$i<=$topage;$i++)
		{
		if ($i==$pagenumparent) $page_string .= "<b>$i</b>";
		else $page_string .= "<a href='".$rehrefpage."_pp$i.html'>$i</a>";
		if ($i<$topage) $page_string .= " | ";
		}      
	if ($pages>$topage) $page_string .= " | <a href='".$rehrefpage."_pp$i.html'>далее>></a>";
	
	echo "<p class=txt><b>Страницы: </b>".$page_string." </p>";*/
    }
}


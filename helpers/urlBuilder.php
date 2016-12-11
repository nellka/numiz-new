<?php
class urlBuild{ 
	
	static $shopcoins_materialsRule = array('moneti'=>1,
                       'nabori_monet'=>7,
                       'meloch'=>8,
                       'cvetnie_moneti'=>6,
                       'banknoti'=>2,
                       'aksessuary'=>3,
                       'podarochnye_nabory'=>4,
                       'baraholka'=>11,
                       'loty_dlya_nachinayushchih'=>9,
                       'notgeldy'=>10,
                       'moneti_sssr'=>12,
                       'knigi'=>5,
                       'newcoins'=>'newcoins',
                       'revaluation'=>'revaluation');

	static $shopcoins_materialIDsRule = array(
                       7=>'nabori_monet',
                       8=>'meloch',
                       6=>'cvetnie_moneti',
                       2=>'banknoti',
                       3=>'aksessuary',
                       4=>'podarochnye_nabory',
                       11=>'baraholka',
                       9=>'loty_dlya_nachinayushchih',
                       10=>'notgeldy',
                       12=>'moneti_sssr',
                       5=>'knigi',
                       'newcoins'=>'newcoins',
                       'revaluation'=>'revaluation');
                       
    static function request($key,$params){

        if(isset($_REQUEST[$key])) return trim($_REQUEST[$key]);
        if(isset($params[$key])) return trim($params[$key]);
        
        return '';
    }
    
    static function correctedUrl($url,$user_id=0){ 
         if(strpos($url,"&nocheck=1")&&$user_id!=811){
             $url = str_replace("&nocheck=1","",$url);
         }
         
         if(strpos($url,"?nocheck=1")&&$user_id!=811){
             $url = str_replace("?nocheck=1","",$url);
         }
         
         if(strpos($url,"&nocheck=1")&&$user_id==811){
             $url = str_replace("nocheck=1","",$url);
             $url .= (strpos($url,"?")!==false) ? "&nocheck=1":"?nocheck=1";
         }
         /* временно. Посмотреть лог
         if(strpos($url,"?nocheck=1")&&$user_id==811){
             $url = str_replace("nocheck=1","",$url);            
             $url .= (strpos($url,"?")!==false) ? "&nocheck=1":"?nocheck=1";
         }*/
         
         return $url;
    }
    
    static function  priceCoinsUrl($rows) {
    	$rehref = "Монета ";				
		if ($rows['gname'])
			$rehref .= $rows['gname']." ";
		$rehref .= $rows['aname'];
		if ($rows['ametal'])
			$rehref .= " ".$rows['ametal']; 
		if ($rows['year'])
			$rehref .= " ".$rows['year'];
		$namecoins = $rehref;
		$rehref = contentHelper::strtolower_ru($rehref)."_cpc".$rows['priceshopcoins']."_cpr".$rows['parent']."_pcn0.html";
		return $rehref;
    } 
    
    static function parseUrl($url){   
        $domain="www.numizmatik.ru/shopcoins";
        $url = str_replace('http://','',$url);
        $url = str_replace($domain,'',$url);       

        $_isCorrect = true;
    	
        $urlParams = array();
       
        $params = explode("?",$url);
    	$url = $params[0];
    	$params = explode("&",$url);
    	$url = $params[0];
    	$parts = explode('/',$url);    	
    	if(!$parts[0]) unset($parts[0]);

    	foreach ($parts as $part){
    	    
    	    if(isset(self::$shopcoins_materialsRule[$part])){
    	        $urlParams['materialtype'] = self::$shopcoins_materialsRule[$part];
    	        continue;    	        
    	    } 
    	    
    	    if ($_ = preg_match('@^(.*)?_(g|nl|mt|th|cn|ysp)([0-9]+)$@i',$part, $match)){    			
    			if ($match[2] == 'g'){     			    
    				$urlParams['group_id'] = (int)$match[3]; 
    				continue;    				
    			}
    			if ($match[2] == 'nl'){     			    
    				$urlParams['nominal_id'] = (int)$match[3]; 
    				continue;    				
    			}
    			
    			if ($match[2] == 'mt'){     			    
    				$urlParams['metal_id'] = (int)$match[3]; 
    				continue;    				
    			}
    			
    			if ($match[2] == 'th'){     			    
    				$urlParams['theme_id'] = (int)$match[3]; 
    				continue;    				
    			}
    			
    			if ($match[2] == 'ysp'){     			    
    				$urlParams['year'] = (int)$match[3]; 
    				continue;    				
    			}
    			if ($match[2] == 'cn'){     			    
    				$urlParams['condition_id'] = (int)$match[3]; 
    				continue;    				
    			}    			
    		}
    	}
    	   	
    	return $urlParams;
    }    
    
    static function makePrettyUrl($params, $domain="http://www.numizmatik.ru/shopcoins"){  
    	$pattern = $domain;
    	$amp_url ='';
    	$separator = '/?';
    	$urlParams = array(
    			'materialtype'     => '',   			
    			'group' => '',    			
    			'metal'       => '',    			
    			'theme'  => '', 
    			'years'  => '', 
    			'years_p' => '',		
    			'nominal' => '',    
    			'condition' => '',  
    			'price' => '', 			
                'nocheck'  => null,
                'bydate'   => null,                
                'yearstart' => '',
                'yearend' => '',
                'mycoins' => '',
                'price' => '',
               // 'searchname' => '',?      
				'catalognewstr'=>'',
                'pricestart' => '',
                'priceend' => '',
                'pagenum'=> '',
                //для новостей
                'sp'=>'',
                'text'=>'',
                'usershopcoinssubscribe' =>'',
                'usercatalogsubscribe'=>'',
                'usermycatalog' => '',
                'usermycatalogchange' =>'usermycatalogchange'
    	);
    	
        
        
        if ( isset($params['materialtype'])){
            if($urlParams['materialtype']==100)  {  		
                $pattern .= '/newcoins';
            } elseif ($urlParams['materialtype']==200)  {  		
                $pattern .= '/revaluation';               
            } elseif(isset(self::$shopcoins_materialIDsRule[$params['materialtype']])) {
                $pattern .= '/'.self::$shopcoins_materialIDsRule[$params['materialtype']];                
            }
    	}     	
    	
   		if (count($params['group'])==1 ){
    		$group_id = key($params['group']);
    		$pattern .= '/'.contentHelper::groupUrl($params['group'][$group_id],$group_id);
    	} elseif (count($params['group'])){
    		foreach ($params['group'] as $group){
	    		$amp_url .= $separator.'groups[]='.$group;
	    	    $separator = '&';
    		}
    	}
       
    	if (count($params['nominal'])==1 ){
    		$nominal_id = key($params['nominal']);
    		$pattern .= '/'.contentHelper::nominalUrl($params['nominal'][$nominal_id],$nominal_id);
    	} elseif (count($params['nominal'])){
    		foreach ($params['nominal'] as $nominal){
	    		$amp_url .= $separator.'nominals[]='.$nominal;
	    	    $separator = '&';
    		}
    	}
    	
    	if (count($params['years'])==1 ){    		
    		$pattern .= '/years_'.$params['years'][0];
    	} elseif (count($params['years'])){
    		foreach ($params['years'] as $years){
	    		$amp_url .= $separator.'years[]='.$years;
	    	    $separator = '&';
    		}
    	}
    	
    	if (count($params['years_p'])==1 ){    		
    		$pattern .= '/y_ysp'.$params['years_p'][0];
    	} elseif (count($params['years_p'])){
    		foreach ($params['years_p'] as $years_p){
	    		$amp_url .= $separator.'years_p[]='.$years_p;
	    	    $separator = '&';
    		}
    	}
    	
    	if (count($params['metal'])==1 ){ 
    		$metal_id = key($params['metal']);   		
    		$pattern .= '/'.contentHelper::metalUrl($params['metal'][$metal_id],$metal_id);
    	} elseif (count($params['metal'])){
    		foreach ($params['metal'] as $metal){
	    		$amp_url .= $separator.'metals[]='.$metal;
	    	    $separator = '&';
    		}
    	}
    	
    	if (count($params['condition'])==1 ){ 
    		$condition_id = key($params['condition']);   		
    		$pattern .= '/'.contentHelper::conditionUrl($params['condition'][$condition_id],$condition_id);
    	} elseif (count($params['condition'])){
    		foreach ($params['condition'] as $condition){
	    		$amp_url .= $separator.'conditions[]='.$condition;
	    	    $separator = '&';
    		}
    	}
    	
    	if (count($params['theme'])==1 ){ 
    		$theme_id = key($params['theme']);   		
    		$pattern .= '/'.contentHelper::themeUrl($params['theme'][$theme_id],$theme_id);
    	} elseif (count($params['theme'])){
    		foreach ($params['theme'] as $theme){
	    		$amp_url .= $separator.'themes[]='.$theme;
	    	    $separator = '&';
    		}
    	}
    	if (count($params['simbol'])==1 ){ 
    		$simbol_id = key($params['simbol']);   		
    		$pattern .= '/'.self::simbolUrl($params['simbol'][$simbol_id],$simbol_id);
    	} elseif (count($params['simbol'])){
    		foreach ($params['simbol'] as $simbol){
	    		$amp_url .= $separator.'simbols[]='.$simbol;
	    	    $separator = '&';
    		}
    	}
    	if (count($params['price'])==1 ){ 
    		$pattern .= '/price_'.$params['price'][0];
    	} elseif (count($params['price'])){    		
    		foreach ($params['price'] as $price){
	    		$amp_url .= $separator.'prices[]='.$price;
	    	    $separator = '&';
    		}
    	}
    	
    	if (count($params['sp'])==1 ){ 
    		$pattern .= '/sp_'.$params['sp'][0];
    	} elseif (count($params['sp'])){    		
    		foreach ($params['sp'] as $sp){
    			$amp_url .= $separator.'sp_s[]='.$sp;
    			$separator = '&'; 
    		}    	       		
    	}   
    	
    	
    	if ($params['nocheck']){    		
    		$amp_url .= $separator.'nocheck=1';
    	    $separator = '&';    		
    	}
    	if ($params['bydate']){    		
    		$amp_url .= $separator.'bydate='.$bydate;
    	    $separator = '&';    		
    	}
    	if ($params['catalognewstr']){    		
    		$amp_url .= $separator.'catalognewstr=1';
    	    $separator = '&';    		
    	}
    	
    	if ($params['yearstart']){    		
    		$amp_url .= $separator.'yearstart='.$params['yearstart'];
    	    $separator = '&';    		
    	}
    	
    	if ($params['yearend']){    		
    		$amp_url .= $separator.'yearend='.$params['yearend'];
    	    $separator = '&';    		
    	}
    	
    	if ($params['mycoins']){    		
    		$amp_url .= $separator.'mycoins=1';
    	    $separator = '&';    		
    	}
    	
    	if ($params['pricestart']){    		
    		$amp_url .= $separator.'pricestart='.$params['pricestart'];
    	    $separator = '&';    		
    	}
    	
    	if ($params['priceend']){    		
    		$amp_url .= $separator.'priceend='.$params['priceend'];
    	    $separator = '&';    		
    	}    	

        if ($params['usershopcoinssubscribe']){    		
    		$amp_url .= $separator.'usershopcoinssubscribe='.$params['usershopcoinssubscribe'];
    	    $separator = '&';    		
    	}        	
    	
    	if ($params['usercatalogsubscribe']){    		
    		$amp_url .= $separator.'usercatalogsubscribe='.$params['usercatalogsubscribe'];
    	    $separator = '&';    		
    	}   
    	if ($params['usermycatalog']){    		
    		$amp_url .= $separator.'usermycatalog='.$params['usermycatalog'];
    	    $separator = '&';    		
    	}   
    	if ($params['usermycatalogchange']){    		
    		$amp_url .= $separator.'usermycatalogchange='.$params['usermycatalogchange'];
    	    $separator = '&';    		
    	}   
    	
    	if ($params['text']){    		
    		$amp_url .= $separator.'text='.$params['text'];
    	    $separator = '&';    		
    	}
    	  
   		if ($params['pagenum']){    		
    		$amp_url .= $separator.'pagenum='.$params['pagenum'];
    	    $separator = '&';    		
    	}   
        $result_url = $pattern.$amp_url;       
        $result_url = str_replace('//', '/', $result_url);  
        $result_url = str_replace('http:/','http://',$result_url);
    	return $result_url;
    }
    
    static function  groupUrl($title,$id){
        return contentHelper::strtolower_ru($title).'_g'.$id;       
    }
    static function  nominalUrl($title,$id){
        return contentHelper::strtolower_ru($title).'_nl'.$id;       
    }
    static function  metalUrl($title,$id){
        return contentHelper::strtolower_ru($title).'_mt'.$id;       
    }
    static function  conditionUrl($title,$id){
        return contentHelper::strtolower_ru($title).'_cn'.$id;       
    }
    static function  themeUrl($title,$id){
        return contentHelper::strtolower_ru($title).'_th'.$id;       
    }
    static function  simbolUrl($title,$id){
        return contentHelper::strtolower_ru($title).'_s'.$id;       
    }
    
    static function makePrettyOfferUrl($urlParams,$materialIDsRule,$ThemeArray, $data, $shopcoins_class,$return_params = array())
    {              
       // , $divisions, $Country, $Region, $RegionPart, $City, $domain
    	$domain="www.numizmatik.ru/shopcoins";
    	
    	$params = $urlParams;
    	
    	$texts = array();
    	
        foreach($params as $key => $value){
            if (!$params[$key]){
                unset($params[$key]);
            }
        }
        
        $pattern = 'http://'.$domain;
    
        if ( isset($params['materialtype'])){
            if($params['materialtype']==100)  {  		
                $pattern .= '/newcoins';
                $texts[] = "Новинки";
            } elseif ($params['materialtype']==200)  {  		
                $pattern .= '/revaluation';
                $texts[] = "Распродажа";
            } elseif(isset(self::$shopcoins_materialIDsRule[$params['materialtype']])) {
                $pattern .= '/'.self::$shopcoins_materialIDsRule[$params['materialtype']];
                $texts[] = contentHelper::$menu[$params['materialtype']];
            }
    	} 
    	
    	if (isset($params['group_id'])){
    	    $groupData = $shopcoins_class->getGroupItem($params['group_id']);
    	    if($groupData){    	    
        	   $pattern .= contentHelper::groupUrl($groupData['name'],$params['group_id']);
        	   $texts[] = $groupData['name'];
    	    }
    	}
    	
    	if (isset($params['nominal_id'])){
    	    $nominalMainTitle = $shopcoins_class->getNominal($params['nominal_id']);
    	    if($nominalMainTitle){    	    
        	   $pattern .= contentHelper::nominalUrl($nominalMainTitle,$params['nominal_id']);
        	   $texts[] = $nominalMainTitle;
    	    }
    	}
    	
    	if (isset($params['metal_id'])){
    	    $metalMainTitle = $data['metalls'][$params['metal_id']];   	   
    	    if($metalMainTitle){    	    
        	   $pattern .= contentHelper::metalUrl($metalMainTitle,$params['metal_id']);
        	   $texts[] = $metalMainTitle;
    	    }
    	}    	 
        
        if(isset($params['condition_id'])){
            $conditionMainTitle = $data['conditions'][$params['condition_id']]; 
            if($conditionMainTitle) {
                $pattern .= contentHelper::conditionUrl($conditionMainTitle,$params['condition_id']);   
                $texts[] = $conditionMainTitle; 
            }            
        } 
        
    	if (isset($params['year'])){    	   
    	   $pattern .= '/y_ysp'.$params['year'];
    	   $texts[] = $params['year'];
    	}
    	
    	if(isset($params['theme_id'])){
            $themeMainTitle = $ThemeArray[$params['theme_id']];   
            if($themeMainTitle) {
                $pattern .= contentHelper::themeUrl($themeMainTitle,$params['theme_id']);  
                $texts[] = $themeMainTitle;   
            }
        }        
        if(isset($return_params['full'])&&$return_params['full']){
            return array('href'=>$pattern,'title'=>implode(" ",$texts));
        } 
    	
        return $pattern;      
    }
}
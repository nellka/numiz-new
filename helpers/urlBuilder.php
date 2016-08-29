<?php
class urlBuild{ 
       
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
             $url = str_replace("&nocheck=1","",$url);
             $url .= (strpos($url,"?")) ? "&nocheck=1":"?nocheck=1";
         }
         
         if(strpos($url,"?nocheck=1")&&$user_id==811){
             $url = str_replace("?nocheck=1","",$url);
             $url .= (strpos($url,"?")) ? "&nocheck=1":"?nocheck=1";
         }
         return $url;
    }
        
    static function parseUrl($url,$materialsRule){   
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
    	    
    	    if(isset($materialsRule[$part])){
    	        $urlParams['materialtype'] = $materialsRule[$part];
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
    
    static function makePrettyOfferListUrl($urlParams, $domain="http://www.numizmatik.ru/shopcoins")
    {     die('jjj');    
       	$params = $urlParams;
    	
        foreach($params as $key => $value){
            if (!$params[$key]){
                unset($params[$key]);
            }
        }
        var_dump($params,$materialsRule);
       
        /*//var_dump($params);
    	if ( isset($params['module']) ){
    	    $domain = $domain.'/'.$params['module'];
    	}
    	
    	if ( isset($params['industry_id']) && (int)$params['industry_id'] > 0 ){
    		$_ = $divisions->getById($params['industry_id']);
    		$pattern = 'http://'.$_['translit'].'.'.$domain;
    		unset($_);
    	} else {
    		$pattern = 'http://'.$domain;
    	}
    	
    	if (isset($params['country']) || isset($params['region']) || isset($params['region_part']) || isset($params['city'])){
    		
    		if (isset($params['city'])){
    			$pattern .= '/city';
    		} elseif (isset($params['region_part'])){
    			$pattern .= '/region_part';
    		} elseif (isset($params['region'])){    		    
    			$pattern .= '/' . $Region->getTranslitById($params['region']);
    		} elseif (isset($params['country'])){
    			$pattern .= '/' . $Country->getTranslitById($params['country']);
    		}
    	}

    	if (isset($params['market_group_id']) || isset($params['market_id'])){
    		
    		if (isset($params['goods_group_id']) || isset($params['goods_id'])){
    			
    			if (isset($params['goods_id'])) {
    				$_ = $divisions->getById($params['market_id']);
    				$pattern .= '/'.$_['translit'];
    				$_ = $divisions->getById($params['goods_id']);
    				$pattern .= '/'.$_['translit'];
    				unset($_);
    			} elseif (isset($params['goods_group_id'])) {
    				$_ = $divisions->getById($params['market_id']);
    				$pattern .= '/'.$_['translit'];
    				$_ = $divisions->getById($params['goods_group_id']);
    				$pattern .= '/'.$_['translit'];
    				unset($_);
    			}
    			
    		} else {
    			if (isset($params['market_id'])){
    				$_ = $divisions->getById($params['market_id']);
    				$pattern .= '/'.$_['translit'];
    			} elseif(isset($params['market_group_id'])){
    				$_ = $divisions->getById($params['market_group_id']);
    				$pattern .= '/'.$_['translit'];
    			}
    		}
    	}
    	
    	$separator = '?';
    	
    	if(isset($params['view'])&&$params['view']){
    	    $pattern .=$separator.'view='.$params['view'];
    	    $separator = '&';
    	}
    	
    	if(isset($params['page'])&&$params['page']){
    	    $pattern .=$separator.'page='.$params['page'];
    	    $separator = '&';
    	}
    	
    	if(isset($params['search'])&&$params['search']){
    	    $pattern .=$separator.'search='.$params['search'];
    	    $separator = '&';
    	}
    	if(isset($params['type'])&&$params['type']){
	        $pattern .=$separator."type=".$params['type'];
            $separator = '&';
     	} 
    	return $pattern;*/
    }
    static function makePrettyOfferTitle($urlParams){
        
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
            } elseif(isset($materialIDsRule[$params['materialtype']])) {
                $pattern .= '/'.$materialIDsRule[$params['materialtype']];
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
<?php

/*Класс для получения информации о пользователе*/
class model_user extends Model_Base 
{		
    public $user_id;    
    public $username;    
    public $fio; 
	public $orderusernow;
	protected $session_int;
	
	static $ArrayForCode = array ("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","j","k","l",
	"m","n","o","p","q","r","v","u","w","i","x","y","z");
    //получаем баланс пользователя
    public function getUserBalance(){
        $select = $this->db->select()
		               ->from('user_bonus_balance',array('balance'))
		               ->where('user_id =?',$this->user_id);
    	return (integer)$this->db->fetchOne($select);      
	}

	public function add_user_describe_log($coin_id){
		 $data = array('coin_id'=>$coin_id,
		               'event_date' =>time(),
		               'user_id'=> $this->user_id);
         $this->db->insert('user_describe_log',$data); 
	}
	
	protected function setSessionInt(){
		if($this->user_id) return 0;
 
		$select  =  $this->db->select()
                  ->from('user_sessions',array('id'))
                  ->where('session_id=?',$this->getSession());
        $session_int = $this->db->fetchOne($select);
        if($session_int) {
        	$this->session_int = $session_int;
        	return ;
        }
        $data = array('session_id' => $this->session_id,
                      'dateinsert' => time());  
              
        $this->db->insert('user_sessions',$data);   
        $this->session_int = $this->db->lastInsertId('user_sessions');
        return ;
	}
	
	public function getStat(){
		
		$coinscount =  0;
		$searchcount = 0;
		$filterscount = 0;
		
		if($this->user_id){  
			$coinscount = $this->cache->load("coinscount_user_".$this->user_id);

			if(!$coinscount){
				
				 $select = $this->db->select()
		               ->from('shopcoins_view_shopcoins',array("count(id)"))
		               ->where('user_id =?',$this->user_id);
		    	$coinscount = (integer)$this->db->fetchOne($select);  
		    	
		    	$this->cache->save($coinscount, "coinscount_user_".$this->user_id);    
			}
			
			$searchcount = $this->cache->load("searchcount_user_".$this->user_id);

			if(!$searchcount){
				
				 $select = $this->db->select()
		               ->from('shopcoins_view_search',array("count(id)"))
		               ->where('user_id =?',$this->user_id);
		    	$searchcount = (integer)$this->db->fetchOne($select);  
		    	
		    	$this->cache->save($searchcount, "searchcount_user_".$this->user_id);    
			}
			
			$filterscount = $this->cache->load("filterscount_user_".$this->user_id);
			
			if(!$filterscount){
				
				 $select = $this->db->select()
		               ->from('shopcoins_view_filter',array("count(id)"))
		               ->where('user_id =?',$this->user_id);
		    	$filterscount = (integer)$this->db->fetchOne($select);  
		    	
		    	$this->cache->save($filterscount, "filterscount_user_".$this->user_id);    
			}
		} elseif(!$this->user_id) {
			$this->setSessionInt();
			
			$coinscount = $this->cache->load("coinscount_ses_".$this->getSession());

			if(!$coinscount){
				
				 $select = $this->db->select()
		               ->from('shopcoins_view_shopcoins',array("count(id)"))
		               ->where('session_id =?',$this->session_int);
		    	$coinscount = (integer)$this->db->fetchOne($select);  
		    	
		    	$this->cache->save($coinscount, "coinscount_ses_".$this->getSession());    
			}
			
			$searchcount = $this->cache->load("searchcount_ses_".$this->getSession());

			if(!$searchcount){
				
				 $select = $this->db->select()
		               ->from('shopcoins_view_search',array("count(id)"))
		               ->where('session_id =?',$this->session_int);
		    	$searchcount = (integer)$this->db->fetchOne($select);  
		    	
		    	$this->cache->save($searchcount, "searchcount_ses_".$this->getSession());    
			}
			
			$filterscount = $this->cache->load("filterscount_ses_".$this->getSession());
			
			if(!$filterscount){
				
				 $select = $this->db->select()
		               ->from('shopcoins_view_filter',array("count(id)"))
		               ->where('session_id =?',$this->session_int);
		    	$filterscount = (integer)$this->db->fetchOne($select);  
		    	
		    	$this->cache->save($filterscount, "filterscount_ses_".$this->getSession());    
			}
		}
		
		$fullcount = $coinscount+$searchcount+$filterscount;		
		
		return array('fullcount'=>$fullcount,
		             'coinscount'=> $coinscount,
		             'searchcount' => $searchcount,
		             'filterscount' => $filterscount);
		 /*$data = array('coin_id'=>$coin_id,
		               'event_date' =>time(),
		               'user_id'=> $this->user_id);
         $this->db->insert('user_describe_log',$data); */
	}
	
	/*public function getUserCouponType(){	   
	      $select = $this->db->select()
		               ->from('coupon',array('type'))
		               ->where('user =?',$this->user_id)
		               ->where('`check`=1')
		               ->order(array('type desc','dateinsert desc'))
		               ->limit(1);
		  return (integer)$this->db->fetchOne($select);
	 }*/
	 //при оформлении заказа

	 public function getUserCoupon($data=array(),$active=false){
		$select = $this->db->select()
			->from('coupon')
			->where('user =?',$this->user_id);

		foreach($data as $key=>$value){
			$select->where("$key=?",$value);
		}
		
		if($active){
			$select->where("dateend>?",time());
		}

		return $this->db->fetchAll($select);
	 }

	public function getUserCouponCount($data=array()){
		$select = $this->db->select()
			->from('coupon',array('count(*)'))
			->where('user =?',$this->user_id);
		foreach($data as $key=>$value){
			$select->where("$key=?",$value);
		}
		//echo $select->__toString();
		return (int)$this->db->fetchOne($select);
	}

	 public function getFriendCouponCode(){
	      $select = $this->db->select()
		               ->from('coupon',array('coupon.code'))
		               ->join('userfrend','coupon.fromuser=userfrend.frend',array())
		               ->where('coupon.user =?',$this->user_id)
		               ->where('coupon.check=1 and coupon.user=userfrend.user and coupon.type=1');	
		  return $this->db->fetchOne($select);
	}
	
    public function decrementUserBalance($how_much){
        $balance = $this->getUserBalance();
        
	    $data = array('user_id' => $this->user_id, 'balance' => ($balance - intval($how_much)));
		$this->db->update('user_bonus_balance',$data,"balance >= 0 and user_id = ".$this->user_id);    	
    }

	public function addUserBalance(){
		 $select = $this->db->select()
		               ->from('user_bonus_balance',array('user_id','balance'))
		               ->where('user_id =?',$this->user_id);	
		$dataBalance  = $this->db->fetchRow($select);             
		if (!$dataBalance) {
			$data = array('user_id' => $this->user_id, 'balance' => 1);
			$this->db->insert('user_bonus_balance',$data);
		} else{
			$data = array('user_id' => $this->user_id, 'balance' => $dataBalance['balance'] + 1);
			$this->db->update('user_bonus_balance',$data,"user_id = ".$this->user_id);
		}
	}
	
	public function addOrderBonusLog($order_id, $sum){
	    $data = array(
                    'user_id' => $this->user_id, 
	                'order_id' => $order_id, 
	                'sum' => $sum, 
	                'created_at' => time());
		$this->db->insert('user_bonus_log',$data);
    }
    
    public function addCoupon($shopcoinsorder,$dis,$dateend){
        $couponups = 1;
        
		while ($couponups==1) {
			$code = '';
            for ($i=0;$i<16;$i++) {  
                if ($i==4 || $i == 8 || $i==12)
					$code .= "-";      
            	$code .= self::$ArrayForCode[rand(0,9)];
            }

            $select = $this->db->select()
		               ->from('coupon',array('count(*)'))
		               ->where('code =?',$code);	         
		    $coupon_exist = $this->db->fetchOne($select); 

		    if(!$coupon_exist) $couponups = 2;     
		}

		$data = array('user' => $this->user_id,
		              'order'=>$shopcoinsorder,
		              'sum'=>$dis,
		              'code'=>$code,
		              'dateend'=>$dateend,
		              'type'=>1,
		              'dateinsert'=>time(),
		              'check'=>1);
        $this->db->insert('coupon',$data);
    }
    
    public function createFreandCoupon(){
        $couponups = 1;
        while ($couponups==1) {        
            $code = '';
            for ($i=0;$i<12;$i++) {        
            	$code .= self::$ArrayForCode[rand(0,15)];
            }

            $select = $this->db->select()
		               ->from('user',array('user'))
		               ->where('codeforfrend =?',$code);	         
		    $coupon_exist = $this->db->fetchOne($select); 

		    if(!$coupon_exist) $couponups = 2;           	
        }
        
        $data = array('codeforfrend' => $code);
        $this->db->update('user',$data,"user = ".$this->user_id);
        
        return 	$code;
    }
    			
	public function getUserData(){
	    
	   if(!(int)$this->user_id) return array();
	   
	   $select = $this->db->select()
		               ->from('user')
		               ->where('user =?',$this->user_id);	         
		return $this->db->fetchRow($select); 
	}
	
	public function getUserDataByID($id){
	    
	   if(!(int)$id) return false;
	   
	   $select = $this->db->select()
		               ->from('user')
		               ->where('user =?',$id);	         
	   return $this->db->fetchRow($select); 
	}
	
	public function getUserDataByEmail($email=''){
		$select = $this->db->select()
			->from('user')
			->where('email =?',$email);
		return $this->db->fetchRow($select);
	}
	//добавляем нового пользователя и подписываем на новости если надо
	 public function addNewUser($data,$is_subsrib = false){
	     $userId =$this->addNewRecord($data);
	     $this->addNewForumUser($data);
	     //добавляем подписку	 
	     if($is_subsrib){
	         $this->addsubscribe($userId);	                 
	     }
	     return true;
	     //отправляем письмо о регистрации	     
	 }
	 public function addNewForumUser($User){
	 	$salt = $this->fetch_user_salt();   
        $datebithday  = '';               
	 	$data = array('username' => $User['email'], 
	 				  'password' => $this->hash_password($User['userpassword'], $salt), 
	 				  'salt'     => $salt,
	 				  'email'    => $User['email'], 
	 				  'usergroupid' => 2,
	 				  'joindate'    => time(),
	 				  'birthday'    => date('m-d-Y',$datebithday),
	 				  'birthday_search' => date('m-d-Y',$datebithday),
	 				  'options'	 =>	 4509205,
	 				  'timezoneoffset'=>3); 
        $this->db->insert('vbull_user',$data); 
        
        $insert_id  = $this->db->lastInsertId('vbull_user');					
		$data = array('userid' => $insert_id); 
        $this->db->insert('vbull_userfield',$data);         	
		$this->db->insert('vbull_usertextfield',$data); 			
	 }
	 
	 
	 public function getIdentity(){
	     return $this->user_id;
	 }
	 
	 public function setIdentity($user_id){
	     return $this->user_id = $user_id;
	 }
	 
	  public function getSession(){
	     return $this->session_id;
	 }
	 
	 public function setSession(){
	     return $this->session_id = session_id();
	 }
	 
	  public function getUsername(){
	     return $this->username;
	 }
	 
	 public function getUserfio(){
	     return $this->fio;
	 }
	 
	 public function addsubscribe($user_id,$data=array('news'=>1)){
	     if($user_id){
    	     $select = $this->db->select()
    		               ->from('subscribe')
    		               ->where('user =?',$user_id);
        	 $subscribs = $this->db->fetchRow($select); 
        	 $data['user'] = $user_id; 
        	 $data['dateupdate'] = time();
        	 $data['typemail'] = 1;
        	 if(!$subscribs){
        	     $data['dateinsert'] = time();     	     
        	     $this->db->insert('subscribe',$data); 
        	 } else {
        	     $this->db->update('subscribe',$data,"user=$user_id"); 
        	 }
        	 return true;
	     } else {
	         return false;
	     }
	 }
	 public function getUserBaseData(){	 	
	     
	 	$data['user_id'] = $this->getIdentity();
	 	$data['orderusernow'] = 0;
		$data['username'] = $this->getUsername();
		$data['fio'] = $this->getUserfio();
    	$data['balance'] = $this->getUserBalance();
    	
    	return $data;
	 }
	 
	 public function is_user_has_premissons(){
	     if($this->user_id == 336844) return TRUE;
		 $sql = "SELECT 1 FROM `order` WHERE order.check = 1 and order.user =".$this->user_id." having COUNT(1) >= 5";
    	
    	 return $this->db->fetchRow($sql)?true:FALSE;    
    }
    
    //если пользователь залогинен и запрещено делать заказы, то проверяем те заказы, которые были
    public 	function setOrderusernow(){
    	 $select = $this->db->select()
    		               ->from('order',array('count(*)'))
    		               ->where('user =?',$this->user_id)
    		               ->where('`check`=1 and SendPost=0 and sum>=500');

        return  $this->db->fetchOne($select)?1:0;
    }

    public 	function getUserCurrentOrder(){
        
        if($this->user_id){
            
            $reservetime = 18000;
            
            $domain = $_SERVER["HTTP_HOST"];
            $domain = '.'.str_replace('www.','', $domain);

            $select = $this->db->select()
        		               ->from('order')
        		               ->where('user =?',$this->user_id)
        		               ->where("`check`=0  and `user`!=811 and `user`>0 and `date`>?",(time()-$reservetime))
        		               ->limit(1);   
        		               
        	if($this->user_id==352480){
        	    //echo "<br><br>";
                //echo $select->__toString();
                // echo "<br><br>";
            }
            		               
        	$rows_or = $this->db->fetchRow($select);
        		               
            if ($rows_or) {
                
    			$shopcoinsorder = $rows_or['order'];  
    			  			
    			setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/", $domain);
    			setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/");
    			setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/shopcoins/", ".shopcoins.numizmatik.ru");
    			setcookie("shopcoinsorder", $shopcoinsorder, $rows_or['date'] + $reservetime, "/");   				
				
    			$_SESSION['shopcoinsorder'] = $shopcoinsorder;
				$_SESSION['orderstart'] = $rows_or["date"];

				return $shopcoinsorder;
    		}    		
        	 
        }

        return 0;
    }

	protected function request_uri() {

		if (isset($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
		}
		else {
			if (isset($_SERVER['argv'])) {
				$uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['argv'][0];
			}
			elseif (isset($_SERVER['QUERY_STRING'])) {
				$uri = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
			}
			else {
				$uri = $_SERVER['SCRIPT_NAME'];
			}
		}
		// Prevent multiple slashes to avoid cross site requests via the FAPI.
		$uri = '/' . ltrim($uri, '/');

		return $uri;
	}

	public function set_login_cookies($email) {
		//$userid
		// Load required vB user data.
		$select =  $select = $this->db->select()
			->from('vbull_user')
			->where('username =?',$email)
			->limit(1);
		$vbuser = $this->db->fetchRow($select);

		if(!$vbuser){
			$select =  $select = $this->db->select()
				->from('vbull_user')
				->where('email =?',$email)
				->limit(1);
			$vbuser = $this->db->fetchRow($select);
		}

		//$vbuser = db_fetch_array(drupalvb_db_query("SELECT userid, password, salt FROM {user} WHERE userid = %d", $userid));
		if (!$vbuser) {
			return FALSE;
		}

		//$vb_config = drupalvb_get('config');
		//$vb_options = drupalvb_get('options');

		//$cookie_prefix = (isset($vb_config['Misc']['cookieprefix']) ? $vb_config['Misc']['cookieprefix'] : 'bb');
		$cookie_prefix = 'bb';
		$cookie_path = '/';
		$now = time();
// вот наша волшебная переменная
//  $expire = $now + (@ini_get('session.cookie_lifetime') ? ini_get('session.cookie_lifetime') : 60 * 60 * 24 * 365);
// сделал для пробы вот так
// $expire = 60 * 60 * 24 * 365;
// а надо вот так
		$expire =  $now +  60 * 60 * 24 * 365;

		//$vb_cookie_domain = (!empty($vb_options['cookiedomain']) ? $vb_options['cookiedomain'] : $GLOBALS['cookie_domain']);
		$vb_cookie_domain = "www.numizmatik.ru";
		// Per RFC 2109, cookie domains must contain at least one dot other than the
		// first. For hosts such as 'localhost' or IP Addresses we don't set a cookie domain.
		// @see conf_init()
		/*if (!(count(explode('.', $vb_cookie_domain)) > 2 && !is_numeric(str_replace('.', '', $vb_cookie_domain)))) {
			$vb_cookie_domain = '';
		}*/

		// Clear out old session (if available).
		if (!empty($_COOKIE[$cookie_prefix .'sessionhash'])) {
			$this->db->delete('vbull_session',"sessionhash = '".$_COOKIE[$cookie_prefix .'sessionhash']."'");
			//drupalvb_db_query("DELETE FROM {session} WHERE sessionhash = '%s'", $_COOKIE[$cookie_prefix .'sessionhash']);
		}

		// Setup user session.
		//$ip = implode('.', array_slice(explode('.', drupalvb_get_ip()), 0, 4 - $vb_options['ipcheck']));
		$ip = implode('.', array_slice(explode('.', $_SERVER['REMOTE_ADDR']), 0, 4 - 1));
		$idhash = md5($_SERVER['HTTP_USER_AGENT'] . $ip);

		//$sessionhash = md5($now . $this->request_uri() . $idhash . $_SERVER['REMOTE_ADDR'] . $this->fetch_user_salt(6));
		$sessionhash = $idhash;
			//drupalvb_db_query("REPLACE INTO {session} (sessionhash, userid, host, idhash, lastactivity, location, useragent, loggedin) VALUES ('%s', %d, '%s', '%s', %d, '%s', '%s', %d)", $sessionhash, $vbuser['userid'], substr($_SERVER['REMOTE_ADDR'], 0, 15), $idhash, $now, '/forum/', $_SERVER['HTTP_USER_AGENT'], 2);

		$sql = "REPLACE INTO vbull_session (sessionhash, userid, host, idhash, lastactivity, location, useragent, loggedin) VALUES ('$sessionhash', '".$vbuser['userid']."', '".substr($_SERVER['REMOTE_ADDR'], 0, 15)."', '$idhash', '".$now."', '/forum/', '".$_SERVER['HTTP_USER_AGENT']."', 2)";
		$this->db->query($sql);
		// Setup cookies.
		setcookie($cookie_prefix .'sessionhash', $sessionhash, $expire, $cookie_path, $vb_cookie_domain);
		setcookie($cookie_prefix .'lastvisit', $now, $expire, $cookie_path, $vb_cookie_domain);
		setcookie($cookie_prefix .'lastactivity', $now, $expire, $cookie_path, $vb_cookie_domain);
		setcookie($cookie_prefix .'userid', $vbuser['userid'], $expire, $cookie_path, $vb_cookie_domain);
		//setcookie($cookie_prefix .'password', md5($vbuser['password'] . variable_get('drupalvb_license', '')), $expire, $cookie_path, $vb_cookie_domain);
		setcookie($cookie_prefix .'password', md5($vbuser['password']), $expire, $cookie_path, $vb_cookie_domain);
		return TRUE;
	}
    //проверяем, что пользователь залогинен
	public function loginUsForForum($email,$userpassword){
		$this->set_login_cookies($email);
	}
	
	//логинем по ссылке из подписки
	public function loginByMailkey($mailkey){
	    if(!$mailkey) return;
	    
	    $select = $this->db->select()
		               ->from('subscribe',array('user'))
		               ->where("mailkey=?",$mailkey);		               
    	$userId  = $this->db->fetchOne($select);    
    	if(!$userId) return ;
    	  
    	$select = $this->db->select()
		               ->from('user')
		               ->where("user=?",$userId);		               
    	$user_data  = $this->db->fetchRow($select);
    	   	
    	if(!$user_data) return ;
    	
    	if($mailkey==md5("Numizmatik_Ru".$user_data['user'].$user_data['userpassword'])){
    	    var_dump($user_data); 
    	    $this->logout();  
    	    $this->loginUser($user_data['email'],$user_data['userpassword']);
    	}    	
	    return;
	}
	
	//проверяем, что пользователь залогинен
	public function loginUser($email,$userpassword){
	   if ($email &&$userpassword){
	       $show = false;
	       $userpassword_new = '';
	       $userpassword_new = '';
	       if(isset($_COOKIE['cookiesuser'])&&$_COOKIE['cookiesuser']=='317741'){
	           // var_dump($email,$userpassword,contentHelper::get_encoding($userpassword),contentHelper::get_encoding($userpassword));
	           /*$show = true;
	          
	           var_dump(iconv( "CP1251//TRANSLIT//IGNORE","UTF8", $email));
	           echo "<br>";*/
	       }
	      
	       if(contentHelper::get_encoding($email)=='windows-1251'){
	           $email = iconv( "CP1251//TRANSLIT//IGNORE","UTF8", $email);
	          /* if($show){
	               echo "<br>";
	               var_dump($email);
	               echo "<br>";
	           }*/
	       }
	       
	       if(contentHelper::get_encoding($userpassword)=='windows-1251'){
	           $userpassword = iconv( "CP1251//TRANSLIT//IGNORE","UTF8", $userpassword);
	       }
	      // var_dump( $email_new,$userpassword_new);
	       // if( $email_new)  $email =  $email_new;
	        //if($userpassword_new) $userpassword = $userpassword_new;
	        
	        if($show){
	          // var_dump($email,$userpassword,$email_new,$userpassword_new);
	       }
	       //получаем данные из базы
	        $select = $this->db->select()
		               ->from('user',array('fio','user', 'userlogin', 'userpassword'))
		               ->where("userlogin='$email' or  email='$email'")
		               ->where("userpassword=?",$userpassword);		               
    	   $userData  = $this->db->fetchRow($select);       
          
        	if ($userData){
        	    $domains = array('.numizmatik.ru','www.numizmatik.ru');
		    	foreach ($domains as $d){
		    		setcookie("cookiesfio", $userData['fio'], time() + 86400 * 90, "/", $d);
	        		setcookie("cookiesuserlogin", $userData['userlogin'], time() + 86400 * 90, "/", $d);
	        		setcookie("cookiesuserpassword", $userData['userpassword'], time() + 86400 * 90, "/", $d);
	        		setcookie("cookiesuser", $userData['user'], time() + 86400 * 90, "/", $d);
		    	}
        	    
        	   /*
        		
        	    $domain = '.'.str_replace('www.','', $domain);
        	    
        		setcookie("cookiesfio", $userData['fio'], time() + 86400 * 90, "/", $domain);
        		setcookie("cookiesuserlogin", $userData['userlogin'], time() + 86400 * 90, "/", $domain);
        		setcookie("cookiesuserpassword", $userData['userpassword'], time() + 86400 * 90, "/", $domain);
        		setcookie("cookiesuser", $userData['user'], time() + 86400 * 90, "/", $domain);*/
        		
        		
        		$_SESSION['cookiesuserlogin'] = $userData['userlogin'];
        		$_SESSION['cookiesuserpassword'] = $userpassword;
        		$_SESSION['cookiesuser'] = $userData['user'];
        		$this->user_id = $userData['user'];
        		$this->username = $userData['userlogin'];
        		$this->fio = $userData['fio'];
                return true;
        	} else {
        		return false;
        	}
        }	    
	}
	
	public function is_logged_in(){
		
		$this->setSession();
		
	    $cookiesuserlogin = 0;
	    $cookiesuserpassword = 0;
	    // who writes like this ?
	   
        if (isset($_COOKIE['cookiesuserlogin'])&&trim($_COOKIE['cookiesuserlogin']))
        	$cookiesuserlogin = trim($_COOKIE['cookiesuserlogin']);
        elseif(isset($_SESSION['cookiesuserlogin'])&&trim($_SESSION['cookiesuserlogin']))
        	$cookiesuserlogin = trim($_SESSION['cookiesuserlogin']);
                	        
        if (isset($_COOKIE['cookiesuserpassword'])&&trim($_COOKIE['cookiesuserpassword']))
        	$cookiesuserpassword = trim($_COOKIE['cookiesuserpassword']);
        elseif(isset($_SESSION['cookiesuserpassword'])&&trim($_SESSION['cookiesuserpassword']))
        	$cookiesuserpassword = trim($_SESSION['cookiesuserpassword']);
     
        $userlogin = trim(str_replace("'","",$cookiesuserlogin));
        $userpassword = trim(str_replace("'","",$cookiesuserpassword)); 
        return $this->loginUser($userlogin,$userpassword);
    }
    
    public function logout(){
    	$domains = array('.numizmatik.ru','numizmatik.ru','.www.numizmatik.ru','www.numizmatik.ru','');
    	setcookie("sshort","0", time()-3600, "/shopcoins/"); 
    	foreach ($domains as $domain){
	    	//$domain = $_SERVER["HTTP_HOST"];
	    	setcookie("cookiesfio","1", time()-3600, "/");       	       		
			setcookie("cookiesuserlogin", "1", time()-3600, "/");
			setcookie("cookiesuserpassword", "1", time()-3600, "/");
			setcookie("cookiesuser", "1", time()-3600, "/");	
			
	    	setcookie("cookiesfio","1", time()-3600, "/",$domain);        		
			setcookie("cookiesuserlogin", "1", time()-3600, "/",$domain);
			setcookie("cookiesuserpassword", "1", time()-3600, "/",$domain);
			setcookie("cookiesuser", "1", time()-3600, "/",$domain);	
			
			setcookie("cookiesfio","1", time()-3600, "/shopcoins/",$domain);        		
			setcookie("cookiesuserlogin", "1", time()-3600, "/shopcoins/",$domain);
			setcookie("cookiesuserpassword", "1", time()-3600, "/shopcoins/",$domain);
			setcookie("cookiesuser", "1", time()-3600, "/shopcoins/",$domain);
			//setcookie("test",null,555,'/')      
    	}
    	setcookie("cookiesfio","1", time()-3600, "/shopcoins/");        		
		setcookie("cookiesuserlogin", "1", time()-3600, "/shopcoins/");
		setcookie("cookiesuserpassword", "1", time()-3600, "/shopcoins/");
		setcookie("cookiesuser", "1", time()-3600, "/shopcoins/");

		if (!empty($_COOKIE['bbsessionhash'])) {
			$this->db->delete('vbull_session',"sessionhash = '".$_COOKIE['bbsessionhash']."'");
		}
		setcookie("bbsessionhash", "0", time()-3600, "/","www.numizmatik.ru");
		setcookie("bbpassword", "", time()-3600, "/","www.numizmatik.ru");
	
		/*unset($_COOKIE['cookiesuserlogin']);
		unset($_COOKIE['cookiesuserpassword']);
		unset($_COOKIE['cookiesuser']);
		
		unset($_SESSION['cookiesuserlogin']);
		unset($_SESSION['cookiesuserpassword']);
		unset($_SESSION['cookiesuser']);*/
		
				
		unset($_SESSION['cookiesuserlogin']);
		unset($_SESSION['cookiesuserpassword']);
		unset($_SESSION['cookiesuser']);
    }
    
    protected function hash_password($password, $salt) {
		// if the password is not already an md5, md5 it now	
		if ($password == ''){}
		else if (!$this->verify_md5($password)){
			$password = md5($password);
		}
		
		return md5($password.$salt);	
	}
	
	protected function verify_md5(&$md5) {
	
		return (preg_match('#^[a-f0-9]{32}$#', $md5) ? true : false);	
	}
	
	protected function fetch_user_salt($length = 3) {	
		$salt = '';		
		for ($i = 0; $i < $length; $i++ ){		
			$salt .= chr(rand(33, 126));		
		}
				
		return $salt;	
	}

}
<?php

/*Класс для получения информации о пользователе*/
class model_user extends Model_Base 
{		
    public $user_id;    
    public $username;    
	public $orderusernow;
	
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
	    $data = array('user_id' => $this->user_id, 'balance' => 'balance' - intval($how_much));
		$this->db->update('user_bonus_balance',$data,"balance >= 0 and user_id = ".$this->user_id);    	
    }

	public function addUserBalance(){
		 $select = $this->db->select()
		               ->from('user_bonus_balance',array('user_id'))
		               ->where('user_id =?',$this->user_id);		               
		if (!$this->db->fetchOne($select)) {
			$data = array('user_id' => $this->user_id, 'balance' => 1);
			$this->db->insert('user_bonus_balance',$data);
		} else{
			$data = array('user_id' => $this->user_id, 'balance' => 'balance' + 1);
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
	   $select = $this->db->select()
		               ->from('user')
		               ->where('user =?',$this->user_id);	         
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
	 
	  public function getUsername(){
	     return $this->username;
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
                
                return $shopcoinsorder;
    		}    		
        	 
        }

        return 0;
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
        		
                return true;
        	} else {
        		return false;
        	}
        }	    
	}
	
	public function is_logged_in(){
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
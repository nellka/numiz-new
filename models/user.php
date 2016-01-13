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
	 public function getUserCouponType(){	   
	      $select = $this->db->select()
		               ->from('coupon',array('type'))
		               ->where('user =?',$this->user_id)
		               ->where('`check`=1')
		               ->order(array('type desc','dateinsert desc'))
		               ->limit(1);	
		  return (integer)$this->db->fetchOne($select);
	 }
	 public function getUserCouponCode(){	  
	      $select = $this->db->select()
		               ->from('coupon',array('coupon.code'))
		               ->join('userfrend','coupon.fromuser=userfrend.frend',array())
		               ->where('coupon.user =?',$this->user_id)
		               ->where('coupon.check=1 and coupon.user=userfrend.user and coupon.type=1');	
		  return (integer)$this->db->fetchOne($select);
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
	     //добавляем подписку	 
	     if($is_subsrib){
	         $this->addsubscribe($userId);	                 
	     }
	     return true;
	     //отправляем письмо о регистрации	     
	 }
	 
	 public function getIdentity(){
	     return $this->user_id;
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

	 /*
	 
		if (!$rows[0])
		{			
			//при вставке отправляем письмо с ключом
			$message = "Добрый день.

			Ваш email ( $email ) был подписан на рассылку Клуба Нумизмат
			http://www.numizmatik.ru/subscribe

			Для подтверждения рассылки пройдите по следующей ссылке.
			___ссылка___
			Без подтверждения, рассылка будет не действительна.


			С уважением, администрация Клуба Нумизмат.

			Телефон: 8-926-236-31-92
			Email: administrator@numizmatik.ru
			Web: http://www.numizmatik.ru
			";
		}
		else
		{
			$sql_update = "update subscribe set
			shopcoins = '".($shopcoins=="on"?1:0)."',
			news = '".($news=="on"?1:0)."',
			tboard = '".($tboard=="on"?1:0)."', 
			blacklist = '".($blacklist=="on"?1:0)."', 
			buycoins = '".($buycoins=="on"?1:0)."',
			biblio = '".($biblio=="on"?1:0)."', 
			advertise = '".($advertise=="on"?1:0)."',
			typemail = '$typemail',
			dateupdate = '".time()."'
			where user='$user';
			";
			//echo $sql_update;
			$result_update = mysql_query($sql_update);
		}
		
	 
	 */
	/*
	письмо о регистрации
	//далее отправляем письмо пользователю
					$mytext = "<br><br><p class=txt><b>Ваши данные</b>";
					$mytext .= "<p class=txt>ФИО: ".$fio;
					$mytext .= "<p class=txt>Логин: ".$userlogin1;
					$mytext .= "<p class=txt>Пароль: ".$userpassword1;
					$mytext .= "<p class=txt>E-mail: ".$email;
					$mytext .= "<p class=txt>Домашняя страница: ".$url;
					$mytext .= "<p class=txt>Пол: ";
					if ($sex==0) { $mytext .= "Мужчина"; } else { $mytext .= "Женщина";}
					$mytext .= "<p class=txt>Город: ";
					for ($i=0; $i<count($city_array); $i++)
					{
						if ($city==$i and $i!=0) $mytext .= $city_array[$i];
					}
					$mytext .= $city1;
					$mytext .= "<p class=txt>Телефон: ".$phone;
					$mytext .= "<p class=txt>Получать SMS-уведомления: ".($sms?"Да":"Нет");
					$mytext .= "<p class=txt>Информация о Вас: ".$text;
					//echo $mytext;
					//echo "<br><br><p class=txt>Данная информация отправлена на Ваш почтовый адрес, указанный при регистрации<br><br>";
					
					$file = fopen($in."mail/top.html", "r");
					while (!feof ($file)) 
					{
						$message .= fgets ($file, 1024);
					}
					fclose($file);
					$message .= "<tr bgcolor=\"#006699\"><td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td>
					<td width=498><p><b><font color=white>Регистрация пользователя</font></b></td>
					<td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td></tr>
					<tr>
					<td width=498 bgcolor=\"#fff8e8\"><p>$info1.$mytext
					<br><br>Клуб Нумизмат - <a href=http://www.numizmatik.ru>www.numizmatik.ru</a><br><br>
					</td>
					</tr>";
					
					$file = fopen($in."mail/bottom.html", "r");
					while (!feof ($file)) 
					{
						$message .= fgets ($file, 1024);
					}
					fclose($file);
					$recipient = $email;
					$subject = "Регистрация в Клубе Нумизмат";
					$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
					mail($recipient, $subject, $message, $headers);
					header("Location: ".$in."auth.php?userlogin=".urlencode($userlogin1)."&userpassword=".urlencode($userpassword1)."&login=1");
				}
	
	*/
	
	/*
	echo "<p class=txt align=center><b><font color=red>Данная информация отправлена на Ваш почтовый адрес, указанный при регистрации!!!</font></b>";
			//далее отправляем письмо пользователю
			$mytext = "<br><br><p class=txt><b>Ваши данные</b>";
			$mytext .= "<p class=txt>ФИО: ".$rows[1];
			$mytext .= "<p class=txt>Логин: ".$rows[2];
			$mytext .= "<p class=txt>Пароль: ".$rows[3];
			$mytext .= "<p class=txt>E-mail: ".$rows[4];
			$mytext .= "<p class=txt>Домашняя страница: ".$rows[5];
			$mytext .= "<p class=txt>Пол: ";
			if ($rows[6]==0) { $mytext .= "Мужчина"; } else { $mytext .= "Женщина";}
			$mytext .= "<p class=txt>Город: ";
			for ($i=0; $i<count($city_array); $i++)
			{
				if ($rows[7]==$i and $i!=0) $mytext .= $city_array[$i];
			}
			$mytext .= $rows[7];
			$mytext .= "<p class=txt>Телефон: ".$rows[8];
			$mytext .= "<p class=txt>Информация о Вас: ".$rows[9];
			
			$file = fopen($in."mail/top.html", "r");
			while (!feof ($file)) 
			{
				$message .= fgets ($file, 1024);
			}
			fclose($file);
			$message .= "<tr bgcolor=\"#006699\"><td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td>
			<td width=498><p><b><font color=white>Востановление пароля</font></b></td>
			<td rowspan=2 width=\"1\" bgcolor=\"#000000\"></td></tr>
			<tr>
			<td width=498 bgcolor=\"#fff8e8\"><p>$info1.$mytext
			<br><br>Клуб Нумизмат - <a href=http://www.numizmatik.ru>www.numizmatik.ru</a><br><br>
			</td>
			</tr>";
			
			$file = fopen($in."mail/bottom.html", "r");
			while (!feof ($file)) 
			{
				$message .= fgets ($file, 1024);
			}
			fclose($file);
			$recipient = $rows["email"];
			$subject = "Регистрация в Клубе Нумизмат";
			$headers = "From: Numizmatik.Ru<administrator@numizmatik.ru>\nContent-type: text/html; charset=\"windows-1251\"";
			InsertMail ($rows["email"], $subject, $message, $headers, "1");
	
	*/
	 //получаем число пользователей на данный момент
  /*  public function countAll(){
        $sql = "SELECT count(*) FROM user";
       // return $this->countAll();
    	return $this->db->getOne($sql);
        $count = $this->countAll();
   var_dump($count);
die();
	  //  $sql = "SELECT count(*) FROM from `user`";
    	//return $this->db->getOne($sql);
	}*/
	//проверяем, что пользователь залогинен
	public function loginUser($email,$userpassword){
	   if ($email &&$userpassword){
	       //получаем данные из базы
	        $select = $this->db->select()
		               ->from('user',array('fio','user', 'userlogin', 'userpassword'))
		               ->where("userlogin='$email' or  email='$email'")
		               ->where("userpassword=?",$userpassword);		               
    	   $userData  = $this->db->fetchRow($select);       
          
        	if ($userData){
        	    
        	    $domain = $_SERVER["HTTP_HOST"];
        	    $domain = '.'.str_replace('www.','', $domain);
        	    
        		setcookie("cookiesfio", $userData['fio'], time() + 86400 * 90, "/", $domain);
        		setcookie("cookiesuserlogin", $userData['userlogin'], time() + 86400 * 90, "/", $domain);
        		setcookie("cookiesuserpassword", $userData['userpassword'], time() + 86400 * 90, "/", $domain);
        		setcookie("cookiesuser", $userData['user'], time() + 86400 * 90, "/", $domain);
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
    	$domain = $_SERVER["HTTP_HOST"];
        $domain = '.'.str_replace('www.','', $domain);  
              	    
    	setcookie("cookiesfio", "", time()-3600, "/", $domain);        		
		setcookie("cookiesuserlogin", "", time()-3600, "/", $domain);
		setcookie("cookiesuserpassword", "", time()-3600, "/", $domain);
		setcookie("cookiesuser", "", time()-3600, "/", $domain);
		unset($_COOKIE['cookiesuserlogin']);
		unset($_COOKIE['cookiesuserpassword']);
		unset($_COOKIE['cookiesuser']);
		
		unset($_SESSION['cookiesuserlogin']);
		unset($_SESSION['cookiesuserpassword']);
		unset($_SESSION['cookiesuser']);
    }
    
}
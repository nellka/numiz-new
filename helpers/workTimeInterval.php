<?
class workTimeInterval
{		
	static function ocenkaWork() {
	    $dayOfWeek = date('w');	   
	     
	    if($dayOfWeek=='0'||$dayOfWeek=='6'){
	       if(date('H')>=10&&date('H')<18) return true;
	    } else {
	        if(date('H')>=11&&date('H')<19) return true;
	    }
	    
	    return false;
	}	

}
?>
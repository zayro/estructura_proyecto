<?php

class Browser 
{
	
	var $name = NULL;
	var $version = NULL;
	var $useragent = NULL;
	var $so;
	var $aol = FALSE;
	var $browser;
	var $ip;

	// -------------------------------------------------------------------------------------------
	// FUNCIÓN CONSTRUCTOR. 
	// -------------------------------------------------------------------------------------------
		
	function Browser(){
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$this->useragent = $agent;
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE DEVUELVE EL SO DEL CLIENTE QUE ACCEDE A LA PÁGINA. 
	// -------------------------------------------------------------------------------------------
	
	function getBrowserOS(){
		$win = eregi("win", $this->useragent);
		$linux = eregi("linux", $this->useragent);
		$mac = eregi("mac", $this->useragent);
		$os2 = eregi("OS/2", $this->useragent);
		$beos = eregi("BeOS", $this->useragent);
		
		if($win){
			$this->so = "Windows";
		} elseif ($linux) {
			$this->so = "Linux"; 
		} elseif ($mac) {
			$this->so = "Macintosh"; 
		} elseif ($os2) {
			$this->so = "OS/2"; 
		} elseif ($beos) {
			$this->so = "BeOS"; 
		} 
		
		return $this->so;
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES OPERA. 
	// -------------------------------------------------------------------------------------------
	
	function isOpera(){
		if (eregi("opera",$this->useragent)){
			$val = stristr($this->useragent, "opera");
			if (eregi("/", $val)){
				$val = explode("/",$val);
				$this->browser = $val[0];
				$val = explode(" ",$val[1]);
				$this->version = $val[0];
			} else {
				$val = explode(" ",stristr($val,"opera"));
				$this->browser = $val[0];
				$this->version = $val[1];
			}
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES FIREFOX. 
	// -------------------------------------------------------------------------------------------
	
	function isFirefox(){
		if(eregi("Firefox", $this->useragent)){
			$this->browser = "Firefox"; 
			$val = stristr($this->useragent, "Firefox");
			$val = explode("/",$val);
			$this->version = $val[1];
			
			return true;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES KONQUEROR. 
	// -------------------------------------------------------------------------------------------
	
	function isKonqueror(){
		if(eregi("Konqueror",$this->useragent)){
			$val = explode(" ",stristr($this->useragent,"Konqueror"));
			$val = explode("/",$val[0]);
			$this->browser = $val[0];
			$this->version = str_replace(")","",$val[1]);
			
			return TRUE;
		} else {
			return FALSE;
		}
		
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES IE VERSIÓN 1.0. 
	// -------------------------------------------------------------------------------------------
	
	function isIEv1(){
		if(eregi("microsoft internet explorer", $this->useragent)){
			$this->browser = "MSIE"; 
			$this->version = "1.0";
			$var = stristr($this->useragent, "/");
			if (ereg("308|425|426|474|0b1", $var)) $this->version = "1.5";
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES IE. 
	// -------------------------------------------------------------------------------------------
	
	function isMSIE(){
		if(eregi("msie", $this->useragent) && !eregi("opera",$this->useragent)){
			$this->browser = "MSIE"; 
			$val = explode(" ",stristr($this->useragent,"msie"));
			$this->browser = $val[0];
			$this->version = $val[1];
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES GALEON. 
	// -------------------------------------------------------------------------------------------
	
	function isGaleon(){
		if(eregi("galeon",$this->useragent)){
			$val = explode(" ",stristr($this->useragent,"galeon"));
			$val = explode("/",$val[0]);
			$this->browser = $val[0];
			$this->version = $val[1];
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES WEB TV. 
	// -------------------------------------------------------------------------------------------
	
	function isWebTV(){
		if(eregi("webtv",$this->useragent)){
			$val = explode("/",stristr($this->useragent,"webtv"));
			$this->browser = $val[0];
			$this->version = $val[1];
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES NETPSOTIVE. 
	// -------------------------------------------------------------------------------------------
	
	function isNetPositive(){
		if(eregi("NetPositive", $this->useragent)){
			$val = explode("/",stristr($this->useragent,"NetPositive"));
			$this->so = "BeOS"; 
			$this->browser = $val[0];
			$this->version = $val[1];
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES IE MOBILE. 
	// -------------------------------------------------------------------------------------------
	
	function isMSPIE(){
		if(eregi("mspie",$this->useragent) || eregi("pocket", $this->useragent)){
			$val = explode(" ",stristr($this->useragent,"mspie"));
			$this->browser = "MSPIE"; 
			$this->so = "WindowsCE"; 
			if (eregi("mspie", $this->useragent)){
				$this->version = $val[1];
			} else {
				$val = explode("/",$this->useragent);
				$this->version = $val[1];
			}
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES BEOS ICAB. 
	// -------------------------------------------------------------------------------------------
	
	function isIcab(){
		if(eregi("icab",$this->useragent)){
			$val = explode(" ",stristr($this->useragent,"icab"));
			$this->browser = $val[0];
			$this->version = $val[1];
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES OMNIWEB. 
	// -------------------------------------------------------------------------------------------
	
	function isOmniWeb(){
		if(eregi("omniweb",$this->useragent)){
			$val = explode("/",stristr($this->useragent,"omniweb"));
			$this->browser = $val[0];
			$this->version = $val[1];
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES PHOENIX. 
	// -------------------------------------------------------------------------------------------
	
	function isPhoenix(){
		if(eregi("Phoenix", $this->useragent)){
			$this->browser = "Phoenix"; 
			$val = explode("/", stristr($this->useragent,"Phoenix/"));
			$this->version = $val[1];
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES THUNDERBIRD. 
	// -------------------------------------------------------------------------------------------
	
	function isFirebird(){
		if(eregi("firebird", $this->useragent)){
			$this->browser = "Firebird"; 
			$val = stristr($this->useragent, "Firebird");
			$val = explode("/",$val);
			$this->version = $val[1];
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES MOZILLA ALPHA - BETA. 
	// -------------------------------------------------------------------------------------------
	
	function isMozAlphaBeta(){
		if(eregi("mozilla",$this->useragent) && eregi("rv:[0-9].[0-9][a-b]",$this->useragent) && !eregi("netscape",$this->useragent)){
			$this->browser = "Mozilla"; 
			$val = explode(" ",stristr($this->useragent,"rv:"));
			eregi("rv:[0-9].[0-9][a-b]",$this->useragent,$val);
			$this->version = str_replace("rv:","",$val[0]);
			
			return TRUE;
		} else {
			return FALSE;
		}
	}

	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES MOZILLA EN VERSIÓN ESTABLE. 
	// -------------------------------------------------------------------------------------------

	function isMozStable(){
		if(eregi("mozilla",$this->useragent) && eregi("rv:[0-9]\.[0-9]",$this->useragent) && !eregi("netscape",$this->useragent)){
			$this->browser = "Mozilla"; 
			$val = explode(" ",stristr($this->useragent,"rv:"));
			eregi("rv:[0-9]\.[0-9]\.[0-9]",$this->useragent,$val);
			$this->version = str_replace("rv:","",$val[0]);
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES LYNX Y AMAYA. 
	// -------------------------------------------------------------------------------------------

	function isLynx(){
		if(eregi("libwww", $this->useragent)){
			if (eregi("amaya", $this->useragent)){
				$val = explode("/",stristr($this->useragent,"amaya"));
				$this->browser = "Amaya"; 
				$val = explode(" ", $val[1]);
				$this->version = $val[0];
			} else {
				$val = explode("/",$this->useragent);
				$this->browser = "Lynx"; 
				$this->version = $val[1];
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES SAFARI. 
	// -------------------------------------------------------------------------------------------
	
	function isSafari(){
		if(eregi("safari", $this->useragent)){
			$this->browser = "Safari"; 
			$this->version = "";
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES NETSCAPE. 
	// -------------------------------------------------------------------------------------------
	
	function isNetscape(){
		if(eregi("netscape",$this->useragent)){
			$val = explode(" ",stristr($this->useragent,"netscape"));
			$val = explode("/",$val[0]);
			$this->browser = $val[0];
			$this->version = $val[1];
			
			return TRUE;
		} elseif(eregi("mozilla",$this->useragent) && !eregi("rv:[0-9]\.[0-9]\.[0-9]",$this->useragent)){
			$val = explode(" ",stristr($this->useragent,"mozilla"));
			$val = explode("/",$val[0]);
			$this->browser = "Netscape"; 
			$this->version = $val[1];
			
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// -------------------------------------------------------------------------------------------
	// FUNCIÓN QUE PREGUNTA SI ES AOL. 
	// -------------------------------------------------------------------------------------------
	
	function isAOL(){
		if (eregi("AOL", $this->useragent)){
			$var = stristr($this->useragent, "AOL");
			$var = explode(" ", $var);
			$this->aol = ereg_replace("[^0-9,.,a-z,A-Z]", "", $var[1]);
			
			return TRUE;
		} else { 
			return FALSE;
		}
	}
	
	function getRealIP(){ if( $_SERVER['HTTP_X_FORWARDED_FOR'] != '' ){$ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] :	( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : "unknown" ); $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']); reset($entries); while (list(, $entry) = each($entries)){$entry = trim($entry); if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) ){$private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/'); $found_ip = preg_replace($private_ip, $ip, $ip_list[1]); if ($ip != $found_ip){$ip = $found_ip;  break;}}}} else	{ $ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] :  "unknown" ); } $this->ip = $ip;}
	
	function getInfo()
	{
		$this->getRealIP();
		$this->getBrowserOS();
		$this->isOpera();
		$this->isFirefox();
		$this->isKonqueror();
		$this->isIEv1();
		$this->isMSIE();
		$this->isGaleon();
		$this->isNetPositive();
		$this->isMSPIE();
		$this->isIcab();
		$this->isOmniWeb();
		$this->isPhoenix();
		$this->isFirebird();
		$this->isLynx();
		$this->isSafari();
		//$this->isMozAlphaBeta();
		//$this->isMozStable();
		//$this->isNetscape();
		$this->isAOL();
		return array('browser' => $this->browser, 
					 'version' => $this->version, 
					 'so' => $this->so, 
					 'AOL' => $this->aol,
					 'ip' => $this->ip
					); 
	}
}//end class
?>
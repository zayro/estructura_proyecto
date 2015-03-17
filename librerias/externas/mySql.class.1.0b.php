<? 
	// ************************************************************************************************************************************************************
	/* ************************************************************************************************************************************************************
	// MySQL Class 1.0b
	// Creada por Pablo E. Fernández Casado
	// Licencia MIT.
	// Visita http://www.islavisual.com
	// ************************************************************************************************************************************************************
	// ************************************************************************************************************************************************************
	// FORMA DE USARLA
	// 		include "../../clases/mySql.class.php" ;
	// 		$mysql = new mySQL;
	// INMEDIATAMENTE DESPUÉS YA SE PUEDEN UTILIZAR LAS INSTRUCCIONES.
	// A CONTINUACIÓN DEJO ALGUNOS EJEMPLOS SENCILLOS:
			$mysql->connect();
			$aux = $mysql->assignToken("SELECT name FROM blog_tags WHERE id =110;");
			echo "COD: ".$aux."<br> DEC: ".$mysql->decodeToken($aux)."<br>";
			echo print_r($mysql->getInfo());
			echo $mysql->insertEntryLog("Accede a");
			echo print_r($mysql->time2Array(date("d-m-Y"), "d-m-Y"));
			echo $mysql->mkTimeFormat(date("d-m-Y H:i:s"), "d-m-Y H:i:s");
			echo $mysql->toDateFormat(date("Y-m-d"),'', "d-m-Y");
			echo $mysql->toDateFormat('2012-10-31',"Y-m-d", "d-m-Y");
			echo $mysql->isDate("2012/33", "Y/d")."<br>";
			echo $mysql->isDate("oct-01", "m-d")."<br>";
			echo $mysql->isDate("oct", "m")."<br>";
			echo $mysql->isDate("31", "d")."<br>";
			echo $mysql->isDate("31/10/2012", '')."<br>";
			echo print_r($mysql->elapsedTime("2012/11/03",'',"05/10/2012",''))."<br>";
			echo print_r($mysql->elapsedTime("2012/10/03 22:10:45",'',"05/10/2012 22:56:12",''))."<br>";
			echo print_r($mysql->elapsedTime("2012/10/03 12:10:45",'',"05/10/2012 22:56:12",''))."<br>";
			echo $mysql->isNumber("023")."<br>";
			echo $mysql->isNumber("0.2")."<br>";
			echo $mysql->isNumber("0,5")."<br>";
			echo $mysql->isNumber("5-4")."<br>";
			echo $mysql->isNumber("1e4")."<br>";
			echo "1. ".$mysql->isString("10/10/2012")."<br>";
			echo "2. ".$mysql->isString("0.2")."<br>";
			echo "3. ".$mysql->isString("str")."<br>";
			echo "4. ".$mysql->isString("2012/01/01")."<br>";
			echo "5. ".$mysql->isString("1e5")."<br>";
			echo "6. ".$mysql->isString("oct-31")."<br>";
			echo "7. ".$mysql->isString(array("0", "1"))."<br>";
			$aux = $mysql->getValue("SELECT name FROM blog_tags WHERE id =110;");
			list($id, $name) = $mysql->getValues("SELECT id, name FROM blog_tags WHERE id IN (SELECT id FROM blog_tags where id >= 112) AND id =112;");
			echo "id = ".$id."<br>name = ".$name. "<br>aux = ".$aux."<br>";
			
			$result = $mysql->query("SELECT * FROM blog_tags WHERE 1;");
			while($row = $mysql->fetchArray()){
				echo $mysql->utf8($row['id'])." ".$mysql->utf8($row['name'])." "."<br>";
			}
			
			$tagsArray = explode(",", $tags);
			foreach($tagsArray as $tag){
				if(trim($tag) != "" && strpos($str, "INSERT INTO blog_tags (name) VALUES('".$tag."');") === false) $str .= "INSERT INTO blog_tags (name) VALUES('".$tag."');\n";
			}
			$mysql->query($str);
			
			$mysql->export("isv_bbdd.txt");
			$mysql->export("isv_bbdd.txt", true);
			$mysql->export("isv_bbdd.txt", false, 'blog_log,blog_tags', 'bz2');
	// ************************************************************************************************************************************************************
	// ************************************************************************************************************************************************************/
	
	// Clase Mysql
	include "getInfo.class.php";
	
	//error_reporting(E_NONE);
	
	class mySQL{
		public $resource;
		private $total_queries = 0;
		
		const _DATABASE_NAME_DEVELOPMENT= '';
		const _USER_DEVELOPMENT 		= '';
		const _PASS_DEVELOPMENT 		= '';
		
		const _DATABASE_NAME_PRODUCTION	= '';
		const _USER_PRODUCTION 			= '';
		const _PASS_PRODUCTION 			= '';
		
		const 		_TOKEN_KEY					= 'date("Y-m-d H:i:s", $_SERVER["REQUEST_TIME"]);';
		var			$_ENCODED_TOKEN				= "";			// Contiene el útimo token generado.
		
		var 		$_IGNORE_ERRORS				= '1062';		// Lista de Nº de error de MySQL separados por coma que se manejarán de manera especial. Para manejar los errores consultar la página http://dev.mysql.com/doc/refman/5.0/es/error-handling.html
		var 		$_WARNING_COLOR 			= 'orange';		// Color de los errores de tipo WARNING
		var 		$_ERROR_COLOR 				= 'red';		// Color de los errores de tipo ERROR
		var 		$_SHOW_WARNING_ERROR 		= true;			// Si esta establecida a TRUE se muestran los mensajes WARNING.
		var			$_SHOW_IGNORED_ERRORS		= false;		// Si está a FALSE No se mostrarán los mensajes ignorados. Si está establecido a TRUE se mostrarán por pantalla al igual que los demás.
		var			$_SHOW_CONTROL_MESSAGES		= true;			// Si esta establecida a TRUE se muestran los mensajes de ERROR.
		var 		$_STOP_WARNING_ERROR	 	= false;		// Para la ejecución de la página si está establecida a TRUE.
		
		const 		_SEPARADOR_SQL 			= ";\n";			// Separador para la ejecución de múltiples sentencias. Se separan por este valor y luego se ejecutan una a una.
		var 		$_FORMAT_DATETIME_DB	= "Y-m-d H:i:s";	// Formato de fecha que tiene configurada la BBDD. Por defecto configurada a FORMATO AMERICANO 1970-01-01 01:00:00.
		var 		$_FORMAT_DATE_DB 		= "Y-m-d";			// Formato de fecha que tiene configurada la BBDD. Por defecto configurada a FORMATO AMERICANO 1970-01-01.
		var 		$_FORMAT_DATETIME_FRMWRK= "d-m-Y H:i:s";	// Formato de fecha que se quiere usar dentro de la clase. Por defecto configurada a FORMATO 31-12-1970 00:00:00.
		var 		$_FORMAT_DATE_FRMWRK	= "d-m-Y";			// Formato de fecha que se quiere usar dentro de la clase. Por defecto configurada a FORMATO 31-12-1970.
		
		public 		$_EMPTY_FIELD_BY_DEFAULT = "";				// Si la query no da ningún resultado se devolverá el valor establecido por esta variable. Normamente será NULL o "".
		public 		$_UTF8_ENCODE			= true;				// Los resultados extraidos de MySQL se convertirán por defecto a UTF-8 si está establecida a true. Si no no hace nada. 
		
		var 		$selected_rows 	= 0;						// Número de filas seleccionadas para un select dado
		var 		$affected_rows 	= 0;						// Número de filas afectadas para un UPDATE, INSERT o DELETE
		var 		$last_insert_id = 0;						// Número del último id insertado.
		var 		$last_query 	= "";						// La última query que se ejecutó.
		var			$last_error_id	= 0;						// El último código de error que dió MySQL. Va asociado a $last_error_msg.
		var			$last_error_msg	= "";						// El último mensaje de error que dió MySQL. Va asociado a $last_error_id.
		
		protected 	$execStartTime 	= 0;						// Guarda el inicio de la ejecución de la query o queries de MySQL.
		protected 	$execEndTime   	= 0;						// Guarda el final de la ejecución de la query o queries de MySQL.
		var       	$completedIn   	= 0;						// Guarda el tiempo transcurrido en la ejecución de la query o queries de MySQL.
		
		// -------------------------------
		// VARIABLES DEL LOG DE EVENTOS
		// -------------------------------
		var			$_ENABLED_LOG			= true;				// Si está TRUE se guarda en la base de datos una entrada de log que cada página a la que se acceda. Si es FALSE, no hace nada.
		private		$_LOG_TABLE_CREATE_AUTO	= true;				// Indica si hay que crear la tabla automáticamente en la base de datos si no está creada en el momento de la llamada o ejecución.
		private		$_LOG_TABLE_NAME		= "blog_log";		// Es el nombre de la tabla que tiene la configuración de la tabla de LOG's.
		var			$_SIZE_LOG_IN_DAYS		= 30;				// Tamaño del LOG en días. Por defecto es 30 días. Si se establece a CERO se entiende que no se quiere eliminar ninguna entrada del log.
		var			$_SAVE_QUERIES_IN_LOG	= false;			// Si está establecido a TRUE, se guardan todas las consultas que envíen a MySQL automáticamente. Si es FALSE, sólo guarda los eventos que se soliciten directamente a través de la función insertEntryLog(...)
		private		$_LOG_TABLE_DEF 		= "DROP TABLE IF EXISTS `<table_log>`; CREATE TABLE IF NOT EXISTS `<table_log>` ( `Id` bigint(20) NOT NULL auto_increment, `fecha` datetime NOT NULL default '0000-00-00 00:00:00', `evento` longtext collate utf8_bin NOT NULL, `pagina` varchar(255) collate utf8_bin default NULL, `ip` varchar(15) collate utf8_bin default NULL, `so` varchar(50) collate utf8_bin default NULL, `browser` varchar(255) collate utf8_bin default NULL, `host` varchar(255) collate utf8_bin default NULL, PRIMARY KEY  (`Id`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=15 ;";
		
		
				
		var			$_NAMES_MONTH = array('JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER',
						 'ENERO', 'FEBBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE',
						 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC', 
						 'ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC', 
					 );
					 
		// ************************************************************************************************************************************************************
		// ************************************************************************************************************************************************************
		// FUNCIONES PARA EL CONTROL DE TRANSFERENCIA DE DATOS.
		// SE USARÁ PARA CONTROLAR QUE NO SE ENVÍE A LA BBDD 2 VECES LA MISMA SENTENCIA SQL.
		// TAMBIÉN SE PUEDE USAR PARA EL GESTIONAR LA SEGURIDAD DE LAS TRANSFERENCIAS ENTRE EL SERVIDOR Y MYSQL.
  		// ************************************************************************************************************************************************************
		// ************************************************************************************************************************************************************
		
		// ------------------------------------------------------------------------------------------------------------
		// FUNCIÓN PARA CODIFICAR O CREAR TOKENS. SE BASA EL LA CONSTAMTE _TOKEN_KEY PARA CODIFICAR LA CADENA ENVIADA.
		// ------------------------------------------------------------------------------------------------------------
		
		function encodeToken($string) {
			eval("\$auxToken = ".mySQL::_TOKEN_KEY);
			eval("\$token_key = '".$auxToken."';");
			
			$token = '';
			for($i=0; $i<strlen($string); $i++) {
				$char = substr($string, $i, 1);
				$keychar = substr($token_key, ($i % strlen($token_key))-1, 1);
				$char = chr(ord($char)+ord($keychar));
				$token.=$char;
			}
			$this->_ENCODED_TOKEN = $token;
			return base64_encode($token);
		}
		
		// ------------------------------------------------------------------------------------------------------------
		// FUNCIÓN PARA DECODIFICAR TOKENS.
		// ------------------------------------------------------------------------------------------------------------
		
		function decodeToken($token) {
			eval("\$auxToken = ".mySQL::_TOKEN_KEY);
			eval("\$token_key = '".$auxToken."';");
			
			$token = base64_decode($token);
			$string = '';
			for($i=0; $i<strlen($token); $i++) {
				$char = substr($token, $i, 1);
				$keychar = substr($token_key, ($i % strlen($token_key))-1, 1);
				$char = chr(ord($char)-ord($keychar));
				$string.=$char;
			}
			return $string;
		}
		
		// ------------------------------------------------------------------------------------------------------------
		// FUNCIÓN QUE COMPARA UN TOKEN ENVIADO POR $token CON EL TOKEN CREADO A PARTIR DE $string. 
		// SE BASA EL LA CONSTAMTE _TOKEN_KEY PARA CODIFICAR LA CADENA ENVIADA.
		// SI $token ES VACÍO TOMA COMO TOKEN PARA COMPARAR EL DEVUELTO POR LA VARIABLE _ENCODED_TOKEN.
		// ------------------------------------------------------------------------------------------------------------
		// Devuelve TRUE si son iguales. En cualquier otro caso devuelve FALSE.
		// ------------------------------------------------------------------------------------------------------------
		
		function checkToken($string, $token=""){
			$current_token = $token;
			if($current_token == "") $current_token = $this->_ENCODED_TOKEN;
			
			$aux = $this->createToken($string);
			if($aux == $current_token) return true;
			
			return false;
		}
		
		// ------------------------------------------------------------------------------------------------------------
		// FUNCIÓN QUE COMPARA 2 TOKENS 
		// SI $token2 ES VACÍO TOMA COMO TOKEN PARA COMPARAR EL DEVUELTO POR LA VARIABLE _ENCODED_TOKEN.
		// ------------------------------------------------------------------------------------------------------------
		// Devuelve TRUE si son iguales. En cualquier otro caso devuelve FALSE.
		// ------------------------------------------------------------------------------------------------------------
		
		function compareTokens($token1, $token2=""){
			if($token2 == "") $token2 = $this->_ENCODED_TOKEN;
			
			if($token1 == $token2) return true;
			
			return false;
		}
		
		// ------------------------------------------------------------------------------------------------------------
		// FUNCIÓN COMPRUEBA QUE EL TOKEN INTRODUCIDO NO ESTÁ EN LA TABLA REFERENCIADA POR _LOG_TABLE_NAME DE LA BBDD.
		// ------------------------------------------------------------------------------------------------------------
		// Devuelve TRUE si son iguales. En cualquier otro caso devuelve FALSE.
		// ------------------------------------------------------------------------------------------------------------
		
		function existsToken($token=""){
			$current_token = $token;
			if($current_token == "") $current_token = $this->_ENCODED_TOKEN;
			
			$result = mysql_query("SELECT id FROM ".$this->_LOG_TABLE_NAME." WHERE token = '".$current_token."'");
			$this->last_query = "SELECT id FROM ".$this->_LOG_TABLE_NAME." WHERE token = '".$current_token."'";
			$row = mysql_fetch_row($result);
			$row = $row[0];
			
			if($row != $this->_EMPTY_FIELD_BY_DEFAULT){
				return true;
			}
			
			return false;
		}
		
		// ************************************************************************************************************************************************************
		// ************************************************************************************************************************************************************
		// FUNCIONES PARA LA GESTIÓN DEL LOG
		// SE UTILIZA PARA EL CONTROL DE EVENTOS EN EL SISTEMA Y GUARDAR EL HISTORIAL DE CONSULTAS QUE SE REALIZARON EN LOS
		// ÚLTIMOS '_SIZE_LOG_IN_DAYS' DÍAS, SIEMPRE Y CUANDO _SAVE_QUERIES_IN_LOG ESTÉ true. POR DEFECTO SON 30 DÍAS.
  		// ************************************************************************************************************************************************************
		// ************************************************************************************************************************************************************
		
		// -----------------------------------------------------------------------------------------------------------------
		// FUNCIÓN QUE DEVUELVE EL BROWSER, VERSION, IP Y SO DEL CLIENTE QUE ACCEDE A LA PÁGINA. 
		// -----------------------------------------------------------------------------------------------------------------
		// Necesita de la clase "getInfo" para alimentarse. Por ello si se usa es necesario incluir la línea de código:
		// EJ.: include "getInfo.class.php";
		// -----------------------------------------------------------------------------------------------------------------
		
		public function getInfo(){
           	$browser = new Browser;
			$aux = $browser->getInfo();
			return array(
                'browser'      	=> $aux['browser'],
                'version'  		=> $aux['version'],
                'so'  			=> $aux['so'],
                'ip'    		=> $aux['ip'],
				'page' 			=> $_SERVER['REQUEST_URI'],
				'host'			=> $_SERVER['SERVER_SOFTWARE'].", ".$_SERVER['SERVER_PROTOCOL'].", ".$_SERVER['SERVER_NAME'],
				'added_info'	=> ''
            );
        }
		
		// -----------------------------------------------------------------------------------------------------------------
		// FUNCIÓN QUE CREA LA TABLA DE LOG's. 
		// -----------------------------------------------------------------------------------------------------------------
		
		public function createTableLog(){
			$query = $this->_LOG_TABLE_DEF;
			$query = ereg_replace('<table_log>', $this->_LOG_TABLE_NAME, $query);
			$result = mysql_query($query);
			$this->last_query = $query;
			
			return $result;
		}
		
		// -----------------------------------------------------------------------------------------------------------------
		// FUNCIÓN QUE INSERTA EVENTOS EN LA TABLA DE LOG's. 
		// -----------------------------------------------------------------------------------------------------------------
		
		public function insertEntryLog($event){
			if($this->_ENABLED_LOG){
				$result = mysql_query("SHOW TABLES LIKE '".$this->_LOG_TABLE_NAME."';");
				$this->last_query = "SHOW TABLES LIKE '".$this->_LOG_TABLE_NAME."';";
				$row = mysql_fetch_row($result);
				$row = $row[0];

				if($row == $this->_EMPTY_FIELD_BY_DEFAULT){
					$resultCreate = $this->createTableLog();
				}
				
				if($this->_SIZE_LOG_IN_DAYS != 0){
					$result = mysql_query("DELETE FROM ".$this->_LOG_TABLE_NAME." WHERE fecha < DATE_SUB(NOW(),INTERVAL ".$this->_SIZE_LOG_IN_DAYS." DAY)");
					$this->last_query = "DELETE FROM ".$this->_LOG_TABLE_NAME." WHERE fecha < DATE_SUB(NOW(),INTERVAL ".$this->_SIZE_LOG_IN_DAYS." DAY)";
				}

				$aux = $this->getInfo();
				$token = $this->encodeToken($event);
				if(!$this->existsToken($token)){
					$result = mysql_query("INSERT INTO ".$this->_LOG_TABLE_NAME." (fecha, browser, so, ip, pagina, added_info, token, evento) VALUES ('".date("Y-m-d H:i:s", $_SERVER["REQUEST_TIME"])."', '".$aux['browser'].' '.$aux['version']."', '".$aux['so']."', '".$aux['ip']."', '".$aux['page']."', '".$aux['added_info']."', '".$token."', '$event')");
					$this->last_query = "INSERT INTO ".$this->_LOG_TABLE_NAME." (fecha, browser, so, ip, pagina, added_info, token, evento) VALUES ('".date("Y-m-d H:i:s", $_SERVER["REQUEST_TIME"])."', '".$aux['browser'].' '.$aux['version']."', '".$aux['so']."', '".$aux['ip']."', '".$aux['page']."', '".$aux['added_info']."', '".$token."', '$event')";
				}
			}
		}
       
		// -------------------------------------------------------------------------------------------
		// FUNCIÓN PARA TENER ACCESO DESDE FUERA DE LA CLASE A DETERMINADAS VARIABLES DE USO PRIVADO. 
		// -------------------------------------------------------------------------------------------
		// PARA PERIMITIR QUE SE LEA UNA VARIABLE DESDE FUERA DE LA CLASE DEBE DE ESTAR EL LA LISTA
		// $alowed_vars. SI NO ESTÁ, NO SE TENDRÁ ACCESO Y SACARÁ UN MENSAJE DE ERROR.
		// -------------------------------------------------------------------------------------------
		
  		public function __get($name){
			$alowed_vars = "completedIn, total_queries, last_insert_id, affected_rows, selected_rows";
			if(strpos($alowed_vars, $name) !== false){
			 	return $this->$name;
			} else {
				eval("\$exists_reference = isset(\$this->".$name.");");
				if($exists_reference){
					if($this->_ENABLED_LOG) $this->insertEntryLog('ERROR: No se permite el acceso a la variable o función '.$name);
					echo '<b style="color:'.$this->_ERROR_COLOR.'">ERROR FATAL</b>:<br>No se permite el acceso a la variable o función '.$name.'.<br />'."\n";
				} else {
					if($this->_ENABLED_LOG) $this->insertEntryLog('ERROR: La variable o función '.$name.' NO EXISTE.');
					echo '<b style="color:'.$this->_ERROR_COLOR.'">ERROR FATAL</b>:<br>La variable o función '.$name.' NO EXISTE.<br />'."\n";
				}
			} 
		}
		
		// -------------------------------------------------------------------------------------
		// FUNCIÓN QUE TOMA LA HORA ACTUAL DEL SISTEMA Y LA DEVUELVE EN MICROSEGUNDOS.
		// -------------------------------------------------------------------------------------
  		
		private function uTime (){
			list ($msec, $sec) = explode(' ', microtime());
			$microtime = (float)$msec + (float)$sec;
			return $microtime;
		}
		
		// -------------------------------------------------------------------------------------
		// FUNCIÓN QUE DETECTA LA CODIFICACIÓN DEL TEXTO PARA DESPUÉS SER TRATADA
		// -------------------------------------------------------------------------------------
		
		private function detectCodeText($t){
			$c = 0;
			$ascii = true;
			for ($i = 0;$i<strlen($t);$i++) {
				$byte = ord($t[$i]);
				if ($c>0) {
					if (($byte>>6) != 0x2) {
						return ISO_8859_1;
					} else {
						$c--;
					}
				} elseif ($byte&0x80) {
					$ascii = false;
					if (($byte>>5) == 0x6) {
						$c = 1;
					} elseif (($byte>>4) == 0xE) {
						$c = 2;
					} elseif (($byte>>3) == 0x1E) {
						$c = 3;
					} else {
						return ISO_8859_1;
					}
				}
			}
			return ($ascii) ? ASCII : UTF_8;
		}
		
		// -------------------------------------------------------------------------------------
		// FUNCIÓN CONVERTIR UN TEXTO EN FORMATO LEGIBLE.
		// -------------------------------------------------------------------------------------
		
		public function utf8($t){
			return ($this->detectCodeText($t)==ISO_8859_1) ? utf8_encode($t) : utf8_decode($t);
		}
		
		// -----------------------------------------------------------------------------------------------
		// CONVIERTE UNA CADENA DE TIPO FECHA, DEL FORMATO ENVIADO A UN ARRAY.
		// LOS PARÁMETROS DE $format SON LOS MISMOS QUE PARA LA IUNSTRUCCION DATEDE PHP.
		// -----------------------------------------------------------------------------------------------
		
		public function time2Array($date, $format) {
			$masks = array(
				'd' => '(?P<d>[0-9]{1,2})',
				'm' => '(?P<m>[0-9]{1,2})',
				'Y' => '(?P<Y>[0-9]{2,4})',
				'H' => '(?P<H>[0-9]{1,2})',
				'i' => '(?P<M>[0-9]{1,2})',
				's' => '(?P<S>[0-9]{1,2})',
			);
			$rexep = "#".strtr(preg_quote($format), $masks)."#";
			if(!preg_match($rexep, $date, $out)) return false;
			
			$ret = array(
				"tm_sec"  => (int) $out['S'],
				"tm_min"  => (int) $out['M'],
				"tm_hour" => (int) $out['H'],
				"tm_mday" => (int) $out['d'],
				"tm_mon"  => $out['m']?$out['m']-1:0,
				"tm_year" => $out['Y'] > 1900 ? $out['Y']-1900 : 0,
			);
			
			return $ret;
		} 
		
		// -----------------------------------------------------------------------------------------------
		// CONVIERTE UNA CADENA DE TIPO FECHA, DEL FORMATO ENVIADO A FORMATO UNIX.
		// LOS PARÁMETROS DE $format SON LOS MISMOS QUE PARA LA IUNSTRUCCION DATEDE PHP.
		// -----------------------------------------------------------------------------------------------
		// Los FORMATOS para manejo de las fechas son como la intrucción date() de PHP.
		// Los valores de cadena SÓLO NÚMEROS o SÓLO LETRAS causan ERROR por no ser considerados fechas.
		// Hay que tener cuidado ya que tiene comportamientos creados a conciencia. Por ejemplo:
		// Si la cadena es "2012/33" siendo 2012 el año y 33 el día, nos devuelve el 02-02-2012.
		// Si la cadena es "oct-01" o "01-oct" siendo oct el mes (Octubre) y 01 el día devuelve 01-10-2012.
		// Si la cadena es "2012-oct o "oct-2012" siendo 2012 el año y oct el mes (Octubre), devuelve 01-10-2012.
		
		public function mkTimeFormat($value, $format=""){
			$f = $format;
			if($f == "") $f = $this->_FORMAT_DATETIME_FRMWRK;
			
			for ($x = 0; $x < count($this->_NAMES_MONTH); $x++){
				$value = str_ireplace($this->_NAMES_MONTH[$x], ($x % 12)+1, $value);
			}
			
			$value = ereg_replace("/", "-", $value);
			$f = ereg_replace("/", "-", $f);
			@extract($this->time2Array($value,$f));
			
			if($tm_year == 0 && $tm_mon == 0 && $tm_mday == 0 && $tm_hour == 0 && $tm_min == 0 && $tm_sec == 0 && $format==""){
				$f = ereg_replace("/", "-", $this->_FORMAT_DATE_FRMWRK);
				@extract($this->time2Array($value,$f));
			}
			
			if($tm_year == "" && $tm_mday != "" && $tm_mon != "") $tm_year = date("Y")-1900;
			if($tm_mday == "") $tm_mday = "01";
			
			$mktime = mktime(
				intval($tm_hour),
				intval($tm_min),
				intval($tm_sec),
				intval($tm_mon)+1,
				intval($tm_mday),
				intval($tm_year+1900)
			);
			
			return $mktime;
		}
		
		// ------------------------------------------------------------------------------------------------------------------------------
		// CONVIERTE UNA CADENA DE TIPO FECHA, DEL FORMATO ENVIADO A FORMATO ENVIADO POR $format.
		// LOS PARÁMETROS DE $format Y $format_source SON LOS MISMOS QUE PARA LA IUNSTRUCCION DATEDE PHP.
		// SI $format_source = "" SE TOMA EL FORMATO DE FECHA DE LA CLASE, O BIEN DE _FORMAT_DATETIME_FRMWRK, O DE _FORMAT_DATE_FRMWRK.
		// SI $format = "" SE TOMA EL FORMATO DE FECHA DE LA CLASE DE _FORMAT_DATETIME_DB.
		// ------------------------------------------------------------------------------------------------------------------------------
		// Los FORMATOS para manejo de las fechas son como la intrucción date() de PHP.
		// Los valores de cadena SÓLO NÚMEROS o SÓLO LETRAS causan ERROR por no ser considerados fechas.
		// Hay que tener cuidado ya que tiene comportamientos creados a conciencia. Por ejemplo:
		// Si la cadena es "2012/33" siendo 2012 el año y 33 el día, nos devuelve el 02-02-2012.
		// Si la cadena es "oct-01" o "01-oct" siendo oct el mes (Octubre) y 01 el día devuelve 01-10-2012.
		// Si la cadena es "2012-oct o "oct-2012" siendo 2012 el año y oct el mes (Octubre), devuelve 01-10-2012.
		// EJ: $mysql->toDateFormat("2012-oct-10","Y-m-d", "d-m-Y");
		// ------------------------------------------------------------------------------------------------------------------------------
		
		public function toDateFormat($value, $format_source="", $format=""){
			if($this->isNumber(substr($value, 0,4))) $mktime = strtotime($value);
			
			if($mktime == 0){
				$f = $format_source;
				if($f == "") $f = $this->_FORMAT_DATETIME_FRMWRK;
				$mktime = $this->mkTimeFormat($value, $f);
						
				if($mktime==0 && $format == ""){
					$f = $this->_FORMAT_DATE_FRMWRK;
					$mktime = $this->mkTimeFormat($value, $f);
				}
			}
			
			$f=$format;
			if($f == "") $f = $this->_FORMAT_DATETIME_DB;
			return date($format, $mktime);
		}
		
		// -----------------------------------------------------------------------------------------------
		// COMPRUEBA SI LA CADENA INTRODUCIDA ES UN TIPO FECHA. SE LE DEBE PASAR EL FORMATO PARA COMPARAR.
		// LOS PARÁMETROS DE $format SON LOS MISMOS QUE PARA LA IUNSTRUCCION DATEDE PHP.
		// -----------------------------------------------------------------------------------------------
		// EJ: $mysql->isDate("2012/33", "Y/d") 	Devolvería 02-02-2012 y por eso, devuelve true
		// EJ: $mysql->isDate("oct-01", "m-d") 		Devolvería 01-10-2012 y por eso, devuelve true
		// EJ: $mysql->isDate("oct", "m")			Devolvería error y por eso, devuelve false
		// EJ: $mysql->isDate("31", "d")			Devolvería error y por eso, devuelve false
		// EJ: $mysql->isDate("31/10/2012", '')		Devolvería 31-10-2012 y por eso, devuelve true
		
		public function isDate($value, $format=""){
			if($this->isNumber(substr($value, 0,4))) $mktime = strtotime($value);

			if($mktime == 0){
				$f = $format;
				if($f == "") $f = $this->_FORMAT_DATETIME_FRMWRK;
				
				if ($this->isNumber($value)) return false;
				
				for ($x = 0; $x < count($this->_NAMES_MONTH); $x++){
					if(str_ireplace($this->_NAMES_MONTH[$x], '', $value) == "") return false;
				}
				
				$mktime = $this->mkTimeFormat($value, $f);
				if($mktime == 0 && $format == ""){
					$f = $this->_FORMAT_DATE_FRMWRK;
					$mktime = $this->mkTimeFormat($value, $f);
					if($mktime == 0) return false;
				} 
			}
			return true; //date("d-m-Y H:i:s", $mktime);
		}
		
		// -----------------------------------------------------------------------------------------------
		// COMPRUEBA SI EL VALOR ENVIADO POR $value ES UN NÚMERO
		// -----------------------------------------------------------------------------------------------
		
		public function isNumber($value){
			if (is_numeric($value)) return true;
			return false;
		}
		
		// -----------------------------------------------------------------------------------------------
		// COMPRUEBA SI EL VALOR ENVIADO POR $value ES UNA CADENA
		// -----------------------------------------------------------------------------------------------
		
		public function isString($value){
			if ($this->isNumber($value) || $this->isDate($value) || is_array($value) || is_object($value)) return false;
			return true;
		}
		
		// ------------------------------------------------------------------------------------------------------------------
		// FUNCIÓN QUE RESTA 2 FECHAS Y NOS DEVUELVE EL NÚMERO DE DIAS, HORAS, MINUTOS Y SEGUNDOS QUE HAN PASADO ENTRE ELLAS 
		// ------------------------------------------------------------------------------------------------------------------
		// Devuelve un ARRAY con el formato array("d" => $d, "h" => $h, "m" => $m, "s" => $s) en dónde cada inicial de la 
		// clave del array indica la medida de tiempo
		// ------------------------------------------------------------------------------------------------------------------
		
		function elapsedTime($dInit, $dEnd, $dInit_format="", $dEnd_format=""){
			$f1 = $dInit_format;
			$f2 = $dEnd_format;
			if($f1 == "") $f1 = $this->_FORMAT_DATETIME_FRMWRK;
			if($f2 == "") $f2 = $this->_FORMAT_DATETIME_FRMWRK;
			
			// Probamos si está en formato legible por PHP
			if($this->isNumber(substr($dInit, 0,4))) $dInitAux = strtotime($dInit);
			// Si no, probamos con nuestra función y el formato pasado
			if($dInitAux == 0) $dInitAux = $this->mkTimeFormat($dInit, $f1);
			// Si no, probamos con nuestra función y el formato establecido por _FORMAT_DATE_FRMWRK
			if($dInitAux == 0 && $dInit_format == ""){
				$f1 = $this->_FORMAT_DATE_FRMWRK;
				$dInitAux = $this->mkTimeFormat($dInit, $f1);
			}
			
			// Probamos si está en formato legible por PHP
			if($this->isNumber(substr($dEnd, 0,4))) $dEndAux = strtotime($dEnd);
			// Si no, probamos con nuestra función y el formato pasado
			if($dEndAux == 0) $dEndAux  = $this->mkTimeFormat($dEnd,  $f2);
			// Si no, probamos con nuestra función y el formato establecido por _FORMAT_DATE_FRMWRK
			if($dEndAux == 0 && $dEnd_format == ""){
				$f2 = $this->_FORMAT_DATE_FRMWRK;
				$dEndAux = $this->mkTimeFormat($dEnd, $f2);
			}
			// Asignamos los auxiliares a los actuales ya convertidos
			$dInit = $dInitAux;
			$dEnd = $dEndAux;
			
			// Si la fecha inicial es mayor que la final, cambiamos.
			if($dInit > $dEnd){
				$aux = $dInit;
				$dInit = $dEnd;
				$dEnd = $aux;
			}
			echo date("d-m-Y", $dInit)." ".date("d-m-Y", $dEnd)."<br />";
			// Hacemos los cálculos
			$d=intval(($dEnd-$dInit)/86400);
			$h=intval((($dEnd-$dInit) - ($d*86400))/3600);
			$m=intval((($dEnd-$dInit) - ($d*86400)-($h*3600))/60);
			$s=intval((($dEnd-$dInit) - ($d*86400)-($h*3600)-($m*60)));
	
			return array("d" => $d, "h" => $h, "m" => $m, "s" => $s);
		}
		
		// -----------------------------------------------------------------------------------------------
		// REALIZA LA CONEXIÓN A LA BASE DE DATOS
		// -----------------------------------------------------------------------------------------------
		
		public function connect(){
			if(!isset($this->resource)){
				if($_SERVER['HTTP_HOST'] == "localhost"){
					$this->resource=(mysql_connect($_SERVER['HTTP_HOST'], mySQL::_USER_DEVELOPMENT, mySQL::_PASS_DEVELOPMENT)) or die(mysql_error());
					mysql_select_db(mySQL::_DATABASE_NAME_DEVELOPMENT, $this->resource) or die(mysql_error());
				} else {
					$this->resource=(mysql_connect($_SERVER['HTTP_HOST'], mySQL::_USER_PRODUCTION, mySQL::_PASS_PRODUCTION)) or die(mysql_error());
					mysql_select_db(mySQL::_DATABASE_NAME_PRODUCTION, $this->resource) or die(mysql_error());
				}
			}
		}
		
		// ------------------------------------------------------------------------------------------------------------------------------------------
		// REALIZA LA CONSULTA A LA BASE DE DATOS ENVIADA POR $consulta.
		// ------------------------------------------------------------------------------------------------------------------------------------------
		// Si _SHOW_CONTROL_MESSAGES está establecido a true, se muestran los mensajes de tipo ERROR en pantalla. Sólo para depuración de errores.
		// Si _SHOW_WARNING_ERROR está establecido a true, se muestran los mensajes de tipo WARNING en pantalla. Sólo para depuración de errores.
		// Si _STOP_WARNING_ERROR está establecido a true, se parará la ejecución como si un ERROR se tratase.  Sólo para depuración de errores.
		// ------------------------------------------------------------------------------------------------------------------------------------------
		
		public function query($query){
			$this->total_queries++;
			$this->selected_rows = 0;
			$this->affected_rows = 0;
			$this->execStartTime = $this->uTime();
			if (preg_match( '/^\s*(select) /i', $query) || preg_match( '/^\s*(show) /i', $query)){
				$this->resource = mysql_query($query);
				$result = $this->resource;
				$this->last_query = $query;
				if(!$this->resource) $this->showError();
				$this->selected_rows = $this->num_rows();
				$this->execEndTime = $this->uTime(); 
				$this->completedIn = round($this->execEndTime - $this->execStartTime, 5) ;
				
				if($this->_ENABLED_LOG) $this->insertEntryLog($this->utf8($query.".\r\n\r\nEjecución de la instrucción realizada con éxito "));
				
				return $result;
			} else {
				$query .= mySQL::_SEPARADOR_SQL;
				$query_array = explode(mySQL::_SEPARADOR_SQL, $query);
				foreach ($query_array as $sentence){
					if (trim($sentence) != ""){
						if($this->_UTF8_ENCODE){
							$this->resource = mysql_query($this->utf8($sentence));
						} else {
							$this->resource = mysql_query($sentence);
						}
						$result = $this->resource;
						$this->last_query = $sentence;
						if (!$this->resource) {
							$this->showError();
						} else {
							if (preg_match( '/^\s*(insert) /i', $sentence)){ $this->affected_rows += 1; $this->last_insert_id = mysql_insert_id(); }
							else $this->affected_rows += $this->affected_rows();
							$this->execEndTime = $this->uTime(); 
							$this->completedIn = round($this->execEndTime - $this->execStartTime, 5) ;
							
							if($this->_ENABLED_LOG) $this->insertEntryLog($this->utf8($sentence.".\r\n\r\nEjecución de la instrucción realizada con éxito "));
							
						} // Fin if (!$this->resource)
					} // Fin if (trim($sentence) != "")
				} // Fin foreach
			} 
			
			return $this->resource;
		}
		
		// -----------------------------------------------------------------------------------------------------------------
		// FUNCIÓN QUE RECUPERA EL NÚMERO DE TUPLAS O FILAS SELECCIONADAS. 
		// -----------------------------------------------------------------------------------------------------------------
		
		private function num_rows(){
			if($this->resource){
				$n = mysql_num_rows($this->resource);
				if (mysql_errno() != 0) $this->showError();
				if ($n == 0 || $n === false || $n == "" || $n == NULL) return 0;

				return $n;
			}
		}
		
		// -----------------------------------------------------------------------------------------------------------------
		// FUNCIÓN QUE RECUPERA EL NÚMERO DE TUPLAS O FILAS AFECTADAS EN UN UPDATE O DELETE. 
		// -----------------------------------------------------------------------------------------------------------------
		
		private function affected_rows(){
			$n = mysql_affected_rows();
			if (mysql_errno() != 0) $this->showError();
			if ($n == 0 || $n  == -1 || $n == "" || $n == NULL) return 0;
			
			return $n;
		}
		
		// -----------------------------------------------------------------------------------------------------------------
		// FUNCIÓN QUE ACTUALIZA LAS VARIABLES DE ERROR, GUARDANDO EL ÚLTIMO CÓDIGO Y MENSAJE DE ERROR.
		// ADEMÁS MUESTRA LOS MENSAJES DE ERROR SI PROCEDE. 
		// -----------------------------------------------------------------------------------------------------------------
		
		public function showError(){
			//echo "E0.".$this->last_query."ERROR ".mysql_errno().": ".mysql_error().".<br /><br />";
			$this->last_error_id 	=  mysql_errno();
			$this->last_error_msg	=  mysql_error();
			if (strpos($this->_IGNORE_ERRORS, (string) mysql_errno()) !== false){
				if ($this->_SHOW_WARNING_ERROR){
					if ($this->_SHOW_CONTROL_MESSAGES && $this->_SHOW_IGNORED_ERRORS) echo '<b style="color:'.$this->_WARNING_COLOR.'">WARNING</b>:<br> Error: ' . mysql_errno() . ": " . mysql_error()."<br>".'<i>'.$sentence."</i><br>\n";
					if ($this->_STOP_WARNING_ERROR && $this->_SHOW_IGNORED_ERRORS) die();
				} elseif($this->_SHOW_CONTROL_MESSAGES) {
					die('<b style="color:'.$this->_ERROR_COLOR.'">FATAL ERROR</b>:<br>Error: ' . mysql_errno() . ": " . mysql_error()."<br>");
				}
			} elseif($this->_SHOW_CONTROL_MESSAGES) {
				die('<b style="color:'.$this->_ERROR_COLOR.'">FATAL ERROR</b>:<br>Error: ' . mysql_errno() . ": " . mysql_error()."<br>");
			}
		}
				
		// -----------------------------------------------------------------------------------------------------------------------------------
		// REALIZA LA CONSULTA A LA BASE DE DATOS ENVIADA POR $sentence Y DEVUELVE EL VALOR DEL CAMPO SOLICITADO.
		// ES ÚNICAMENTE PARA CONSULTAS EN LAS QUE SE SOLICITA UN ÚNICO DATO Y UNA ÚNICA COINCIDENCIA.
		// -----------------------------------------------------------------------------------------------------------------------------------
		// EJ.: SELECT name FROM clientes WHERE id = 1;
		// Si no hay coincidencias devuelve el valor por defecto establecido por _EMPTY_FIELD_BY_DEFAULT
		// -----------------------------------------------------------------------------------------------------------------------------------
		
		public function getValue($sentence){
			$result = $this->query($sentence);

			if ($this->selected_rows == 0){
				return $this->_EMPTY_FIELD_BY_DEFAULT;
			} else {
				$row = mysql_fetch_row($result);
				return $row[0];
			}
		}
		
		// -----------------------------------------------------------------------------------------------------------------------------------
		// REALIZA LA CONSULTA A LA BASE DE DATOS ENVIADA POR $sentence Y DEVUELVE UNA LISTA CON LOS VALORES DE LOS CAMPOS SOLICITADOS.
		// LA LISTA ES UN ARRAY QUE DESPUES PODEMOS RECUPERAR CON LA INSTRUCCIÓN list DE PHP O TRATARLO COMO UN array CUALQUIERA DE PHP.
		// -----------------------------------------------------------------------------------------------------------------------------------
		// EJ.: SELECT id, name FROM blog_tags WHERE id = 112;
		// LA FORMA MÁS FRECUENTE DE RECUPERAR LOS VALORES ES O SERÁ, EN EL EJEMPLO:
		// list($id, $name) = $this->getValues('SELECT id, name FROM blog_tags WHERE id = 112;');
		// PARA DESPUÉS RECUPERALO COMO:
		// echo "id = ".$id.", name = ".$name;
		// Si no hay coincidencias devuelve el valor por defecto establecido por _EMPTY_FIELD_BY_DEFAULT
		// -----------------------------------------------------------------------------------------------------------------------------------
		
		public function getListValues($sentence){
			$result = $this->query($sentence);
			
			$values = trim(substr($sentence, stripos($sentence, "SELECT")+7, stripos($sentence, "FROM",stripos($sentence, "SELECT"))-stripos($sentence, "SELECT")-7));
			$valuesArr = explode(",", $values);
			$valuesList = "";
			foreach($valuesArr as $value){ $valuesList .= "\$".trim($value).", "; }
			$valuesList = "list(".substr($valuesList, 0, strlen($valuesList)-2).")";
			if ($this->selected_rows == 0){
				return $this->_EMPTY_FIELD_BY_DEFAULT;
			} else {
				eval($valuesList." = mysql_fetch_row(\$result);");
				$list = array();
				foreach($valuesArr as $value){ eval("\$aux = \$".trim($value).";");	$list[] = $aux;	}
				
				return $list;
			}
		}
		
		// -----------------------------------------------------------------------------------------------------------------------------------
		// ELIMINA TODAS LAS FILAS O TUPLAS DE LA TABLA $table QUE CUMPLAN LA CONDICIÓN $cond
		// -----------------------------------------------------------------------------------------------------------------------------------
		public function delete($table, $cond){
			$sentence = "DELETE FROM ".$table." WHERE ".$cond;
			$this->query($sentence);
		}
		
		// -----------------------------------------------------------------------------------------------------------------------------------
		// RECUPERA LOS RESULTADOS REFERENCIADOS POR $this->resource COMO UN ARRAY ASOCIATIVO DE NÚMEROS, NOMBRES ASOCIADOS O AMBOS
		// -----------------------------------------------------------------------------------------------------------------------------------
		// LA VARIABLE $type INDICA CÓMO SE HARÁ LA ASOCIACIÓN. SUS VALORES PUEDEN SER MYSQL_NUM, MYSQL_ASSOC O MYSQL_BOTH. 
		// POR DEFECTO ES MYSQL_BOTH.
		// -----------------------------------------------------------------------------------------------------------------------------------
		
		public function fetchArray($type=MYSQL_BOTH){
			$result = @mysql_fetch_array($this->resource, $type);
			if(mysql_errno() != 0){
				$this->showError();
			}
			
			return $result;
		}
		
		// -----------------------------------------------------------------------------------------------------------------------------------
		// RECUPERA LOS RESULTADOS REFERENCIADOS POR $this->resource COMO OBJETOS.
		// -----------------------------------------------------------------------------------------------------------------------------------
		// LA VARIABLE $class INDICA QUE EL OBJETO SE TRANSFERIRÁ A LA CLASE SUMINISTRADA.
		// -----------------------------------------------------------------------------------------------------------------------------------
		
		function fetchObject($class=""){
			if($class == "") $result = @mysql_fetch_object($this->resource);
			else  $result = @mysql_fetch_object($this->resource, $class);
			
			if(mysql_errno() != 0){
				$this->showError();
			}
			
			return $result;
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------
		// FUNCIÓN PARA EXPORTAR LA BBDD COMPLETA O TABLAS CONCRETAS.
		// -------------------------------------------------------------------------------------------------------------------------------------
		// $exportfilename
		// Es el nómbre del archivo destuino.
		// $exporttables:
		// Array que contiene las tablas de la base de datos que seran resguardadas. Puede especificarse un valor false para indicar que se 
		// exporten todas las tablas de la base de datos especificada por _DATABASE_NAME_DEVELOPMENT ó _DATABASE_NAME_PRODUCTION. Ejemplos son:
		// $tablas = false; ó $tablas = array("tabla1", "tabla2", "tablaetc");
		// $exportcompresion:
		// Indica cómo se enviará el archivo con los datos exportados. Puede ser FALSE, GZ ó BZ2.
		// $exportdrop:
		// Indica si se añadirá DROP antes de la creación de cada tabla.
		// -------------------------------------------------------------------------------------------------------------------------------------
		
		public function export($exportfilename, $exportdrop=false, $exporttables=false, $exportcompresion=false){
			// Definimos la base de datos de desarrollo o produción
			if($_SERVER['HTTP_HOST'] == "localhost"){
				$bd = mySQL::_DATABASE_NAME_DEVELOPMENT;
			} else {
				$bd = mySQL::_DATABASE_NAME_PRODUCTION;
			}
			
			// Array de tablas a exportar.
			if($exporttables != false){
				$exporttables= explode(",", $exporttables);
				for ($i=0;$i<count($exporttables);$i++){ $tablas[] = $exporttables[$i]; }
			} else {
				$result=$this->query("SHOW TABLES FROM $bd");
				while ($fila = $this->fetchArray(MYSQL_NUM)) {
					$tablas[] = $fila[0];
				}
			}
			
			// Establecemos si hay compresión o no.
			if ($exportcompresion == false){
				$compresion = false;
			} else {
				$compresion = $exportcompresion;
			}
			
			// Introducimos la cabecera del archivo.
			$info['dumpversion'] = "1.0b";
			$info['fecha'] = date("d-m-Y");
			$info['hora'] = date("h:m:s A");
			$info['mysqlver'] = mysql_get_server_info();
			$info['phpver'] = phpversion();
			$aux = $this->getInfo();
			
			ob_start();
			print_r($tablas);
			$representacion = ob_get_contents();
			ob_end_clean ();
			preg_match_all('/(\[\d+\] => .*)\n/', $representacion, $matches);
			$info['tablas'] = implode(";  ", $matches[1]);
			$dump = "
# +===================================================================
# | MySQL Class {$info['dumpversion']}
# | por islavisual <comercial@islavisual.com>
# |
# | Generado el {$info['fecha']} a las {$info['hora']} por el usurio '$usurio'
# | Servidor: {$_SERVER['HTTP_HOST']}
# | Browser: {$aux['browser']} {$aux['version']}
# | SO: {$aux['so']}
# | IP: {$aux['ip']}
# | MySQL Version: {$info['mysqlver']}
# | PHP Version: {$info['phpver']}
# | Base de datos: '$bd'
# | Tablas: {$info['tablas']}
# |
# +-------------------------------------------------------------------";
			
			foreach ($tablas as $tabla) {
				$drop_table_query = "";
				$create_table_query = "";
				$insert_into_query = "";
				
				/* Se halla el query que será capaz vaciar la tabla. */
				if ($exportdrop) {
					$drop_table_query = "DROP TABLE IF EXISTS `$tabla`;";
				} else {
					$drop_table_query = "# No especificado.";
				}
			
				/* Se halla el query que será capaz de recrear la estructura de la tabla. */
				$create_table_query = "";
				$result = $this->query("SHOW CREATE TABLE $tabla;");
				while ($fila =$this->fetchArray(MYSQL_NUM)) {
						$create_table_query = $fila[1].";";
				}
				
				/* Se halla el query que será capaz de insertar los datos. */
				$insert_into_query = "";
				$respuesta = $this->query("SELECT * FROM $tabla;");
				while ($fila = $this->fetchArray()) {
						$values = array();
						$columnas = array_keys($fila);
						foreach ($columnas as $columna) {
							if ( gettype($fila[$columna]) == "NULL" ) {
								$values[]="NULL";
							} else {
								$values[] = "'".mysql_real_escape_string($fila[$columna])."'";
							}
						}
						$insert_into_query .= "INSERT INTO `$tabla` VALUES (".implode(", ", $values).");\n";
						unset($values);
				}
				
				$dump .="
				
# | Vaciado de tabla '$tabla'
# +------------------------------------->
$drop_table_query
	
# | Estructura de la tabla '$tabla'
# +------------------------------------->
$create_table_query

# | Carga de datos de la tabla '$tabla'
# +------------------------------------->
$insert_into_query

			";
			}
			
			/* Envio */
			if ( !headers_sent() ) {
				header("Pragma: no-cache");
				header("Expires: 0");
				header("Content-Transfer-Encoding: binary");
				switch ($compresion) {
				case "gz":
					header("Content-Disposition: attachment; filename=$nombre.gz");
					header("Content-type: application/x-gzip");
					echo gzencode($dump, 9);
					break;
				case "bz2": 
					header("Content-Disposition: attachment; filename=$nombre.bz2");
					header("Content-type: application/x-bzip2");
					echo bzcompress($dump, 9);
					break;
				default:
					header("Content-Disposition: attachment; filename=$nombre");
					header("Content-type: application/force-download");
					echo $dump;
				}
			} else {
				echo "<b><span style='color:".$this->_ERROR_COLOR."'>ATENCION</span>: No se puede enviar los encabezados pertinentes porque ya han sido enviados previamente.</b><br />\n<pre>\n$dump\n</pre>";
			}
		}
		
		// -------------------------------------------------------------------------------------------------------------------------------------------
		// LIBERA TODA LA MEMORIA ASOCIADA A $this->resource
		// -------------------------------------------------------------------------------------------------------------------------------------------
		// Solo necesita ser llamado si se está preocupado por la cantidad de memoria que está siendo usada por las consultas que devuelven conjuntos 
		// de resultados grandes. Toda la memoria de resultados asociada se liberará automaticamente al finalizar la ejecución del script. 
		// -------------------------------------------------------------------------------------------------------------------------------------------
		
		public function free(){
        	mysql_free_result($this->resource);
	    }
		
		// -------------------------------------------------------------------------------------------------------------------------------------------
		// CIERRA LA CONEXIÓN NO PERSISTENTE DEL SERVIDOR DE MYSQL QUE ESTÁ ASOCIADA A $this->resource.
		// -------------------------------------------------------------------------------------------------------------------------------------------
		
		public function disconnect(){ 
			if ($this->resource){ 
				return mysql_close($this->resource); 
			} 
		}
	}
?>
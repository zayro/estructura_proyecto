<?php

class Rest {

  public $tipo = "application/json";
  public $datosPeticion = array();
  private $_codEstado = 200;

  public function __construct() {
    $this->tratarEntrada();
  }

  public function mostrarRespuesta($data, $estado) {
    $this->_codEstado = ($estado) ? $estado : 200; //si no se envía $estado por defecto será 200  
    $this->setCabecera();
    echo $data;
    exit;
  }

  private function setCabecera() {
    header("HTTP/1.1 " . $this->_codEstado . " " . $this->getCodEstado());
    header("Content-Type:" . $this->tipo . ';charset=utf-8');
  }

  private function limpiarEntrada($data) {
    $entrada = array();
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        $entrada[$key] = $this->limpiarEntrada($value);
      }
    } else {
      if (get_magic_quotes_gpc()) {
        //Quitamos las barras de un string con comillas escapadas  
        //Aunque actualmente se desaconseja su uso, muchos servidores tienen activada la extensión magic_quotes_gpc.   
        //Cuando esta extensión está activada, PHP añade automáticamente caracteres de escape (\) delante de las comillas que se escriban en un campo de formulario.   
        $data = trim(stripslashes($data));
      }
      //eliminamos etiquetas html y php  
      $data = strip_tags($data);
      //Conviertimos todos los caracteres aplicables a entidades HTML  
      $data = htmlentities($data);
      $entrada = trim($data);
    }
    return $entrada;
  }

  private function tratarEntrada() {
    $metodo = $_SERVER['REQUEST_METHOD'];
    switch ($metodo) {
      case "GET":
        $this->datosPeticion = $this->limpiarEntrada($_GET);
        break;
      case "POST":
        $this->datosPeticion = $this->limpiarEntrada($_POST);
        break;
      case "DELETE"://"falling though". Se ejecutará el case siguiente  
      case "PUT":
        //php no tiene un método propiamente dicho para leer una petición PUT o DELETE por lo que se usa un "truco":  
        //leer el stream de entrada file_get_contents("php://input") que transfiere un fichero a una cadena.  
        //Con ello obtenemos una cadena de pares clave valor de variables (variable1=dato1&variable2=data2...)
        //que evidentemente tendremos que transformarla a un array asociativo.  
        //Con parse_str meteremos la cadena en un array donde cada par de elementos es un componente del array.  
        parse_str(file_get_contents("php://input"), $this->datosPeticion);
        $this->datosPeticion = $this->limpiarEntrada($this->datosPeticion);
        break;
      default:
        $this->response('', 404);
        break;
    }
  }

  private function getCodEstado() {
    $estado = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error');
    $respuesta = ($estado[$this->_codEstado]) ? $estado[$this->_codEstado] : $estado[500];
    return $respuesta;
  }

}

class Api extends Rest {

  const servidor = "localhost";
  const usuario_db = "usuario";
  const pwd_db = "";
  const nombre_db = "autorizacion";

  private $_conn = NULL;
  private $_metodo;
  private $_argumentos;

  public function __construct() {
    parent::__construct();
    $this->conectarDB();
  }

  private function conectarDB() {
    $dsn = 'mysql:dbname=' . self::nombre_db . ';host=' . self::servidor;
    try {
      $this->_conn = new PDO($dsn, self::usuario_db, self::pwd_db);
    } catch (PDOException $e) {
      echo 'Falló la conexión: ' . $e->getMessage();
    }
  }

  private function devolverError($id) {
    $errores = array(
        array('estado' => "error", "msg" => "petición no encontrada"),
        array('estado' => "error", "msg" => "petición no aceptada"),
        array('estado' => "error", "msg" => "petición sin contenido"),
        array('estado' => "error", "msg" => "email o password incorrectos"),
        array('estado' => "error", "msg" => "error borrando usuario"),
        array('estado' => "error", "msg" => "error actualizando nombre de usuario"),
        array('estado' => "error", "msg" => "error buscando usuario por email"),
        array('estado' => "error", "msg" => "error creando usuario"),
        array('estado' => "error", "msg" => "usuario ya existe")
    );
    return $errores[$id];
  }

  public function procesarLLamada() {
    if (isset($_REQUEST['url'])) {
      //si por ejemplo pasamos explode('/','////controller///method////args///') el resultado es un array con elem vacios;
      //Array ( [0] => [1] => [2] => [3] => [4] => controller [5] => [6] => [7] => method [8] => [9] => [10] => [11] => args [12] => [13] => [14] => )
      $url = explode('/', trim($_REQUEST['url']));
      //con array_filter() filtramos elementos de un array pasando función callback, que es opcional.
      //si no le pasamos función callback, los elementos false o vacios del array serán borrados 
      //por lo tanto la entre la anterior función (explode) y esta eliminamos los '/' sobrantes de la URL
      $url = array_filter($url);
      $this->_metodo = strtolower(array_shift($url));
      $this->_argumentos = $url;
      $func = $this->_metodo;
      if ((int) method_exists($this, $func) > 0) {
        if (count($this->_argumentos) > 0) {
          call_user_func_array(array($this, $this->_metodo), $this->_argumentos);
        } else {//si no lo llamamos sin argumentos, al metodo del controlador  
          call_user_func(array($this, $this->_metodo));
        }
      } else
        $this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);
    }
    $this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);
  }

  private function convertirJson($data) {
    return json_encode($data);
  }

  private function usuarios() {
    if ($_SERVER['REQUEST_METHOD'] != "GET") {
      $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
    }
    $query = $this->_conn->query("SELECT id, nombre, email FROM usuario");
    $filas = $query->fetchAll(PDO::FETCH_ASSOC);
    $num = count($filas);
    if ($num > 0) {
      $respuesta['estado'] = 'correcto';
      $respuesta['usuarios'] = $filas;
      $this->mostrarRespuesta($this->convertirJson($respuesta), 200);
    }
    $this->mostrarRespuesta($this->devolverError(2), 204);
  }

  private function login() {
    if ($_SERVER['REQUEST_METHOD'] != "POST") {
      $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
    }
    if (isset($this->datosPeticion['email'], $this->datosPeticion['pwd'])) {
      //el constructor del padre ya se encarga de sanear los datos de entrada  
      $email = $this->datosPeticion['email'];
      $pwd = $this->datosPeticion['pwd'];
      if (!empty($email) and ! empty($pwd)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
          //consulta preparada ya hace mysqli_real_escape()  
          $query = $this->_conn->prepare("SELECT id, nombre, email, fRegistro FROM usuario WHERE   
           email=:email AND password=:pwd ");
          $query->bindValue(":email", $email);
          $query->bindValue(":pwd", sha1($pwd));
          $query->execute();
          if ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
            $respuesta['estado'] = 'correcto';
            $respuesta['msg'] = 'datos pertenecen a usuario registrado';
            $respuesta['usuario']['id'] = $fila['id'];
            $respuesta['usuario']['nombre'] = $fila['nombre'];
            $respuesta['usuario']['email'] = $fila['email'];
            $this->mostrarRespuesta($this->convertirJson($respuesta), 200);
          }
        }
      }
    }
    $this->mostrarRespuesta($this->convertirJson($this->devolverError(3)), 400);
  }

  private function actualizarNombre($idUsuario) {
    if ($_SERVER['REQUEST_METHOD'] != "PUT") {
      $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
    }
    //echo $idUsuario . "<br/>";  
    if (isset($this->datosPeticion['nombre'])) {
      $nombre = $this->datosPeticion['nombre'];
      $id = (int) $idUsuario;
      if (!empty($nombre) and $id > 0) {
        $query = $this->_conn->prepare("update usuario set nombre=:nombre WHERE id =:id");
        $query->bindValue(":nombre", $nombre);
        $query->bindValue(":id", $id);
        $query->execute();
        $filasActualizadas = $query->rowCount();
        if ($filasActualizadas == 1) {
          $resp = array('estado' => "correcto", "msg" => "nombre de usuario actualizado correctamente.");
          $this->mostrarRespuesta($this->convertirJson($resp), 200);
        } else {
          $this->mostrarRespuesta($this->convertirJson($this->devolverError(5)), 400);
        }
      }
    }
    $this->mostrarRespuesta($this->convertirJson($this->devolverError(5)), 400);
  }

  private function borrarUsuario($idUsuario) {
    if ($_SERVER['REQUEST_METHOD'] != "DELETE") {
      $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
    }
    $id = (int) $idUsuario;
    if ($id >= 0) {
      $query = $this->_conn->prepare("delete from usuario WHERE id =:id");
      $query->bindValue(":id", $id);
      $query->execute();
      //rowcount para insert, delete. update  
      $filasBorradas = $query->rowCount();
      if ($filasBorradas == 1) {
        $resp = array('estado' => "correcto", "msg" => "usuario borrado correctamente.");
        $this->mostrarRespuesta($this->convertirJson($resp), 200);
      } else {
        $this->mostrarRespuesta($this->convertirJson($this->devolverError(4)), 400);
      }
    }
    $this->mostrarRespuesta($this->convertirJson($this->devolverError(4)), 400);
  }

  private function existeUsuario($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $query = $this->_conn->prepare("SELECT email from usuario WHERE email = :email");
      $query->bindValue(":email", $email);
      $query->execute();
      if ($query->fetch(PDO::FETCH_ASSOC)) {
        return true;
      }
    } else
      return false;
  }

  private function crearUsuario() {
    if ($_SERVER['REQUEST_METHOD'] != "POST") {
      $this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
    }
    if (isset($this->datosPeticion['nombre'], $this->datosPeticion['email'], $this->datosPeticion['pwd'])) {
      $nombre = $this->datosPeticion['nombre'];
      $pwd = $this->datosPeticion['pwd'];
      $email = $this->datosPeticion['email'];
      if (!$this->existeUsuario($email)) {
        $query = $this->_conn->prepare("INSERT into usuario (nombre,email,password,fRegistro) VALUES (:nombre, :email, :pwd, NOW())");
        $query->bindValue(":nombre", $nombre);
        $query->bindValue(":email", $email);
        $query->bindValue(":pwd", sha1($pwd));
        $query->execute();
        if ($query->rowCount() == 1) {
          $id = $this->_conn->lastInsertId();
          $respuesta['estado'] = 'correcto';
          $respuesta['msg'] = 'usuario creado correctamente';
          $respuesta['usuario']['id'] = $id;
          $respuesta['usuario']['nombre'] = $nombre;
          $respuesta['usuario']['email'] = $email;
          $this->mostrarRespuesta($this->convertirJson($respuesta), 200);
        } else
          $this->mostrarRespuesta($this->convertirJson($this->devolverError(7)), 400);
      } else
        $this->mostrarRespuesta($this->convertirJson($this->devolverError(8)), 400);
    } else {
      $this->mostrarRespuesta($this->convertirJson($this->devolverError(7)), 400);
    }
  }

}

/*     
$api = new Api();
$api->procesarLLamada();
https://dedudosaprocedencia.wordpress.com/2012/05/09/rest-usando-php-y-curl/
http://programandolo.blogspot.com.co/2013/08/ejemplo-php-de-servicio-restful-parte-1.html

 */

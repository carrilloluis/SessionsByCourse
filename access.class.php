<?php
class flexibleAccess{
  var $dbTable;	
  var $dsn_;
  var $username_;
  var $password_;
  
  var $dsn;
  var $tbFields = array('userID'=> 'idUser','login' => 'username','pass'  => 'password','email' => 'email','active'=> 'active');

  var $remTime = 2592000; //One month | One day ?? | One Hour
  var $sessionVariable = 'userSessionValue'; // Cambiar esto???
  var $remCookieName = 'ckSavePa2ss';	// Cambiar por algo no default
  var $remCookieDomain = '';
  var $passMethod = 'md5';
  var $displayErrors = true;
  var $userID;
  var $userData = array();

function flexibleAccess($dsnStr, $usr_, $pwd_, $table = 'User', $settings = '') 
{
/*if ( is_array($settings) ) { 
	foreach ( $settings as $k => $v ){
		if ( property_exists($this, $k) ) $this->{$k} = $v;
		else die('Property '.$k.' does not exists');
	}
}*/
$this->remCookieDomain = $this->remCookieDomain == '' ? $_SERVER['HTTP_HOST'] : $this->remCookieDomain;
$this->dbTable = $table; /* Asignar la cadena de conexion */ // Verificar si la cadena es vacía???
$this->dsn_ = $dsnStr;
$this->username_ = $usr_;
$this->password_ = $pwd_;
if ( !isset( $_SESSION ) ) { session_start(); }
if ( !empty($_SESSION[$this->sessionVariable]) ) { 	$this->loadUser( $_SESSION[$this->sessionVariable] ); }
if ( isset($_COOKIE[$this->remCookieName]) && !$this->is_loaded()){
	$u = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
	$this->login($u['uname'], $u['password']);
}
}

function is_loaded() { return empty($this->userID) ? false : true; }
function login($uname, $password, $remember = false, $loadUser = true) {
$originalPassword = $password;
switch(strtolower($this->passMethod)){
case 'sha1': $password = sha1($password); break;
case 'md5' : $password = md5($password); break;
case 'nothing': $password = $password; break; }
$cn = new PDO($this->dsn_, $this->username_, $this->password_);
$sql = "SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['login']}` =:usr_ AND `{$this->tbFields['pass']}` =:pwd_  AND `{$this->tbFields['active']}`='1' ";
$rsQuery = $cn->prepare($sql);
$rsQuery->bindParam(':usr_', $uname);
$rsQuery->bindParam(':pwd_', $password);
$rsQuery->execute();
$tQuery = $rsQuery->fetchAll();

if ( sizeof($tQuery) == 0)
	return false;

if ( $loadUser )
{
	$this->userData = $tQuery; //mysql_fetch_array($res);
	
	$this->userID = $this->userData[0][$this->tbFields['userID']];
	$_SESSION[$this->sessionVariable] = $this->userID;
	
if ( $remember ){
	$cookie = base64_encode(serialize(array('uname'=>$uname,'password'=>$originalPassword)));
	$a = setcookie($this->remCookieName, 
	$cookie,time()+$this->remTime, '/', $this->remCookieDomain);
}
}
$cn = null;
return true;
}
function logout($redirectTo = '') {
setcookie($this->remCookieName, '', time()-3600);
$_SESSION[$this->sessionVariable] = '';
unset($_SESSION);
session_destroy(); //ya que no tendria que destruir sin una previamente creada
if ( $redirectTo != '' && !headers_sent()){
header('Location: '.$redirectTo );
exit;//To ensure security
}
}
function loadUser($userID) {
$cn = new PDO($this->dsn_, $this->username_, $this->password_);
$rsQueryUser = $cn->prepare("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['userID']}` =:usr_id_  ");
$rsQueryUser->bindParam(':usr_id_', $userID);
$rsQueryUser->execute();
$tQueryUser = $rsQueryUser->fetchAll();

if ( sizeof($tQueryUser) == 0 )
	return false;

$this->userData = $tQueryUser; //mysql_fetch_array($res);
$this->userID = $userID;
$_SESSION[$this->sessionVariable] = $this->userID;

$cn = null;
return true;
}
function is($prop) {
return $this->get_property($prop)==1?true:false;
}
function get_property($property) {
if (empty($this->userID)) die('No user is loaded'); 
if (!isset($this->userData[$property])) die('Unknown property <b>'.$property.'</b>');
return $this->userData[$property];
}
function is_active() {
return $this->userData[$this->tbFields['active']];
}
function randomPass($length=10, $chrs = '1234567890qwertyuiopasdfghjklzxcvbnm') {
$ch = str_split($chrs);
for($i = 0; $i < $length; $i++) { $pwd .= $chrs{mt_rand(0, strlen($chrs))}; }
return $pwd;
}

/*  
  /**
  	* Activates the user account - @return bool
  function activate()
  {
    if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
    if ( $this->is_active()) $this->error('Allready active account', __LINE__);
    $res = $this->query("UPDATE `{$this->dbTable}` SET {$this->tbFields['active']} = 1 
	WHERE `{$this->tbFields['userID']}` = '".$this->escape($this->userID)."' LIMIT 1");
    if (@mysql_affected_rows() == 1)
	{
		$this->userData[$this->tbFields['active']] = true;
		return true;
	}
	return false;
  }
  /*
   * Creates a user account. The array should have the form 'database field' => 'value'
   * @param array $data
   * return int
  function insertUser($data){
    if (!is_array($data)) $this->error('Data is not an array', __LINE__);
    switch(strtolower($this->passMethod)){
	  case 'sha1':
	  	$password = "SHA1('".$data[$this->tbFields['pass']]."')"; break;
	  case 'md5' :
	  	$password = "MD5('".$data[$this->tbFields['pass']]."')";break;
	  case 'nothing':
	  	$password = $data[$this->tbFields['pass']];
	}
    foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";
    $data[$this->tbFields['pass']] = $password;
    $this->query("INSERT INTO `{$this->dbTable}` (`".implode('`, `', array_keys($data))."`) VALUES (".implode(", ", $data).")");
    return (int)mysql_insert_id($this->dbConn);
  }
  /*




*/
}
?>

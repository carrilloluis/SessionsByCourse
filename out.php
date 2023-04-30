<?
require_once 'data@keys.php';	
require_once './access.class.php';	
$user = new flexibleAccess($provider, $usuario, $contrasegna);
$user->logout();
unset($user);
echo '<meta http-equiv="refresh" content="0;url=index.php">';
?>

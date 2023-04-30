<?
mb_http_input("utf-8");
mb_http_output("utf-8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
	<meta http-equiv="pragma" content="no-cache" />
	<title></title>
	<link rel="stylesheet" type="text/css" href=".css" media="screen" />
	<script language="javascript" type="text/javascript" src=".js"></script>
</head>
<body>
	<div id="container">
		<div id="main">
<?
echo '<form action="index.php"  method="POST" /><table cellspacing=\'0\' cellpadding=\'1\'>
		 <tr><td width="250"><label for="loggeto">Nombre de usuario: </td><td><input type="text" name="loggeto" id="loggeto" class=\'inOut_\' tabindex=\'1\'/></td></tr>
		 <tr><td><label for=\'pwd\'>Contrase&ntilde;a:</label> </td><td><input type="password" name="pwd" id="pwd" class=\'inOut_\' tabindex=\'2\'/></td></tr>
		 <tr><td>&nbsp;</td><td><input type="submit" value="Entrar" class=\'inOut_\' tabindex=\'3\' name="botoncito" /></td></tr></table></form>';
?>
		</div>
	</div>
</body>
</html>

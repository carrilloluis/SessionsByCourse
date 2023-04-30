<?
mb_http_input("utf-8");
mb_http_output("utf-8");

require_once 'data@keys.php';
require_once './access.class.php';	
$user = new flexibleAccess($provider, $usuario, $contrasegna);
if ( $user->is_loaded() ) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
    <title></title>
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
    <script language="javascript" type="text/javascript" src=".js"></script>
</head>
<body>
<div id="container"><div id="header">&nbsp;</div>
<?
	include('cCourse.php'); include('cLearningSession.php');
	$p = new cCourse($provider, $usuario, $contrasegna);
	$ls = new cLearningSession($provider, $usuario, $contrasegna);
	
	if (isset($_REQUEST['_do_'])) {
		switch ($_REQUEST['_do_']) {
			case '35c5c9d038254a9c55cd9b1bc594fcce': { echo $ls->Add($_GET['_id_']); break; }
			case '005238d3d30cb71e176a2eef8f1d2003': { echo $ls->ShowByCourse($_GET['_id_']); break; }
			case 'c0e156cb568c48b49b8ec06bb49bc9ff': { echo $ls->Edit($_GET['_id_'], $_GET['_who_']); break; }
			case '1205cad2d473dc830cd734496705ac1c': { echo $ls->Update(); break; }
			case '4113b786b268368a414a49dbf2a95246': { echo $ls->Delete($_GET['_id_'], $_GET['_who_']); break; }
			case '8a1cc54dfd63dde6769f91017b3cb05b': { echo $ls->Remove(); break; }
			case '4551a4d210c346f843804be19267ced6': { echo $ls->AddDetailType($_GET['_id_'], $_GET['_who_']); break; }
			case '2fbba15e4f554a08a75f9a0795470f43': { echo $ls->SaveDetailType(); break; }
			case '72e276904348275f00f5f46fe7f4c0be': { echo $ls->AddDetail($_GET['_id_'], $_GET['_who_']); break; }
			case '38709a36419c658f13f36f296f27ee38': { echo $ls->SaveDetail(); break; }
			case '3cd120f70e0c70ae347e209f9fb47efa': { echo $ls->BrowseData($_GET['_id_'], $_GET['_who_']); break; }
			case '3b34171940317b3d07adff10c9a21667': { echo $ls->EditDetail($_GET['_id_'], $_GET['_course_'], $_GET['_who_']); break; }
			case 'c40f41212ae9c9d7503af7def94bc827': { echo $ls->UpdateDetail($_GET['_id_'], $_GET['_course_'], $_GET['_who_']); break; }
			case '949c487fc446ed6faecf50b2a4a07a2c': { echo $ls->DeleteDetail($_GET['_id_'], $_GET['_course_'], $_GET['_who_']); break; }
			case '6a1e8312c490bece24ed8ba9f07143ef': { echo $ls->RemoveDetail(); break; }
			case '3cf213b659e10152f2b7912a5ae53e98': { $ls->Save(); break; }
			case '600cd8677c87f63a0e019f32e657b4b4': { echo $p->ShowAll(); break; } 
			case 'f6d080b79b97552211585f5756823093': { echo $p->Add(); break; } 
			case '146c2e896dbe402f7e0aba967ec9cfda': { $p->Save(); break; }
			case '21fd13a4800b50bd791a367ae49b4cd3': { echo $p->SearchByNameCourse(); break; }
			case '06afce8a44d4f7d42c9eff36aba00542': { echo $p->RequestByNameCourse(); break; }
			case 'da55578f890388a9a16262c208312deb': { echo $p->BrowseData($_GET['_id_']); break; }
			case '4594475d28c87673276666d698196a24': { $p->Edit($_GET['_id_']); break; }
			case '1d3e47d0a214f6bf19bc35515c2badd2': { $p->Update(); break; }
			case '2cd693183b23c993ef09dfbcce90bd1d': { $p->Delete($_GET['_id_']); break; }
			case '8bffc1c1d76f9791024d3ce760e06f63': { $p->Remove(); break; }
			case '8e693377bb16761806688ad6f0799552': { $p->AddDetail($_GET['_id_']); break; }
			case '3fad79aebf59308874b3935d20c22f79': { $p->AddDetailType($_GET['_id_']); break; }
			case '89c090104f91856f08c1fafc54dbfbd5': { $p->SaveDetailType(); break; }
			case '509c97efb7f2c69e1583b3321a61f101': { $p->SaveDetail(); break; }
			case '95bed3f1cde70013dd8d971480967c0e': { $p->EditDetail($_GET['_id_'], $_GET['_who_']); break; }
			case '90858cb821e3b76622dfd9644b849967': { $p->UpdateDetail(); break; }
			case 'fad877d9009c84107223d7ed114c8803': { $p->DeleteDetail($_GET['_id_'], $_GET['_who_']); break; }
			case '45146fcb92e671f4d0b960b73521c14a': { $p->RemoveDetail(); break; }
			default: { die('NOT Enable Operation<br /> &rarr; <a href="'. $_SERVER['PHP_SELF'] .'" class="menu_item"> Continue</a>'); break; }
		}
	} else { 
		echo $p->ShowAll(); 
	} 
?><div id="footer">&nbsp;</div>
</div>
</body></html>
<?
} else {
	if ( isset($_POST['loggeto']) && isset($_POST['pwd']) && isset($_POST['botoncito']) ) {
		if ( !$user->login($_POST['loggeto'], $_POST['pwd'], true) ) 
			echo '<meta http-equiv="refresh" content="0;url=in.php">';
		else 
			echo '<meta http-equiv="refresh" content="0;url=index.php">';	
	} else {
		echo '<meta http-equiv="refresh" content="0;url=in.php">';
	}	 
}	
unset($user); 
?>

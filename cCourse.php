<? 
class cCourse { 
	var $dsn_; 
	var $username_; 
	var $password_;
	
	var $nameTable = 'Course';
	
	function cCourse( $strDSN, $strUser, $strPwd ) 
	{ 
		$this->dsn_ = $strDSN; 
		$this->username_ = $strUser; 
		$this->password_ = $strPwd; 
	}

	function ShowAll() 
	{
		echo '<div id="sidebar">&larr; <a href="out.php" class="menu_item">Quit/Close session</a> | + <a href="'. $_SERVER['PHP_SELF'] .'?_do_=f6d080b79b97552211585f5756823093" class="menu_item">Add one course</a> | &rarr; <a href="'.$_SERVER['PHP_SELF'].'?_do_=21fd13a4800b50bd791a367ae49b4cd3" class="menu_item">Search Course</a></div><div id="main"><h1>Your Course(s)</h1>';
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
		$request_ = $cn->query("SELECT * FROM ". $this->nameTable ." ORDER BY name". $this->nameTable);
		$rsTotal = $request_->fetchAll();
		$risposta = '';
		if (sizeof($rsTotal) > 0) {
			foreach ($rsTotal as $row) {
				$risposta .= '<a href="' . $_SERVER['PHP_SELF'] . '?_do_=da55578f890388a9a16262c208312deb&_id_='. $row[0] .'" class="menu_item" title="View data about this course">&rarr;</a> | ' . $row[1] . " &mdash; " .$row[2] . '<br />';
			}
		} else {
				$risposta = '<p>No one register requested<br />0 results for your account</p>';
		} 
		$cn = null;
		return $risposta . '</div>';
	}

	function Add() {
		return '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=600cd8677c87f63a0e019f32e657b4b4" class="menu_item">Back/Cancel</a></div><div id="main"><h3>&deg; Push <em>course name</em> into DB.</h3><form action="'. $_SERVER['PHP_SELF'] . '" method="POST">
		<input type="hidden" name="_do_" value="146c2e896dbe402f7e0aba967ec9cfda" />
		<table cellpadding="3" cellspacing="0" >
			<tr>
				<td><a href="javascript: alert(\'Coloquial Name of the Course.\');" class="help_">?</a> | <label for="_nome_course_">Course Name :</label></td>
				<td><input type="text" name="_nome_course_" tabindex="1" style="border: 1px solid gray; padding: 4px; font-family: Georgia; font-size: 16px;" size="30" /></td>
			</tr>
			<tr>
				<td><a href="javascript: alert(\'Course Year proposal exposed.\');" class="help_">?</a> | <label for="_year_">Year/Semester :</label></td>
				<td><input type="text" name="_year_" tabindex="1" style="border: 1px solid gray; padding: 4px; font-family: Georgia; font-size: 16px;" size="20" value="'.date("Y").'"/> &mdash; <select name="_semester_"><option value="I">I</option><option value="II">II</option></select></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><br /><input type="submit" value="[+] Add this course" tabindex="4" name="btn_add_course_"/></td>
			</tr></table></form></div>';
	}

	function Save()	{
		if (isset($_POST['btn_add_course_'])) {
			if (!empty($_POST['_nome_course_'])) {
				$cn = new PDO($this->dsn_, $this->username_, $this->password_);
				$rqst = $cn->prepare("INSERT INTO ". $this->nameTable ."(name". $this->nameTable .", semester". $this->nameTable .", idUser, created, modified) VALUES (:name, :year, :who, :date_, :date_);");
				$rqst->bindParam(':name', $_POST['_nome_course_']);
				$compose_semester = $_POST['_year_'] ."-".$_POST['_semester_'];
				$rqst->bindParam(':year', $compose_semester);
				$rqst->bindParam(':who', $_SESSION['userSessionValue']);
				$rqst->bindParam(':date_', date("Y-m-d H:i:s"));
				$rqst->execute();
				$cn = null;
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=600cd8677c87f63a0e019f32e657b4b4">';
			} else {
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=600cd8677c87f63a0e019f32e657b4b4">';
			}
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=600cd8677c87f63a0e019f32e657b4b4">'; 
		}
	}

	function SearchByNameCourse() {
		return '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=600cd8677c87f63a0e019f32e657b4b4" class="menu_item">Back</a></div><div id="main"><h3>&deg; Look up one Course into DB.</h3><form action="'. $_SERVER['PHP_SELF'] .'" method="POST">
		<input type="hidden" name="_do_" value="06afce8a44d4f7d42c9eff36aba00542" /><table cellspacing="0" cellpadding="0">
		<tr><td width="200"><a href="javascript: alert(\'Put the word to match into database search.\');" class="help_">?</a> | <label for="_searchByName_">Name Course Search : </label>
		</td><td><input type="text" name="_request2search_" style="border: 1px solid gray; padding: 4px; font-family: Georgia; font-size: 16px;" size="30" tabindex="1" /></td></tr>
		<tr><td>&nbsp;</td><td><br /><input type="submit" tabindex="4" name="btn_search_" value="&rarr; send my request" /></td></tr></table></form></div>';
	}

	function RequestByNameCourse() {
		if (isset($_POST['btn_search_'])) {
			if (!empty($_POST['_request2search_'])) {
				$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
				$risposta = '<div id="sidebar">';
				$rsByName = $cn->prepare("SELECT * FROM ". $this->nameTable ." WHERE name". $this->nameTable ." LIKE :DATA");
				$rsByName->bindParam(':DATA', $requested);
				$requested = '%'. $_POST['_request2search_'].'%';
				$rsByName->execute();
				$rsTotal = $rsByName->fetchAll();	// Si el tamaño de la consulta es cero tonces un mensaje complaciente - no ofensivo
				if (sizeof($rsTotal) > 0) {
					$risposta = $risposta . '&larr; <a href="'. $_SERVER['PHP_SELF'] .'" class="menu_item">Back home</a> | &rarr; <a href="'. $_SERVER['PHP_SELF'] . '?_do_=21fd13a4800b50bd791a367ae49b4cd3" class="menu_item">Another search</a></div><div id="main"><h3>&deg; Search results - ' . sizeof($rsTotal) . '</h3>';
					foreach ($rsTotal as $rowSet) { 
						$risposta .= '<a href="' . $_SERVER['PHP_SELF'] . '?_do_=da55578f890388a9a16262c208312deb&_id_='. $rowSet[0] .'">' . $rowSet[1] . ' &mdash; ' . $rowSet[2] . '</a><br />';  }
				} else { 
					$risposta = '<p>No one with pattern requested<br />0 results for your request</p> &rarr; <a href="'.$_SERVER['PHP_SELF'].'?_do_=21fd13a4800b50bd791a367ae49b4cd3" class="menu_item">Search again</a>'; 		
				}
				$cn = null;
				}
			else { $risposta = 'No one NAME to search,<br /> try <a href="'.$_SERVER['PHP_SELF'].'?_do_=21fd13a4800b50bd791a367ae49b4cd3" class="menu_item">again</a> or <a href="'.$_SERVER['PHP_SELF'].'" class="menu_item">cancel</a>';  }
		}
		return $risposta . '</div>';
	}

	function BrowseData( $itemRequested ) {
		$risposta = '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=600cd8677c87f63a0e019f32e657b4b4" class="menu_item">Back</a>';
		$risposta .= ' | &crarr; <a href="'.$_SERVER['PHP_SELF']. '?_do_=4594475d28c87673276666d698196a24&_id_='.$itemRequested.'" class="menu_item">Edit</a> | &times; <a href="'.$_SERVER['PHP_SELF'].'?_do_=2cd693183b23c993ef09dfbcce90bd1d&_id_='.$itemRequested.'" class="menu_item">Delete</a> | + <a href="' . $_SERVER['PHP_SELF']. '?_do_=8e693377bb16761806688ad6f0799552&_id_='.$itemRequested.'" class="menu_item">Add aditional data about this Course</a> | &harr; <a href="' . $_SERVER['PHP_SELF']. '?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='.$itemRequested.'" class="menu_item">See my learning(s) session(s)</a> ]</div><div id="main">';
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
		$q = $cn->prepare("SELECT name". $this->nameTable .", semester". $this->nameTable ." FROM ". $this->nameTable ." WHERE id". $this->nameTable ."=?");
		$q->execute(Array($itemRequested));
		foreach($q as $r_) { $risposta .= '<h3>&deg; Data about [ '. $r_[0] . ' &mdash; ' . $r_[1] .' ]</h3>'; }
		$recordSetByName = $cn->prepare("SELECT id". $this->nameTable ."Detail, detailName, detailData FROM ". $this->nameTable ."Detail a INNER JOIN detailType d ON d.idDetailType = a.idDetailType AND a.Id". $this->nameTable ."=? ");
		$recordSetByName->execute(Array($itemRequested));
		$tData = $recordSetByName->fetchAll();
		if (sizeof($tData) == 0) {
			$risposta .= '<p>No one details<br />about this Course</p>'; 
		} else { 
			$risposta .= '<p>';
			foreach ($tData as $rowSet) {
				$risposta .= '[ <a href="'.$_SERVER['PHP_SELF'].'?_do_=95bed3f1cde70013dd8d971480967c0e&_id_='. $rowSet[0] .'&_who_='. $itemRequested .'" class="menu_item">&crarr;</a> | <a href="'.$_SERVER['PHP_SELF'].'?_do_=fad877d9009c84107223d7ed114c8803&_id_='. $rowSet[0] .'&_who_='. $itemRequested .'" class="menu_item">&times;</a> ] '.$rowSet[1] . ': ' .$rowSet[2] . '<br />';  
			} 
			$risposta .= '</p>';
		}	
		$cn = null;
		return $risposta . '</div>';		
	}

	function Edit( $CourseId ) {
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
		$rsEditable = $cn->prepare("SELECT nameCourse, semesterCourse FROM course WHERE id". $this->nameTable ."=:idp");
		$rsEditable->bindParam(':idp', $CourseId, PDO::PARAM_INT);
		$rsEditable->execute();
		$tEditable = $rsEditable->fetchAll();
		foreach($tEditable as $pEditData) {
			echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=da55578f890388a9a16262c208312deb&_id_='.$CourseId.'" class="menu_item">Back</a></div><div id="main"><h3>&deg; Setup the data about this Course into DB.</h3><form action="'. $_SERVER['PHP_SELF'] . '" method="POST">
	<input type="hidden" name="_do_" value="1d3e47d0a214f6bf19bc35515c2badd2" /><input type="hidden" name="_idPersonEditable_" value="'.$CourseId.'" />
	<table cellpadding="3" cellspacing="0" ><tr><td><a href="javascript: alert(\'Course Name - Non coloquial name, preferible Academic Name .\');" class="help_">?</a> | <label for="_nome_">Course Name:</label></td><td><input type="text" name="_nome_" value="'.$pEditData[0].'" tabindex="1" style="border: 1px solid gray; padding: 4px; font-family: Georgia; font-size: 16px;" size="30" /></td></tr>
	<tr><td><a href="javascript: alert(\'Course Year proposal exposed.\');" class="help_">?</a> | <label for="_year_semester_">Year/Semester :</label></td><td><input type="text" name="_year_semester_" tabindex="1" style="border: 1px solid gray; padding: 4px; font-family: Georgia; font-size: 16px;" size="20" value="'. $pEditData[1] .'"/></td></tr>
	<tr><td>&nbsp;</td><td><br /><input type="submit" value="&rarr; Update Data" tabindex="4" name="btn_updt_course_"/></td></tr></table></form>';
		}
		$cn = null; 
	}

	function Update() {
		if (isset($_POST['btn_updt_course_'])) {
			if (!empty($_POST['_nome_'])) {
				$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
				$yumi = $cn->prepare("UPDATE Course SET nameCourse=:name_,semesterCourse=:semc_, modified=:when_ WHERE idCourse=:id_");
				$yumi->bindParam(':name_', $_POST['_nome_']);
				$yumi->bindParam(':semc_', $_POST['_year_semester_']);
				$yumi->bindParam(':when_', date("Y-m-d H:i:s"));
				$yumi->bindParam(':id_', $_POST['_idPersonEditable_'], PDO::PARAM_INT);
				$yumi->execute();
				$cn = null;
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=600cd8677c87f63a0e019f32e657b4b4">';
			}
			else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=600cd8677c87f63a0e019f32e657b4b4">'; }
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=600cd8677c87f63a0e019f32e657b4b4">'; }
	}

	function Delete( $CourseId ) {
		echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=da55578f890388a9a16262c208312deb&_id_='. $CourseId .'" class="menu_item">Back/Cancel</a></div>';
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);
		$q = $cn->prepare("SELECT nameCourse, semesterCourse FROM course WHERE idCourse=?");
		$q->execute(Array($CourseId));
		foreach($q as $r_) { echo '<div id="main"><h3>&deg; Delete course: [ '. $r_[0] . ' &mdash; ' . $r_[1] .' ]</h3>'; }
		$cn = null;
		echo '<form action="'. $_SERVER['PHP_SELF'] .'" method="POST"><input type="hidden" name="_do_" value="8bffc1c1d76f9791024d3ce760e06f63" /><input type="hidden" name="_CourseId_" value="'. $CourseId .'" /><label for="_IdCategory_">Are you sure DELETE this Course ?</label> <p><input type="submit" value="Yes, kill data!" name="btn_perform_"/></p></form></div>';
	}

	function Remove() {
		if (isset($_POST['btn_perform_'])) {
			$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
			$t = $cn->prepare("DELETE FROM ". $this->nameTable ." WHERE id". $this->nameTable ."=:idp");
			$t->bindParam(':idp', $_POST['_CourseId_'], PDO::PARAM_INT); 
			$t->execute();
			$cn = null;	
			echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=600cd8677c87f63a0e019f32e657b4b4" />';
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=600cd8677c87f63a0e019f32e657b4b4" />'; }
	}

	function AddDetailType( $idAboutWhat ){
		echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=8e693377bb16761806688ad6f0799552&_id_='. $idAboutWhat .'" class="menu_item">Back/Cancel</a></div><div id="main"><h3>&deg; Append one detail about the Course</h3><form action="'.$_SERVER['PHP_SELF'].'" method="POST">
		<input type="hidden" name="_do_" value="89c090104f91856f08c1fafc54dbfbd5" />
		<input type="hidden" name="_aboutWho_" value="'.$idAboutWhat.'" />
		<table cellspacing="0" cellpadding="1">
		<tr><td><a href="javascript: alert(\'Append one detail about the Course that Want to SAVE.\');" class="help_">?</a> | <label for="_nameDetail_">Name of Detail to Save :</label></td></tr>
		<tr><td><input type="text" name="_nameDetail_" tabindex="1" style="border: 1px solid gray; padding: 4px; font-family: Georgia; font-size: 16px;" size="30" /></td></tr>
		<tr><td><input type="submit" value="[+] Add Data type" tabindex="2" name="btn_append_detail_" /></td></tr></table>
		</form></div>';
	}

	function SaveDetailType(){
		if (isset($_POST['btn_append_detail_'])) {
			if (!empty($_POST['_nameDetail_'])) {
				$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
				$rqApp = $cn->prepare("INSERT INTO detailType VALUES (null, :nDtl_, :usr_, :when_, :when_);");
				$rqApp->bindParam(':nDtl_', $_POST['_nameDetail_']);
				$rqApp->bindParam(':usr_', $_SESSION['userSessionValue']);
				$rqApp->bindParam(':when_', date("Y-m-d H:i:s"));
				$rqApp->execute();
				$cn = null;
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=8e693377bb16761806688ad6f0799552&_id_='. $_POST['_aboutWho_'] .'">';
			} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=8e693377bb16761806688ad6f0799552&_id_='. $_POST['_aboutWho_'] .'">'; }
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=8e693377bb16761806688ad6f0799552&_id_='. $_POST['_aboutWho_'] .'">'; }
	}

	function AddDetail( $AboutId ) {
		echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=da55578f890388a9a16262c208312deb&_id_='.$AboutId.'" class="menu_item">Back/Cancel</a></div>';
		
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);
		$q = $cn->prepare("SELECT nameCourse, semesterCourse FROM course WHERE idCourse=?");
		$q->execute(Array($AboutId));
		foreach($q as $r_) { echo '<div id="main"><h3>&deg; Append additional data to: [ '. $r_[0] . ' &mdash; ' . $r_[1] .' ]</h3>'; }
		
		echo '<form action="'.$_SERVER['PHP_SELF'].'" method="POST"><input type="hidden" name="_do_"  value="509c97efb7f2c69e1583b3321a61f101" /><input type="hidden" value="'.$AboutId.'" name="_AboutId_" />
			<table cellspacing="0" cellpadding="1">
			<tr><td><a href="javascript: alert(\'Choose first element of the pair (Category:Value).\');" class="help_">?</a> | <label for="_idDetailType_">Data type/Category : </label></td><td><select name="_idDetailType_">';
		foreach($cn->query("SELECT * FROM detailType") as $row_) {
			echo '<option value="'.$row_[0].'">'.$row_[1].'</option>';
		}
		echo '</select> [ <a href="'.$_SERVER['PHP_SELF'].'?_do_=3fad79aebf59308874b3935d20c22f79&_id_='.$AboutId.'" title="Append one category detail type">+</a> ]</td></tr><tr><td><a href="javascript: alert(\'Value - second element of the pair (Category/Value) .\');" class="help_">?</a> | <label for="_DataDetail_"> Enter the data value : </label></td>
			<td><textarea name="_DataDetail_" tabindex="2" style="border: 1px solid gray; padding: 4px; font-family: Georgia; font-size: 16px;" rows="5" cols="30"></textarea><br /><input type="checkbox" checked="checked" name="_isPublic_" />this detail is <em>PUBLIC</em></td><tr><td>&nbsp;</td><td><input type="submit" name="btn_add_detail_" value="[+] Append this data"/></td></tr></table></form></div>';
		$cn = null;		
	}

	function SaveDetail() {
		if (isset($_POST['btn_add_detail_'])) {
			if (!empty($_POST['_DataDetail_'])) {
				$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
				$rdAdd = $cn->prepare("INSERT INTO ". $this->nameTable ."Detail VALUES (null, :who, :detail, :dataDetail, :usr_, :when_, :when_, :public_);"); 
				$rdAdd->bindParam(':who', $_POST['_AboutId_']);
				$rdAdd->bindParam(':detail', $_POST['_idDetailType_']);
				$rdAdd->bindParam(':dataDetail', $_POST['_DataDetail_']);
				$rdAdd->bindParam(':usr_', $_SESSION['userSessionValue']);
				$rdAdd->bindParam(':when_', date("Y-m-d H:i:s"));
				if (!isset($_POST['_isPublic_'])) {
					$enablePublish = '0'; 
				} else { $enablePublish = '1'; }
				$rdAdd->bindParam(':public_', $enablePublish);
				$rdAdd->execute();
				$cn = null;
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=da55578f890388a9a16262c208312deb&_id_='. $_POST['_AboutId_'] .'">';
			} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=da55578f890388a9a16262c208312deb&_id_='. $_POST['_AboutId_'] .'">'; }
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=da55578f890388a9a16262c208312deb&_id_='. $_POST['_AboutId_'] .'">'; }	
	}
	
	function EditDetail( $DetailId, $CourseId ) {
		echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=da55578f890388a9a16262c208312deb&_id_='.$CourseId.'" class="menu_item">Back/Cancel Update</a></div><div id="main"><h3>&deg; Reset additional data</h3><form action="" method="POST">
		<input type="hidden" name="_do_" value="90858cb821e3b76622dfd9644b849967" />
		<input type="hidden" value="'.$DetailId.'" name="_idDetail_" />
		<input type="hidden" value="'.$CourseId.'" name="_idCourse_" />
		<table cellspacing="0" cellpadding="1"><tr><td><a href="javascript: alert(\'Choose first element of the pair (Category:Value).\');" class="help_">?</a> | <label for="_idDetailType_">Data type/Category : </label></td><td><select name="_idDetailType_">';
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
		$rsE = $cn->prepare("SELECT idDetailType, detailData, publishDetail FROM ". $this->nameTable ."Detail WHERE id". $this->nameTable ."Detail=:idpd AND id". $this->nameTable ."=:idp");
		$rsE->bindParam(':idpd', $DetailId);
		$rsE->bindParam(':idp', $CourseId);
		$rsE->execute();
		$t = $rsE->fetchAll();
		foreach($t as $dataRow) {
			foreach($cn->query("SELECT * FROM detailType") as $row_) {
				if ($row_[0] == $dataRow[0]) {
				echo '<option value="'.$row_[0].'" selected="selected">'. $row_[1].'</option>';
				} else {
				echo '<option value="'.$row_[0].'">'. $row_[1].'</option>';
				}
			}
		}
		echo '</select></td></tr><tr><td><a href="javascript: alert(\'Value - second element of the pair (Category/Value) .\');" class="help_">?</a> | <label for="_dataDetail_"> Enter the data value : </label></td>
			<td><textarea name="_dataDetail_" tabindex="2" style="border: 1px solid gray; padding: 4px; font-family: Georgia; font-size: 16px;" rows="5" cols="30">'. $dataRow[1]. '</textarea><br />';
		if ($dataRow[2] == true) { echo '<input type="checkbox" checked="checked" name="_isPublic_" />this detail is <em>PUBLIC</em></td>'; }
		else { echo '<input type="checkbox" name="_isPublic_" />this detail is <em>PUBLIC</em></td>'; } 
		echo '<tr><td>&nbsp;</td><td><input type="submit" name="btn_updt_detail_" value=" &rarr; Update this data"/></td></tr></table></form></div>';	
		$cn = null;
	}
	
	function UpdateDetail() {
		if (isset($_POST['btn_updt_detail_'])) {
			if (!empty($_POST['_dataDetail_'])) {
				$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
				$yumi = $cn->prepare("UPDATE ". $this->nameTable ."Detail SET idDetailType=:dato1, detailData=:dato2, idUser=:dato5, modified=:dato6, publishDetail=:dato7 WHERE id". $this->nameTable ."=:dato3 AND id". $this->nameTable ."Detail=:dato4");
				$yumi->bindParam(':dato1', $_POST['_idDetailType_']);
				$yumi->bindParam(':dato2', $_POST['_dataDetail_']);
				$yumi->bindParam(':dato3', $_POST['_idCourse_']);
				$yumi->bindParam(':dato4', $_POST['_idDetail_']);
				$yumi->bindParam(':dato5', $_SESSION['userSessionValue']);
				$yumi->bindParam(':dato6', date("Y-m-d H:i:s"));
				if (!isset($_POST['_isPublic_'])) {
					$enablePublish = '0'; 
				} else { $enablePublish = '1'; }
				$yumi->bindParam(':dato7', $enablePublish);
				$yumi->execute();
				$cn = null;
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=da55578f890388a9a16262c208312deb&_id_='.$_POST['_idCourse_'].'">';
	  		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=da55578f890388a9a16262c208312deb&_id_='.$_POST['_idCourse_'].'">'; }
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=da55578f890388a9a16262c208312deb&_id_='.$_POST['_idCourse_'].'">'; }	
	}

	function DeleteDetail( $DetailId, $AboutId ) {
		echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=da55578f890388a9a16262c208312deb&_id_='. $AboutId .'" class="menu_item">Back/Cancel delete</a></div><div id="main"><p><form action="'. $_SERVER['PHP_SELF'] .'" method="POST"><input type="hidden" name="_do_" value="45146fcb92e671f4d0b960b73521c14a" /><input type="hidden" name="_idDetail_" value="'. $DetailId .'" /><input type="hidden" name="_idAbout_" value="'. $AboutId .'" /><label for="_IdCategory_">Are you sure DELETE this Detail about the Course ?</label><br /><input type="submit" value="Yes, kill data!" name="btn_perform_remove_"/></form></p></div>';
	}
	
	function RemoveDetail() {
	if (isset($_POST['btn_perform_remove_'])) {
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
		$d = $cn->prepare("DELETE FROM courseDetail WHERE idCourseDetail=:idpd AND idCourse=:idp");
		$d->bindParam(':idpd', $_POST['_idDetail_'], PDO::PARAM_INT); 
		$d->bindParam(':idp', $_POST['_idAbout_'], PDO::PARAM_INT); 
		$d->execute();
		$cn = null;	
		echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=da55578f890388a9a16262c208312deb&_id_='.$_POST['_idAbout_'].'" />';
	} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=da55578f890388a9a16262c208312deb&_id_='.$_POST['_idAbout_'].'" />'; }	
	}
}
?>

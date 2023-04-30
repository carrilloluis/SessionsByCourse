<? 
class cLearningSession { 
	var $dsn_; 
	var $username_; 
	var $password_;
	
	function cLearningSession( $strDSN, $strUser, $strPwd ) 
	{ 
		$this->dsn_ = $strDSN; 
		$this->username_ = $strUser; 
		$this->password_ = $strPwd; 
	}

	function Add( $AboutId ) {
		return '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='. $AboutId .'" class="menu_item">Back/Cancel</a></div><div id="main"><h3>&deg; Enter <em>Name/content of Learning Session</em> into DB.</h3><form action="'. $_SERVER['PHP_SELF'] . '" method="POST"><input type="hidden" name="_do_" value="3cf213b659e10152f2b7912a5ae53e98" /><input type="hidden" name="_idCourse_" tabindex="1" value="'.  $AboutId .'"/>
		<table cellpadding="3" cellspacing="0" >
		<tr><td><a href="javascript: alert(\'Coloquial Name of the Learning Session or the Content of the Learning Session.\');" class="help_">?</a> | <label for="_nome_lsession_">Learning Session\'s name/content :</label></td><td><input type="text" name="_nome_lsession_" tabindex="1" style="border: 1px solid gray; padding: 4px; font-family: Georgia; font-size: 16px;" size="50" /></td></tr>
		<tr><td>&nbsp;</td><td><br /><input type="submit" value="[+] Add this Learning Session" tabindex="2" name="btn_add_lsession_"/></td></tr>
		</table></form></div>';
	}

	function Save()	{
		if (isset($_POST['btn_add_lsession_'])) {
			if (!empty($_POST['_nome_lsession_'])) {
				$cn = new PDO($this->dsn_, $this->username_, $this->password_);
				$rqst = $cn->prepare("INSERT INTO learningSession(idCourse, nameLearningSession, idUser, created, modified) VALUES (:idc_, :name, :who, :date_, :date_);");
				$rqst->bindParam(':idc_', $_POST['_idCourse_']);
				$rqst->bindParam(':name', $_POST['_nome_lsession_']);
				$rqst->bindParam(':who', $_SESSION['userSessionValue']);
				$rqst->bindParam(':date_', date("Y-m-d H:i:s"));
				$rqst->execute();
				$cn = null;
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='. $_POST['_idCourse_'] .'">';
			} else {
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='. $_POST['_idCourse_'] .'">';
			}
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='. $_POST['_idCourse_'] .'">'; 
		}
	}
	
	function ShowByCourse( $AboutId ) {
		echo '<div id="sidebar">&larr; <a href="'.$_SERVER['PHP_SELF'].'?_do_=da55578f890388a9a16262c208312deb&_id_='. $AboutId .'" class="menu_item">Back</a> | + <a href="'.$_SERVER['PHP_SELF'].'?_do_=35c5c9d038254a9c55cd9b1bc594fcce&_id_='. $AboutId .'" class="menu_item">Add learning session</a></div>';
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);
		$q = $cn->prepare("SELECT nameCourse, semesterCourse FROM Course WHERE idCourse=?");
		$q->execute(Array($AboutId));
		foreach($q as $r_) { 
			echo '<div id="main"><h3>&deg; Learning Session(s) from [ '. $r_[0] . ' &mdash; ' . $r_[1] .' ]</h3>'; //<a href="'. $_SERVER['PHP_SELF'] .'?_do_=f6d080b79b97552211585f5756823093"> + Add</a> | &rarr; <a href="'.$_SERVER['PHP_SELF'].'?_do_=21fd13a4800b50bd791a367ae49b4cd3" class="menu_item">Search Course</a>';
		}
		$request_ = $cn->query("SELECT idLearningSession, nameLearningSession FROM learningSession WHERE idCourse='". $AboutId ."' ORDER BY nameLearningSession");
		$rsTotal = $request_->fetchAll();
		$risposta = '';
		if (sizeof($rsTotal) > 0) {
			foreach ($rsTotal as $row) {
				$risposta .= '<a href="' . $_SERVER['PHP_SELF'] . '?_do_=c0e156cb568c48b49b8ec06bb49bc9ff&_id_='. $AboutId .'&_who_='.$row[0].'" class="menu_item">&crarr;</a> | <a href="' . $_SERVER['PHP_SELF'] . '?_do_=72e276904348275f00f5f46fe7f4c0be&_id_='. $AboutId .'&_who_='.$row[0].'" class="menu_item">+</a> | <a href="' . $_SERVER['PHP_SELF'] . '?_do_=4113b786b268368a414a49dbf2a95246&_id_='. $AboutId .'&_who_='.$row[0].'" class="menu_item">&times;</a> | <a href="' . $_SERVER['PHP_SELF'] . '?_do_=3cd120f70e0c70ae347e209f9fb47efa&_id_='. $AboutId .'&_who_='.$row[0].'" class="menu_item">&rarr;</a> | ' . $row[1] . " &mdash; " . '<br />';
			}
		} else {
				$risposta = '<p>No one register requested<br />0 results for your account</p>';
		} 
		$cn = null;
		return $risposta . '</div>';
	}	

	function Edit( $CourseId, $LearningSessionId ) {
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
		$rsEditable = $cn->prepare("SELECT nameLearningSession FROM learningSession WHERE idCourse=:idp AND idLearningSession=:idt");
		$rsEditable->bindParam(':idp', $CourseId, PDO::PARAM_INT);
		$rsEditable->bindParam(':idt', $LearningSessionId, PDO::PARAM_INT);
		$rsEditable->execute();
		$tEditable = $rsEditable->fetchAll();
		foreach($tEditable as $pEditData) {
			echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='.$CourseId.'" class="menu_item">Back</a></div><div id="main"><h3>&deg; Setup the data about this drink into DB.</h3><form action="'. $_SERVER['PHP_SELF'] . '" method="POST">
	<input type="hidden" name="_do_" value="1205cad2d473dc830cd734496705ac1c" />
	<input type="hidden" name="_idCourse_" value="'.$CourseId.'" />
	<input type="hidden" name="_idLearningSession_" value="'.$LearningSessionId.'" />
	<table cellpadding="3" cellspacing="0" >
	<tr><td><a href="javascript: alert(\'Learning Session Name - Non coloquial name, Academic Name .\');" class="help_">?</a> | <label for="_nome_">Learning Session Name:</label></td><td><input type="text" name="_nome_" value="'.$pEditData[0].'" tabindex="1" style="border: 1px solid gray; padding: 4px; font-family: Georgia; font-size: 16px;" size="30" /></td></tr>
	<tr><td>&nbsp;</td><td><br /><input type="submit" value="&rarr; Update Data" tabindex="4" name="btn_updt_lsession_" /></td></tr>
	</table></form>';
		}
		$cn = null; 
	}

	function Update() {
		if (isset($_POST['btn_updt_lsession_'])) {
			if (!empty($_POST['_nome_'])) {
				$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
				$yumi = $cn->prepare("UPDATE learningSession SET nameLearningSession=:name_, modified=:when_ WHERE idLearningSession=:id_ AND idCourse=:idc_");
				$yumi->bindParam(':name_', $_POST['_nome_']);
				$yumi->bindParam(':when_', date("Y-m-d H:i:s"));
				$yumi->bindParam(':id_', $_POST['_idLearningSession_'], PDO::PARAM_INT);
				$yumi->bindParam(':idc_', $_POST['_idCourse_'], PDO::PARAM_INT);
				$yumi->execute();
				$cn = null;
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='.$_POST['_idCourse_'].'">';
			} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='.$_POST['_idCourse_'].'">'; }
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='.$_POST['_idCourse_'].'">'; }
	}
	
	function Delete( $CourseId, $LearningSessionId ) {
		echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='. $CourseId .'" class="menu_item">Back/Cancel</a></div>
		<div id="main">
		<p><form action="'. $_SERVER['PHP_SELF'] .'" method="POST">
		<input type="hidden" name="_do_" value="8a1cc54dfd63dde6769f91017b3cb05b" />
		<input type="hidden" name="_CourseId_" value="'. $CourseId .'" />
		<input type="hidden" name="_LearningSessionId_" value="'. $LearningSessionId .'" />
		<label for="_IdCategory_">Are you sure DELETE this Learning Session ?</label><br />
		<input type="submit" value="Yes, kill data!" name="btn_perform_"/></form></p></div>';
	}

	function Remove() {
		if (isset($_POST['btn_perform_'])) {
			$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
			$t = $cn->prepare("DELETE FROM learningSession WHERE idLearningSession=:idp AND idCourse=:idc");
			$t->bindParam(':idc', $_POST['_CourseId_'], PDO::PARAM_INT);
			$t->bindParam(':idp', $_POST['_LearningSessionId_'], PDO::PARAM_INT);  
			$t->execute();
			$cn = null;	
			echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='.$_POST['_CourseId_'].'" />';
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='.$_POST['_CourseId_'].'" />'; }
	}


	function AddDetailType( $idAboutWhat, $LearningSessionId ){
		echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=72e276904348275f00f5f46fe7f4c0be&_id_='. $idAboutWhat .'&_who_='.$LearningSessionId.'" class="menu_item">Back/Cancel</a></div><div id="main"><h3>&deg; Append one detail about the learning session</h3><form action="'.$_SERVER['PHP_SELF'].'" method="POST">
		<input type="hidden" name="_do_" value="2fbba15e4f554a08a75f9a0795470f43" />
		<input type="hidden" name="_aboutWho_" value="'.$idAboutWhat.'" />
		<input type="hidden" name="_idWho_" value="'.$LearningSessionId.'" />
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
				$rqApp = $cn->prepare("INSERT INTO detailTypeLearningSession VALUES (null, :nDtl_, :usr_, :when_, :when_);");
				$rqApp->bindParam(':nDtl_', $_POST['_nameDetail_']);
				$rqApp->bindParam(':usr_', $_SESSION['userSessionValue']);
				$rqApp->bindParam(':when_', date("Y-m-d H:i:s"));
				$rqApp->execute();
				$cn = null;
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=72e276904348275f00f5f46fe7f4c0be&_id_='. $_POST['_aboutWho_'] .'&_who_='. $_POST['_idWho_'] .'">';
			} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=72e276904348275f00f5f46fe7f4c0be&_id_='. $_POST['_aboutWho_'] .'&_who_='. $_POST['_idWho_'] .'">'; }
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=72e276904348275f00f5f46fe7f4c0be&_id_='. $_POST['_aboutWho_'] .'&_who_='. $_POST['_idWho_'] .'">'; }
	}

	function AddDetail( $AboutId, $LearningSessionId ) {
		echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='.$AboutId.'" class="menu_item">Back/Cancel</a></div><div id="main"><h3>&deg; Append additional data</h3>
		<form action="'.$_SERVER['PHP_SELF'].'" method="POST">
		<input type="hidden" name="_do_"  value="38709a36419c658f13f36f296f27ee38" />
		<input type="hidden" value="'.$LearningSessionId.'" name="_AboutId_" />
		<input type="hidden" value="'.$AboutId.'" name="_CourseId_" />
			<table cellspacing="0" cellpadding="1">
			<tr><td><a href="javascript: alert(\'Choose first element of the pair (Category:Value).\');" class="help_">?</a> | <label for="_idDetailType_">Data type/Category : </label></td><td><select name="_idDetailType_">';
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
		foreach($cn->query("SELECT * FROM detailTypeLearningSession") as $row_) {
			echo '<option value="'.$row_[0].'">'.$row_[1].'</option>';
		}
		echo '</select> [ <a href="'.$_SERVER['PHP_SELF'].'?_do_=4551a4d210c346f843804be19267ced6&_id_='.$AboutId.'&_who_='. $LearningSessionId .'" title="Append one category detail type">+</a> ]</td></tr><tr><td><a href="javascript: alert(\'Value - second element of the pair (Category/Value) .\');" class="help_">?</a> | <label for="_DataDetail_"> Enter the data value : </label></td>
			<td><textarea name="_DataDetail_" tabindex="2" style="border: 1px solid gray; padding: 4px; font-family: Georgia; font-size: 16px;" rows="5" cols="30"></textarea><br /><input type="checkbox" checked="checked" name="_isPublic_" />this detail is <em>PUBLIC</em></td><tr><td>&nbsp;</td><td><input type="submit" name="btn_add_detail_" value="[+] Append this data"/></td></tr></table></form></div>';	
	}

	function SaveDetail() {
		if (isset($_POST['btn_add_detail_'])) {
			if (!empty($_POST['_DataDetail_'])) {
				$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
				$rdAdd = $cn->prepare("INSERT INTO learningSessionDetail VALUES (null, :who, :detail, :dataDetail, :usr_, :when_, :when_, :public_);"); 
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
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=3cd120f70e0c70ae347e209f9fb47efa&_id_='.$_POST['_CourseId_'].'&_who_='. $_POST['_AboutId_'] .'">';
			} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=3cd120f70e0c70ae347e209f9fb47efa&_id_='.$_POST['_CourseId_'].'&_who_='. $_POST['_AboutId_'] .'">'; }
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=3cd120f70e0c70ae347e209f9fb47efa&_id_='.$_POST['_CourseId_'].'&_who_='. $_POST['_AboutId_'] .'">'; }	
	}

	function BrowseData( $itemRequested, $learningSessionId ) {
		$risposta = '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=005238d3d30cb71e176a2eef8f1d2003&_id_='. $itemRequested .'" class="menu_item">Back</a> | + <a href="' . $_SERVER['PHP_SELF'] . '?_do_=72e276904348275f00f5f46fe7f4c0be&_id_='. $itemRequested .'&_who_='. $learningSessionId .'" class="menu_item">Add detail to this learning session</a></div><div id="main">';
		
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
		$q = $cn->prepare("SELECT nameLearningSession FROM learningSession WHERE idLearningSession=?");
		$q->execute(Array($learningSessionId));
		foreach($q as $r_) { $risposta .= '<h3>&deg; Data about the learning session called [ '. $r_[0] .' ]</h3>'; } 
		$recordSetByName = $cn->prepare("SELECT idLearningSessionDetail, detailName, detailData FROM learningSessionDetail a INNER JOIN detailTypeLearningSession d ON d.idDetailType = a.idDetailType AND a.idLearningSession=? ");
		$recordSetByName->execute(Array($learningSessionId));
		$tData = $recordSetByName->fetchAll();
		if (sizeof($tData) == 0) {
			$risposta .= '<p>No one details<br />about this Course</p>'; 
		} else { 
			$risposta .= '<p>';
			foreach ($tData as $rowSet) {
				$risposta .= ' <a href="'.$_SERVER['PHP_SELF'].'?_do_=3b34171940317b3d07adff10c9a21667&_id_='. $rowSet[0] .'&_course_='. $itemRequested .'&_who_='. $learningSessionId .'" class="menu_item">&crarr;</a> | <a href="'.$_SERVER['PHP_SELF'].'?_do_=949c487fc446ed6faecf50b2a4a07a2c&_id_='. $rowSet[0] .'&_course_='. $itemRequested .'&_who_='. $learningSessionId .'" class="menu_item">&times;</a> | '.$rowSet[1] . ': ' .$rowSet[2] . '<br />';  
			} 
			$risposta .= '</p>';
		}	
		$cn = null;
		return $risposta . '</div>';		
	}

	function EditDetail( $DetailId, $CourseId, $LearningSessionId ) {
		echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=3cd120f70e0c70ae347e209f9fb47efa&_id_='.$CourseId.'&_who_='. $LearningSessionId .'" class="menu_item">Back/Cancel Update</a></div><div id="main"><h3>&deg; Reset additional data</h3><form action="" method="POST">
		<input type="hidden" name="_do_" value="c40f41212ae9c9d7503af7def94bc827" />
		<input type="hidden" value="'.$DetailId.'" name="_idDetail_" />
		<input type="hidden" value="'.$CourseId.'" name="_idCourse_" />
		<input type="hidden" value="'.$LearningSessionId.'" name="_idLearningSession_" />
		<table cellspacing="0" cellpadding="1"><tr><td><a href="javascript: alert(\'Choose first element of the pair (Category:Value).\');" class="help_">?</a> | <label for="_idDetailType_">Data type/Category : </label></td><td><select name="_idDetailType_">';
		
		$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
		$rsE = $cn->prepare("SELECT idDetailType, detailData, publishDetail FROM learningSessionDetail WHERE idLearningSessionDetail=:idpd AND idlearningSession=:idp");
		$rsE->bindParam(':idpd', $DetailId);
		$rsE->bindParam(':idp', $LearningSessionId);
		$rsE->execute();
		$t = $rsE->fetchAll();
		foreach($t as $dataRow) {
			foreach($cn->query("SELECT * FROM detailTypeLearningSession") as $row_) {
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
				$yumi = $cn->prepare("UPDATE learningSessionDetail SET idDetailType=:dato1, detailData=:dato2, idUser=:dato5, modified=:dato6, publishDetail=:dato7 WHERE idLearningSession=:dato3 AND idlearningSessionDetail=:dato4");
				$yumi->bindParam(':dato1', $_POST['_idDetailType_']);
				$yumi->bindParam(':dato2', $_POST['_dataDetail_']);
				
				$yumi->bindParam(':dato3', $_POST['_idLearningSession_']);
				$yumi->bindParam(':dato4', $_POST['_idDetail_']);
				
				$yumi->bindParam(':dato5', $_SESSION['userSessionValue']);
				$yumi->bindParam(':dato6', date("Y-m-d H:i:s"));
				if (!isset($_POST['_isPublic_'])) {
					$enablePublish = '0'; 
				} else { $enablePublish = '1'; }
				$yumi->bindParam(':dato7', $enablePublish);
				$yumi->execute();
				$cn = null;
				echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=3cd120f70e0c70ae347e209f9fb47efa&_id_='.$_POST['_idCourse_'].'&_who_='.$_POST['_idLearningSession_'].'">';
	  		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=3cd120f70e0c70ae347e209f9fb47efa&_id_='.$_POST['_idCourse_'].'&_who_='.$_POST['_idLearningSession_'].'">'; }
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=3cd120f70e0c70ae347e209f9fb47efa&_id_='.$_POST['_idCourse_'].'&_who_='.$_POST['_idLearningSession_'].'">'; }	
	}

	function DeleteDetail( $DetailId, $AboutId, $LearningSessionId ) {
		echo '<div id="sidebar">&larr; <a href="'. $_SERVER['PHP_SELF'] .'?_do_=3cd120f70e0c70ae347e209f9fb47efa&_id_='. $AboutId .'&_who_='.$LearningSessionId.'" class="menu_item">Back/Cancel delete</a></div>';
		echo '<div id="main"><p><form action="'. $_SERVER['PHP_SELF'] .'" method="POST">
		<input type="hidden" name="_do_" value="6a1e8312c490bece24ed8ba9f07143ef" />
		<input type="hidden" name="_idDetail_" value="'. $DetailId .'" />
		<input type="hidden" name="_idCourse_" value="'. $AboutId .'" />
		<input type="hidden" name="_idAbout_" value="'. $LearningSessionId .'" />
		<label for="_IdCategory_">Are you sure DELETE this Detail about the Learning Session ?</label><br /><input type="submit" value="Yes, kill data!" name="btn_perform_remove_"/></form></p></div>';
	}

	function RemoveDetail() {
		if (isset($_POST['btn_perform_remove_'])) {
			$cn = new PDO($this->dsn_, $this->username_, $this->password_);  
			$d = $cn->prepare("DELETE FROM learningSessionDetail WHERE idLearningSession=:idpd AND idLearningSessionDetail=:idp");
			$d->bindParam(':idpd', $_POST['_idAbout_'], PDO::PARAM_INT);
			$d->bindParam(':idp', $_POST['_idDetail_'], PDO::PARAM_INT); 
			$d->execute();
			$cn = null;	
			echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=3cd120f70e0c70ae347e209f9fb47efa&_id_='.$_POST['_idCourse_'].'&_who_='.$_POST['_idAbout_'].'" />';
		} else { echo '<meta http-equiv="refresh" content="0;url=index.php?_do_=3cd120f70e0c70ae347e209f9fb47efa&_id_='.$_POST['_idCourse_'].'&_who_='.$_POST['_idAbout_'].'" />'; }	
	}

/*
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
	
	
	


	



 */
}
?>

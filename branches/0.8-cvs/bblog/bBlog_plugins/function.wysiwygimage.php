<?php
/**
 * function.wysiwygimage.php
 * <p>
 * @copyright Copyright (C) 2003  Eaden McKee <email@eadz.co.nz>
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 * @package bblog
 */
if(preg_match('/function.wysiwygimage.php$/',$_SERVER['REQUEST_URI'])){
	require_once(dirname(__FILE__).'/../config.php');
	if($bBlog->admin_logged_in()){
		wysiwygimage();
	}
}

function wysiwygimage(){
	global $bBlog,$smartyObj;
	if(empty($_FILES)) {
		$onload = "window.focus();init();";
	} elseif(preg_match('/\.[gif|jpg|jpeg|png]/', $_FILES['userfile']['name'])) {
		$ext = preg_match('/(\.[a-z]+)$/i', $_FILES['userfile']['name'], $matches);
		$filename = md5(microtime().rand()).$matches[1];
		$uploadfile = IMAGESUPLOADROOT . $filename;
		move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
		$onload = "insertImage('".IMAGESUPLOADURL.$filename."','".
		basename($_FILES['userfile']['name']).' ['.filesize($uploadfile).' bytes]'."');";
	}
	$bBlog->smartyObj->assign("onload",$onload);
	$smartyObj->template_dir = BBLOGROOT.'inc/admin_templates';
	$smartyObj->compile_id = 'admin';
	$bBlog->display("wysiwygimage.html");
}
?>

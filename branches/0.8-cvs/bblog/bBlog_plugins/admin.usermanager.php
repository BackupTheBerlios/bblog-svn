<?php
/**
 * admin.usermanager.php - administer users
 *
 * Adds / Edits / Deletes user accounts.
 *
 * @package bBlog
 * @author Eaden McKee - <email@eadz.co.nz> - last modified by $LastChangedBy: $
 * @version $Id: $
 * @copyright The bBlog Project, http://www.bblog.com/
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

 /**
  * @todo xushi: Should the secret answer be sha1() hashed for extra security?
  * @todo since it handles password resets & critical account information?
  */

 /**
  * @todo xushi - major security enhancement
  * @todo 
  * @todo Currently, anyone who logs in can see all other users details and can
  * @todo edit them at will. We need this to happen only if the user is an admin.
  * @todo 
  * @todo Add an extra 'isadmin (bool)' field in authors table.  So add
  * @todo this boolean, and if a user isnt an admin, then mask all other users but
  * @todo himself (select * from t_authors ... ... ... where nickname='user')
  */

function identify_admin_usermanager () {
    return array (
    'name'           =>'usermanager',
    'type'             =>'admin',
    'nicename'     =>'User manager',
    'description'   =>'Add, Remove and Edit users',
    'authors'        =>'Wouter Wolkers <wouter@wolkers.nl>',
    'licence'         =>'GPL',
    'template' 	=> 'usermanager_admin.html',
    'help'    	=> ''
  );
}

function admin_plugin_usermanager_run(&$bBlog) {
// Again, the plugin API needs work.
if(isset($_GET['userdo']))  { $userdo = $_GET['userdo']; }
elseif (isset($_POST['userdo'])) { $userdo = $_POST['userdo']; }
else { $userdo = ""; }

switch($userdo) {
    case "Delete" : // delete author
        if(is_numeric($_POST['userid'])) {
                $bBlog->query("DELETE FROM ".T_AUTHORS." WHERE id='".$_POST['userid']."'");
        }
        break;

    case "Add" :

                $user = array();
                $user['id'] = "-1";

        $bBlog->smartyObj->assign('user',$user);
        $bBlog->smartyObj->assign('showeditform',TRUE);

        break;

    case "addsave" :
        $nickname = my_addslashes($_POST['nickname']);
        $email  = my_addslashes($_POST['email']);
        $fullname = my_addslashes($_POST['fullname']);
        $password = sha1(my_addslashes($_POST['password']));
        $location = my_addslashes($_POST['location']);
        $ip_domain = my_addslashes($_POST['ip_domain']);
        $url = my_addslashes($_POST['url']);
        $icq = my_addslashes($_POST['icq']);
        $secretQuestion = my_addslashes($_POST['secretQuestion']);
        $secretAnswer = my_addslashes($_POST['secretAnswer']);
        $q = "insert into ".T_AUTHORS." (nickname, email, fullname, password, location, url, icq, secret_question, secret_answer) values ('$nickname', '$email', '$fullname', '$password', '$location', '$url', '$icq', '$secretQuestion', '$secretAnswer')";
        $bBlog->query($q);

        break;

    case "Edit" :

        if(!(is_numeric($_POST['userid']))) break;

        $user = $bBlog->get_results("SELECT * from ".T_AUTHORS." WHERE id='".$_POST['userid']."'", ARRAY_A);
        if(!$user) break;

        $bBlog->smartyObj->assign('user',$user[0]);
        $bBlog->smartyObj->assign('showeditform',TRUE);

        break;

    case "editsave" :
        if(!(is_numeric($_POST['userid']))) break;
        $oldpass = $bBlog->db->get_var("SELECT `password` FROM `".T_AUTHORS."` WHERE `id` = '".$_POST['userid']."'");
        $nickname = my_addslashes($_POST['nickname']);
        $email  = my_addslashes($_POST['email']);
        $fullname = my_addslashes($_POST['fullname']);
        $password = $_POST['password'] == '***OLDPASSWORD***' ? $oldpass : sha1($_POST['password']);
        $location = my_addslashes($_POST['location']);
        $ip_domain = my_addslashes($_POST['ip_domain']);
        $url = my_addslashes($_POST['url']);
        $icq = my_addslashes($_POST['icq']);
        $secretQuestion = my_addslashes($_POST['secretQuestion']);
        $secretAnswer = my_addslashes($_POST['secretAnswer']);
        $q = "update ".T_AUTHORS." set nickname='$nickname', email='$email', fullname='$fullname', password='$password', location='$location', url='$url', icq='$icq', secret_question='$secretQuestion', secret_answer='$secretAnswer', ip_domain='$ip_domain' where id='{$_POST['userid']}'";
        $bBlog->query($q);

        break;
    default : // show form
            break;
    }

	$bBlog->smartyObj->assign('message','Showing users. ');
	$bBlog->smartyObj->assign('users',$bBlog->get_results("SELECT * FROM `".T_AUTHORS."` order by nickname"));

	$posts_with_comments_q = "SELECT ".T_POSTS.".postid, ".T_POSTS.".title, count(*) c FROM ".T_COMMENTS.",  ".T_POSTS." 	WHERE ".T_POSTS.".postid = ".T_COMMENTS.".postid GROUP BY ".T_POSTS.".postid ORDER BY ".T_POSTS.".posttime DESC  LIMIT 0 , 30 ";
	$posts_with_comments = $bBlog->get_results($posts_with_comments_q,ARRAY_A);
	$bBlog->smartyObj->assign("postselect",$posts_with_comments);
}

?>

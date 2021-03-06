<?php
/**
 * modifier.simple.php
 *
 * @package bBlog
 * @author Eaden McKee - <email@eadz.co.nz> - last modified by $LastChangedBy: $
 * @version $Id: $
 * @copyright The bBlog Project, http://www.bblog.com/
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */


function identify_modifier_simple () {
    return array (
    'name'           =>'simple',
    'type'             =>'modifier',
    'nicename'     =>'Newlines and URLS',
    'description'   =>'Converts breaks to newlines and URLs into clickable links',
    'authors'        =>'Eaden McKee, phpBB Authors',
    'licence'         =>'GPL',
    'help'    	=> 'This is a simple modifier that simply converts new lines ( returns ) into html breaks, any urls ( e.g. http://www.bblog.com/ or www.bblog.com) into clickable links.'
  );
}
////
// !a simple modifier combining nl2br and make clickable
function smarty_modifier_simple ($body) {
    //Replaced all code with methods in StringHandler class

    $parts = explode(" ", $body);
    foreach($parts as $ind=>$line){
        $parts[$ind] = StringHandling::transformLinks($line);
    }
    $body = join(" ", $parts);
    return nl2br($body);
}


?>

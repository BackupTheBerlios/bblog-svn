<?php
/**
 * function.externalcontent.php - a Smarty function for displaying external content within bBlog
 *
 * @package bBlog
 * @author Paul Balogh <javaducky@gmail.com> - last modified by $LastChangedBy: $
 * @version $Id: $
 * @copyright The bBlog Project, http://www.bblog.com/
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

function identify_function_externalcontent() {
    $help = '<p>ExternalContent is a Smarty function to be used in templates.
             <p>Usage:
             <ul>
               <li>Return content from a "registered" provider<br>
               {externalcontent provider="some provider name"}</li>
             </ul>';

    return array (
        'name'        =>'externalcontent',
        'type'        =>'function',
        'nicename'    =>'External Content',
        'description' =>'Embeds markup from the specified content provider',
        'authors'     =>'Paul Balogh <javaducky@gmail.com>',
        'licence'     =>'GPL',
        'help'        => $help
    );

}

function smarty_function_externalcontent($params, &$smartyObj) {
    $bBlog = & $smartyObj->get_template_vars("bBlog_object");

    if (isset($params['provider'])) {
        $results = $bBlog->get_results("select url, enabled from ".T_EXT_CONTENT." where nicename='".$params['provider']."'");
        $content = $results[0]->url;
        $display = $results[0]->enabled;

/*
  PAUL:  I'm commenting this out due to Smarty issues encountered when using the plugin
         with a URL when the post uses the "Newlines & URLS", i.e. Simple, modifier.
         Leave out until a solid resolution can be determined.
    } elseif (isset($params['url'])) {
        $content = $params['url'];
        $display = 'true';
 */
    }
    // Check for enabled display
    if ($display == 'false') return '';

    if ($content == '') {
        return "<i>Content Missing!</i>";
    }

    $bytes = @readfile($content);
    if ($bytes = 0) return "<i>No Content!</i>";

    return '';
}

 ?>

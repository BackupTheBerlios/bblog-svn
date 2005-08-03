<?php
/**
 * modifier.locale.php - smarty modifier to set locale
 * <p>
 * @copyright Copyright (C) 2003  Eaden McKee <email@eadz.co.nz>
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 * @package bblog
 */
function smarty_modifier_locale($stream, $locale) {
  setlocale(LC_ALL, $locale);
  setlocale(LC_TIME, $locale);

  return $stream;
}

function identify_modifier_locale () {
  return array (
    'name'           =>'locale',
    'type'           =>'smarty_modifier',
    'nicename'       =>'Set Locale',
    'description'    =>'Set locale and return unmodified input data',
    'authors'         =>'Sebastian Werner',
    'licence'         =>'GPL'
  );
}

function bblog_modifier_locale_help () {
?>
<p>Set Locale</p>
<pre>
{$post.posttime|locale:"de_DE"|data_format:"date"}
</pre>
<?php
}
?>

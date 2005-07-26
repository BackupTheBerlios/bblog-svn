<?php

/*
    bBlog 0.8 - Rewrite Prototype
    Elie `woe` BLETON
*/

?>

<html>

<head>
<title>proto</title>
</head>

<body>
<h2>bBlog 0.8 prototype</h2>

<?php
    require('./lib/bBlog.class.php');
    $bBlog = new bBlog();
?>

<pre>
<b><u>Init:</u></b>

<?php
    $returnValue = $bBlog->LoadCodeFile('./ext/ext.foo.php');
    if ($returnValue != 0) { echo "<b>Error</b>: Unable to load extension foo (error code: $returnValue)"; }
?>

<b><u>Operation:</u></b>
<? 
    $bBlog->Call("extFoo", "Init"); 
    $bBlog->Call("extFoo", "Main");
?>

<b><u>Core dump:</u></b>
<?  $bBlog->DebugDump(); ?>

</pre>

</body>

</html>
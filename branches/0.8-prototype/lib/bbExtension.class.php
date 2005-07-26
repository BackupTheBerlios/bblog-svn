<?php

/*
    bBlog 0.8 - Rewrite Prototype
    Elie `woe` BLETON
    
    bbExtension class
    This is the base class for extensions that all extensions must inherit from in bBlog. 
    It provides necessary structure for the extension objects.
    
    Note: This would have got a 'real' use in PHP5, mostly with overloading and the 'abstract' keyword.
          In any case, this is left as 'required' for inheritance by other extensions.
          In the event of a code switch to PHP5, there won't be any need for massive structure reordering.   
*/

class bbExtension
{
    var $coreReference;    
}

class bbCommandExtension extends bbExtension 
{
    function Execute ()
    {
        // Everything is always fine here
        return 0;   
    }
}

class bbDataExtension extends bbExtension 
{
    // This is en empty data provider. It should be extended regarding to the kind of data that should be
    // provided. Should be flexible enough for any kind of data.
    // See dataFoo class.
}

?>
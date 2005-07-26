<?php
/*
    bBlog 0.8 - Rewrite Prototype
    Elie `woe` BLETON
    
    foo extension - Sample extension.
    This is to be registered as a new command stream, here the 'foo' stream.
    The 'foo' stream registers a data stream in order to provide content to bBlog.
    It's only built on basic_bar plugin to assure content handling.
*/

class extFoo extends bbCommandExtension 
{
    var $data;
        
    function GetExtensionInfo ()
    {
        // This function is built by bBlog specs.
        // If we were using PHP5 it won't be necessary, because the job could be done using
        // reflection classes. Anyway, we just can't.    
        
        $result = array(
            "ext_name"          =>  "extFoo", /* (1) */
            "ext_author"        =>  "Elie `woe` BLETON",
            "ext_authormail"    =>  "woe?berlios.de",
            "ext_GUID"          =>  "",
            "ext_revision_maj"  =>  1,
            "ext_revision_min"  =>  0,
            "ext_revision_bld"  =>  0
        );
        
        /* (1): This is provided in order for extended class based on Foo to provide another distinct
                commandstream if required. Reflexion classes couldn't provide a mean to do this without
                specifying a bit. */
    }
    
    /**
    * Init() - 2nd level constructor, to be called externally
    *
    * 
    */
    
    public function Init ()
    {
        // We just have to register a new dataExtension for the core in order to provide some "foo" content.
        $dataext = new dataFoo();
        $dataext->extFoo = $this;
        $this->coreReference->RegisterDataExtension($dataext);        
        return 0;
    }
    
    /**
    * Main() - Required method for all command streams.
    *
    * 
    */
    
    public function Main()
    {
        // Feeds the datastream with bullshit crap.
        $data .= 'Foo: ';
        for ($i = 0; $i < 16; $i++) 
            $data .= chr(rand(65, 90));
        $data .= chr(10) . chr(13);
        
        // Calls the parent
        parent::Execute();
    }
    
    
}

// Now our little custom data provider (which basicly reads the $data property of our extension)

class dataFoo extends bbDataExtension 
{
    var $extFoo;
    
    function Read()
    {
        return $extFoo->data;
    }
    
    function Write($newdata)
    {   
        $extFoo->data = $newdata;
    }
}

// Now this is important.
$object = new extFoo();

?>
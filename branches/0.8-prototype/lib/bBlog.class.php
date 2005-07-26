<?php

/*
    bBlog 0.8 - Rewrite Prototype
    Elie `woe` BLETON
    
    bBlog core class - Includes PluginAPI functionality
*/


// Call 'STATE' return values.
define('BB_CALLSTATE_OK', 0);
define('BB_CALLSTATE_UNKNOWN_EXTENSION', -1);
define('BB_CALLSTATE_UNKNOWN_METHOD', -2);
define('BB_CALLSTATE_INTERNAL_ERROR', -3);
define('BB_CALLSTATE_EXTERNAL_ERROR', -4);

define('BB_DEBUG', TRUE);

class bBlog
{
    var $commands = array();
    var $datastreams = array();
    
    function bBlog()
    {
        // PHP4 compatibility.
        $this->__construct();
        register_shutdown_function(array($this,"__destruct"));
    }
    
    function __construct()
    {
        // bBlog core constructor.
        // Loads all the base class FILES (that doesn't mean there is any instanciation or something)
        require('bbExtension.class.php');
    }
    
    function __destruct()
    {
        // bBlog core destructor.   
    }
    
    /**
    * RegisterCommandExtension() - Register a new command extension in the core, providing a distinct feature
    *
    * RegisterCommandExtension() is designed for internal use only by "LoadCodeFile". An extension object should
    * be registered ONCE (else this will cause useless dupes of extensions in memory).
    * There could be a use in registering extension A and extension A' (if A' extends A), if the two
    * distinct behavior are to be expected.    
    *
    * @param extension bbCommandExtension  Command extension object to register
    *
    */
    
    private function RegisterCommandExtension(bbCommandExtension $extension)
    {
        // An extension provides a new entry in the COMMAND array. It's an object encapsulating more or less
        // features. A plugin can simply EXTEND (OO speaking) an existing extension in order to provide
        // enhanced functionality, optionally with overriding, or can register a new plugin for this extension.
        $reflexion = new ReflectionObject($extension);
        if (defined('BB_DEBUG')) $this->DebugOutput("RegisterExtension(): Registering command extension ".$reflexion->getName());
        $extension->coreReference = $this;
        $this->commands[$reflexion->getName()] = $extension;               
    }
    
    private function RegisterPlugin()
    {
    }
    
    /**
    * RegisterDataExtension() - Register a new data extension in the core, providing a distinct set of data
    *
    * RegisterDataExtension() is designed for public use, since any extension/plugin can register new data extensions. 
    * An extension object should be registered ONCE (else this will cause useless dupes of extensions in memory).
    *
    * @param $extension bbDataExtension  Data extension object to register
    */
    
    public function RegisterDataExtension(bbDataExtension $extension)
    {
        $reflexion = new ReflectionObject($extension);
        if (defined('BB_DEBUG')) $this->DebugOutput("RegisterDataExtension(): Registering data extension ".$reflexion->getName());
        $object->coreReference = $this;
        $this->datastreams[] = $extension;
    }

    /**
    * DebugOutput() - Prints debug information in selected output.
    *
    * DebugOutput() is designed for internal use and for plugin developpers.
    * DebugOutput() doesn't check the existence of a DEBUG constant, it's up to the dev
    * to check such matter before calling DebugOutput.
    * Debug log is written depending on a few settings (see SettingsAPI documentation)
    *
    * @param $msg string  Debug message to print
    */
    
    function DebugOutput($msg)
    {
        // See how my version is complicated ? HAHA
        echo "$msg\r\n";   
    }
    
    /**
    * DebugDump() - Dumps core status to debug output
    *
    * The name explains it all.
    *   
    */
    
    function DebugDump()
    {
        $this->DebugOutput('<u>Datastreams:</u>');
        foreach ($this->datastreams as $key => $node)      
            $this->DebugOutput("[$key] $node");
            
        $this->DebugOutput('<u>Commands:</u>');
        foreach ($this->commands as $key => $node)      
            $this->DebugOutput("[$key] $node");          
    }
    
    /**
    * LoadCodeFile() - Loads an extension/plugin into the core.
    *
    * WARNING: This function is PHP5 only. A replacement could be designed for PHP4, requiring a bit of
    * of code magic regarding object reflexion.
    *
    * LoadCodeFile loads any PHP file into PHP via include().
    * It expects an $object variable to be set by the codefile in order to process with further registration.
    * Specifications for the different kind of $objects are available in the development wiki.  
    * -1 return code for "FILE NOT FOUND"
    * -2 return code for "INCORRECT/INEXISTANT OBJECT VARIABLE"
    *   
    * @param $filepath string  Path of the file to include
    */

    function LoadCodeFile($filepath)
    {
        if (defined('BB_DEBUG')) $this->DebugOutput("LoadCodeFile(): Attempting to load '$filepath'");
        
        clearstatcache();
        if (file_exists($filepath))
        {
            // Loads the plugin/extension
            include_once($filepath);
            if (!$object) return -2; // BAD BAD BAD BAD BAD !                      
            if (defined('BB_DEBUG')) $this->DebugOutput("LoadCodeFile(): Loaded file '$filepath'");
            
            // Create some reflexion objects in order to check the inheritance of the new $object
            $cmdexts = new ReflectionClass(bbCommandExtension);
            $dataexts = new ReflectionClass(bbDataExtension);         
            $reflexion = new ReflectionObject($object);
            
            // Switch to the appropriate register procedure.
            if ($reflexion->isSubclassOf($cmdexts))
                $this->RegisterCommandExtension($object);
            else if ($reflexion->isSubclassOf($dataexts))
                $this->RegisterDataExtension($object);
        }
        else return -1;       
    }   
    
    /**
    * ExtensionSanityCheck() - Checks that a candidate for registration answers to all requirements.
    *
    * ExtensionSanityCheck() performs a suite of tests on an object in order to check if all the required
    * methods/properties are properly named, and present in the code. This function shouldn't be called
    * each time an extension is loading, but preferably when detecting new extensions.
    *
    * ExtensionSanityCheck returns TRUE if everything's fine, FALSE if there is a problem.
    * Note: FALSE indicate that the extension is likely to cause fatal errors if run as is.
    *
    * @param $object   bbExtension Object to be checked
    */

    function ExtensionSanityCheck($object)
    {
    }
    
    /**
    * Call() - Executes a command extension code.
    *   
    * WARNING: This method heavily uses reflection classes in order to get the job done.
    * Some workaround must been thinked of in order to provide similar functionality for PHP4.
    *
    * NOTE: Another version using directly a ReflectionMethod object could be done.
    *
    * See BB_CALLSTATE_* for return values, the name are almost explicit.
    * BB_CALLSTATE_OK is never returned, return value for the given method is sent instead.
    *
    * @param $commandstream   string Extension name to consider
    * @param $methodname   string  Method name to call, 'main' by default.
    * @param $parameters mixed array    See invoke documentation
    */
    
    function Call($commandstream = "init", $methodname = "Main", $parameters = null)
    {        
        if (!$this->commands[$commandstream]) 
        {
            if (defined('BB_DEBUG')) $this->DebugOutput("Call(): $commandstream::$methodname <b>fails</b> BB_CALLSTATE_UNKNOWN_EXTENSION");
            return BB_CALLSTATE_UNKNOWN_EXTENSION; 
        }
        
        $reflectionObject = new ReflectionObject($this->commands[$commandstream]);
        $method = $reflectionObject->getMethod($methodname);             
        
        if (!$method)
        {
            if (defined('BB_DEBUG')) $this->DebugOutput("Call(): $commandstream::$methodname <b>fails</b> BB_CALLSTATE_UNKNOWN_METHOD");
            return BB_CALLSTATE_UNKNOWN_METHOD;    
        }
        
        $result = $method->invoke($this->commands[$commandstream], $parameters);
        
        if (defined('BB_DEBUG')) $this->DebugOutput("Call(): $commandstream::$methodname <b>ok</b>");  
        return $result; 
    }

}

?>
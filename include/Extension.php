<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

/**
 * Base class for all extensions
 *
 * Sets up the required stuff for the extension to be run on the system,
 * including all necessary hooks, autoloads, and version information.
 *
 * @version 1.0
 * @author stwalkerster
 */
abstract class Extension
{
    /**
     * Get information about the extension
     * 
     * @return array(
     *      name => "",
     *      gitviewer => "",
     *      description => "",
     *      "filepath" => dirname(__FILE__),
     *      );
     */
    public abstract function getExtensionInformation();
    
    /**
     * Returns relative file path of entered class
     * 
     */
    protected abstract function autoload($class);
    
    /**
     * Function where all hooks required by this extension can be registered.
     * 
     */
    protected abstract function registerHooks();

    protected function reallyAutoload($class)
    {
        $filepath = $this->autoload($class);
        if($filepath !== null)
        {
            $extinfo = $this->getExtensionInformation();
            $extensionFilePath = $extinfo['filepath'];
            if(file_exists($extensionFilePath . '/' . $filepath)) {
	            require_once($extensionFilePath . '/' . $filepath);
	            return;
            }
        }
    }
     
    public function setup()
    {
        spl_autoload_register(array($this, 'reallyAutoload'));
            
        $this->registerHooks();
    
    }
    
    	
	public function getGitInformation()
	{
        $dir = getcwd();
        $extinfo = $this->getExtensionInformation();
        chdir($extinfo['filepath']);
        return exec( 'git log -n1 --format=%H' );
        chdir($dir);
	}
	
    public function getGitTimeInformation()
	{
        $dir = getcwd();
        $extinfo = $this->getExtensionInformation();
        chdir($extinfo['filepath']);
        return exec( 'git log -n1 --pretty=format:"%cr"' );
        chdir($dir);
	}
    
	public function getAuthors()
	{
        $dir = getcwd();
        $extinfo = $this->getExtensionInformation();
        chdir($extinfo['filepath']);
        
		$authors = array();
		exec( 'git log --format="%cN <%cE>"', $authors );
		$authors = array_count_values( $authors );
		arsort( $authors );
        $authors = array_keys( $authors);
        
        chdir($dir);
        
		return $authors;
	}
}

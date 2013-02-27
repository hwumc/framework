<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Hooks
{
	// array of arrays (doubles as the globally-registered hooks).
	private static $registeredHandlers = array(
		"PostRunPage" => array(
			"PageLogin::getErrorDisplay",
			"PageLogin::loginModuleOverride",
		),
	
	);
	
	public static function register($name, $callback)
	{
		global $gLogger;
		if(!isset(self::$registeredHandlers[$name]))
		{
			$gLogger->log("Registering hook $name");
			self::$registeredHandlers[$name]=array();
		}
		$gLogger->log("Registering callback for hook $name");
		array_push(self::$registeredHandlers[$name],$callback);
	}

	/**
	 * Run hooks
	 * 
	 * First item in the parameters array is the return value of the last hook 
	 * to be run - should be used as the value to be processed by the hook
	 */
	public static function run($hook, $parameters = array(""))
	{
		global $gLogger;
		$gLogger->log("Checking hook registry for $hook");
		// is there a hook registered?
		if(isset(self::$registeredHandlers[$hook]))
		{
			$gLogger->log("Running callbacks for hook $hook");
		
			if(! (isset($parameters) && is_array($parameters)))
			{
				$gLogger->log("Setting default parameter list");
				$parameters = array("");
			}
			
			foreach(self::$registeredHandlers[$hook] as $func)
			{
				$gLogger->log("Running callback $func for hook $hook");
				//$gLogger->log("   params: " . print_r($parameters,true));
				$retval = call_user_func($func, $parameters);
				if($retval !== false)
				{
					$gLogger->log("	Callback successful.");
					$parameters[0] = $retval;
					//$gLogger->log("   new params: " . print_r($parameters,true));
				}
			}
		}
		else
		{
			$gLogger->log("Hook $hook is not registered!");
		}
		
		return $parameters[0];
	}
}
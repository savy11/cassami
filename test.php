<?php
//require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'autoload.php';
//$fn = new controllers\test;
//$fn->nested_filter_data();

class MyBaseClass
{
    protected static $friendClasses = array();

    public function __get($name)
    {
        if (
            // check if the caller's class is one of the friend classes
            ($trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)) &&
            (isset($trace[1]['class']) && in_array($trace[1]['class'], static::$friendClasses))
        ) {
            return $this->$name;
        } else {
            trigger_error('Member not available: ' . $name, E_USER_ERROR);
        }
    }
}

$s = new MyBaseClass();

echo $s->__get('MyBaseClass');

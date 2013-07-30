<?php
// Blame Scott Taylor for this -> http://scotty-t.com/2012/07/09/wp-you-oop/

abstract class BasePlugin {
    private static $instance = array();
    protected function __construct() {} 

    public static function get_instance( $c = '' ) {
        if ( empty( $c ) ) 
            die( 'Class name is required' );
        if ( !isset( self::$instance[$c] ) )           
            self::$instance[$c] = new $c();    

        return self::$instance[$c];
    }

    abstract public function init(); 
}
?>
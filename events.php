<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Events_In_events {

    protected $ci;

    public function __construct()
    {
        $this->ci = & get_instance();

        Events::register('admin_controller', array($this, 'run'));
    }

    public function run()
    {
        
    }

}

/* End of file events.php */
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @author 		Rigo B Castro
 * @website		http://imaginamos.com
 * @package             PyroCMS
 * @subpackage          In Events Module
 */
class Events_categories_m extends MY_Model {

    public function __construct()
    {
        parent::__construct();

        $this->_table = 'inevents_categories';
    }

}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @author 		Rigo B Castro
 * @author 		Jose L Fonseca
 * @website		http://imaginamos.com
 * @package             PyroCMS
 * @subpackage          In Events Module
 */
class Events_m extends MY_Model {

    public function __construct() {
        parent::__construct();

        $this->_table = 'inevents';
    }

    public function get_many_by($params = array()) {
        $categories_m = get_instance()->events_categories_m;

        if (!empty($params['start_date'])) {
            $this->db->where('start_date >=', $params['start_date']);
        }
        if (!empty($params['end_date'])) {
            $this->db->where('end_date <=', $params['end_date']);
        }
        if (!empty($params['category'])) {
            $this->db->select("{$this->_table}.*, B.*")->join("{$categories_m->table_name()} as B", "B.id = {$this->_table}.inevents_category_id");
        }

        return $this->get_all();
    }

    // ----------------------------------------------------------------------
    /**
     * Get next events for widget
     * @param type $limit
     * @return type
     */
    public function get_next_events($limit = null) {
        $params = array(
            'stream' => 'inevents',
            'namespace' => 'in_events',
            'limit' => $limit,
            'where' => 'start_date >= "'.date('Y-m-d').'"',
            'order_by' => 'start_date',
            'sort' => 'ASC'
        );
        $data = $this->streams->entries->get_entries($params);
        $return = array();
        foreach($data['entries'] as $event){
            $return[] = (object)array(
                'id' => $event['id'],
                'title' => $event['name'],
                'date' => format_date($event['start_date']),
                'image' => $this->get_image($event['id'])
            );
        }
        return $return;
    }
    /**
     * Get image for widget
     * @param type $id
     * @return string
     */
    public function get_image($id = null){
        $principal = "{{ url:site }}assets/static/evento-1.jpg";
        if(empty($id))
            return $principal;
        $q = $this->db->where('intranet_event_id',$id)->limit(1)->get('inevents_images');
        if($q->num_rows() === 0){
            return $principal;
        }else{
            $f = $q->row();
            $file = Files::get_file($f->file_id);
            return $file['data']->path;
        }
    }

}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class In_events extends Public_Controller {

    protected $namespace = 'in_events';
    protected $slug_stream = 'inevents';

    public function __construct()
    {
        parent::__construct();
    }

    public function index($event_id = null)
    {
        $url = site_url('in_events');
        $base_url = isset($_SERVER['QUERY_STRING']) ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;

        $params = array(
            'stream' => $this->slug_stream,
            'namespace' => $this->namespace,
            'paginate' => 'yes',
            'pag_segment' => '2',
            'limit' => 6,
            'offset' => 6
        );

        $events = $this->streams->entries->get_entries($params, array('suffix' => '?' . $_SERVER['QUERY_STRING'], 'first_url' => $base_url));
        $entries = $events['entries'];
        
        foreach ($entries as &$entry)
        {
            $entry['description_preview'] = character_limiter($entry['description'], 80);
        }

        return $this->template
                ->title($this->module_details['name'])
                ->set_stream($this->stream->stream_slug, $this->stream->stream_namespace)
                ->set('events', $entries)
                ->set('events_chunck', array_chunk($entries, 3))
                ->set('selected_event_id', $event_id)
                ->build('index');
    }

    // ----------------------------------------------------------------------
    
    public function event($event_id)
    {
        return $this->index($event_id);
    }

    // ----------------------------------------------------------------------
}

/* End of file controllers/forums.php */
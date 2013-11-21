<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Blog\Controllers
 */
class Admin extends Admin_Controller {

    protected $section = 'events';
    protected $namespace = 'in_events';
    protected $slug_stream = 'inevents';
    private $_tabs = array();

    public function __construct()
    {
        parent::__construct();

        $this->_tabs = array(
            array(
                'id' => 'info',
                'title' => 'Información',
                'fields' => array(
                    'name',
                    'description',
                    'inevents_category_id',
                    'start_date',
                    'end_date'
                )
            ),
            'ubication' => array(
                'id' => 'ubication',
                'title' => 'Ubicación',
                'fields' => array(
                    'place',
                    'show_map',
                    'map'
                )
            )
        );

        if (module_installed('entities'))
        {
            $this->_tabs['ubication']['fields'][] = 'building_id';
        }

        $this->template->append_js('module::admin.js');
    }

    /**
     * Show all created blog posts
     */
    public function index($page = 1)
    {

        $this->template
            ->append_css(array('module::fullcalendar.css'))
            ->append_js('module::fullcalendar.min.js');

        if (CURRENT_LANGUAGE == 'es')
        {
            $this->template->append_js('module::fullcalendar.locale.es.js');
        }

        $this->template->title($this->module_details['name'])
            ->build('admin/index');
    }

    // ----------------------------------------------------------------------

    public function table($page = 1)
    {
        $extra = array();

        $extra['title'] = "lang:{$this->namespace}:table_title";

        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => "admin/{$this->namespace}/edit/-entry_id-/{$page}",
            ),
            array(
                'label' => lang('global:delete'),
                'url' => "admin/{$this->namespace}/delete/-entry_id-/{$page}",
                'confirm' => true
            )
        );

        $extra['columns'] = array('name', 'inevents_category_id', 'start_date', 'end_date', 'place', 'building_id');
        $extra['no_entries_message'] = lang("{$this->namespace}:no_entries_message");

        $this->streams->cp->entries_table($this->slug_stream, $this->namespace, 5, "admin/{$this->namespace}/index", true, $extra);
    }

    // ----------------------------------------------------------------------


    public function load()
    {
        $param_start_date = $this->input->get('start');
        $param_end_date = $this->input->get('end');


        $events = $this->events_m->get_many_by(array(
            'start_date' => date('Y-m-d h:i:s', $param_start_date),
            'end_date' => date('Y-m-d h:i:s', $param_end_date),
            'category' => true
        ));

        $return = array();

        if (!empty($events))
        {
            foreach ($events as $event)
            {
                array_push($return, array(
                    'id' => $event->id,
                    'title' => $event->name,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                    'url' => site_url("admin/in_events/edit/{$event->id}"),
                    'color' => $event->category_background_color, // an option!
                    'textColor' => $event->category_font_color
                ));
            }
        }

        exit(json_encode($return));
    }

    // ----------------------------------------------------------------------

    public function create()
    {
        $extra = array(
            'return' => "admin/{$this->namespace}",
            'success_message' => lang("{$this->namespace}:create_success"),
            'failure_message' => lang("{$this->namespace}:create_failure"),
            'title' => lang("{$this->namespace}:new"),
        );



        return $this->streams->cp->entry_form($this->slug_stream, $this->namespace, 'new', null, true, $extra, null, $this->_tabs);
    }

    // ----------------------------------------------------------------------



    public function edit($id = null)
    {

        $this->_tabs['images'] = array(
            'id' => 'images',
            'title' => 'Imágenes',
            'fields' => array(
                'images'
            )
        );
        $this->_tabs['video'] = array(
            'id' => 'video',
            'title' => 'Video',
            'fields' => array(
                'video_url'
            )
        );

        $extra = array(
            'return' => "admin/{$this->namespace}",
            'success_message' => lang("{$this->namespace}:edit_success"),
            'failure_message' => lang("{$this->namespace}:edit_failure"),
            'title' => lang("{$this->namespace}:edit")
        );

        return $this->streams->cp->entry_form($this->slug_stream, $this->namespace, 'edit', $id, true, $extra, array(), $this->_tabs);
    }

    // ----------------------------------------------------------------------

    /**
     * Delete a FAQ entry
     * 
     * @param   int [$id] The id of FAQ to be deleted
     * @return  void
     */
    public function delete($id = null, $page = 1)
    {
        $this->streams->entries->delete_entry($id, $this->slug_stream, $this->namespace);
        $this->session->set_flashdata('error', lang("{$this->namespace}:deleted"));

        return redirect("admin/{$this->namespace}/index/{$page}");
    }

}

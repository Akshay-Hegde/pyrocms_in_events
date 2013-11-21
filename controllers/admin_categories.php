<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Document categories module
 *
 * @author 		Rigo B Castro
 * @website		http://imaginamos.com
 * @package             PyroCMS
 * @subpackage          Procedures Module
 */
class Admin_categories extends Admin_Controller {

    protected $section = 'categories';
    protected $namespace = 'in_events';
    protected $slug_name = 'categories';
    protected $data;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * List all Subcategories using Streams CP Driver
     *
     *
     * @access	public
     * @return	void
     */
    public function index($page = 1)
    {
        $extra = array();

        $extra['title'] = "lang:{$this->namespace}:{$this->section}:title";

        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => "admin/{$this->namespace}/{$this->section}/edit/-entry_id-/{$page}"
            ),
            array(
                'label' => lang('global:delete'),
                'url' => "admin/{$this->namespace}/{$this->section}/delete/-entry_id-/{$page}",
                'confirm' => true
            )
        );

        $extra['columns'] = array('category_name', 'category_background_color', 'category_font_color');

        return $this->streams->cp->entries_table($this->slug_name, $this->namespace, 5, "admin/{$this->namespace}/categories", true, $extra);
    }

    /**
     * Create a new document subcategory
     * 
     * @access  public
     * @return  void
     */
    public function create()
    {
        $extra['title'] = "lang:{$this->namespace}:{$this->section}:new";

        $extra = array(
            'return' => "admin/{$this->namespace}/{$this->section}/index",
            'success_message' => lang("{$this->namespace}:categories:create_success"),
            'failure_message' => lang("{$this->namespace}:categories:create_failure"),
            'title' => lang("{$this->namespace}:categories:new")
        );

        $this->streams->cp->entry_form($this->slug_name, $this->namespace, 'new', null, true, $extra);
    }

    /**
     * Edit a document categories
     *
     * @access	public
     * @return	void
     */
    public function edit($id = 0, $page = 1)
    {
        $this->template->title(lang("{$this->namespace}:{$this->section}:edit"));

        $extra = array(
            'return' => "admin/{$this->namespace}/{$this->section}/index/{$page}",
            'success_message' => lang("{$this->namespace}:{$this->section}:edit_success"),
            'failure_message' => lang("{$this->namespace}:{$this->section}:edit_failure"),
            'title' => lang("{$this->namespace}:{$this->section}:edit")
        );

        $this->streams->cp->entry_form($this->slug_name, $this->namespace, 'edit', $id, true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * Delete a categories
     * 
     * 
     * @access  public
     * @param   int $id The id of subcategory to be deleted
     * @return  void
     * @todo    This function does not currently hava any error checking.
     */
    public function delete($id = 0, $page = 1)
    {
        $model = $this->load->model('events_categories_m');

        $total = $model->count_all();

        if ($total > 1)
        {
            $this->streams->entries->delete_entry($id, $this->slug_name, $this->namespace);
            $this->session->set_flashdata('error', lang("{$this->namespace}:{$this->section}:deleted"));
        }
        else
        {
            $this->session->set_flashdata('error', lang("{$this->namespace}:{$this->section}:cannot_deleted"));
        }

        return redirect("admin/{$this->namespace}/{$this->section}/index/{$page}");
    }

}
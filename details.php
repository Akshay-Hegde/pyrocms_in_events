<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * In Events details module
 *
 * @author 	Rigo B Castro - Imaginamos Dev Team
 * @website	http://imaginamos.com
 * @package 	PyroCMS
 * @subpackage 	In Events Module
 */
class Module_in_events extends Module {

    public $version = '1.0.0';
    private $namespace = 'in_events';

    public function __construct()
    {
        $this->load->driver('Streams');
        $this->load->library('files/files');
    }

    // ----------------------------------------------------------------------

    public function info()
    {
        $sections['events'] = array(
            'name' => $this->namespace . ':title',
            'uri' => "admin/{$this->namespace}",
            'shortcuts' => array(
                'table' => array(
                    'name' => $this->namespace . ':show_table',
                    'uri' => "admin/{$this->namespace}/table",
                    'class' => 'show'
                ),
                'calendar' => array(
                    'name' => $this->namespace . ':show_calendar',
                    'uri' => "admin/{$this->namespace}/index",
                    'class' => 'show'
                ),
                'create' => array(
                    'name' => $this->namespace . ':new',
                    'uri' => "admin/{$this->namespace}/create",
                    'class' => 'add'
                )
            )
        );
        $sections['categories'] = array(
            'name' => $this->namespace . ':categories:title',
            'uri' => "admin/{$this->namespace}/categories/index",
            'shortcuts' => array(
                'create_category' => array(
                    'name' => $this->namespace . ':categories:new',
                    'uri' => "admin/{$this->namespace}/categories/create",
                    'class' => 'add'
                )
            )
        );

        return array(
            'name' => array(
                'en' => 'Events',
                'es' => 'Eventos'
            ),
            'description' => array(
                'en' => 'This is a PyroCMS module in events.',
                'es' => 'Módulo de Eventos (Intranet).'
            ),
            'frontend' => true,
            'backend' => true,
            'sections' => $sections
        );
    }

    public function install()
    {
        static $slug_stream = 'inevents';
        static $slug_stream_categories = 'categories';

        $this->_clear_info();
       

        if (!$this->streams->streams->add_stream("lang:{$this->namespace}:title", $slug_stream, $this->namespace))
            return false;

        if (!$categories_stream_id = $this->streams->streams->add_stream("lang:{$this->namespace}:categories:title", $slug_stream_categories, $this->namespace, $slug_stream . '_', null))
            return false;


        // ==== Create folder
        $folder = $this->file_folders_m->get_by('slug', 'eventos-intranet');

        if (empty($folder))
        {
            $folder = Files::create_folder(0, 'Eventos Intranet');

            if (!empty($folder['data']))
            {
                $folder = (object) $folder['data'];
            }
        }

        $images_folder = $this->file_folders_m->get_by('slug', 'eventos-intranet-imagenes');

        if (empty($images_folder))
        {
            $images_folder = Files::create_folder($folder->id, 'Eventos Intranet Imagenes');

            if (!empty($images_folder['data']))
            {
                $images_folder = (object) $images_folder['data'];
            }
        }

        // ==== Create Streams Fields

        $fields = array(
            // ---- Categories ----
            array(
                'name' => 'lang:name_label',
                'slug' => 'category_name',
                'namespace' => $this->namespace,
                'type' => 'text',
                'extra' => array('max_length' => 100),
                'assign' => $slug_stream_categories,
                'title_column' => true,
                'required' => true
            ),
            array(
                'name' => "lang:{$this->namespace}:{$slug_stream_categories}:background_color_label",
                'slug' => 'category_background_color',
                'namespace' => $this->namespace,
                'type' => 'colorpicker',
                'assign' => $slug_stream_categories,
                'extra' => array('default_color' => '#C7E1EF', 'options' => array('readonly' => true)),
                'required' => true
            ),
            array(
                'name' => "lang:{$this->namespace}:{$slug_stream_categories}:font_color_label",
                'slug' => 'category_font_color',
                'namespace' => $this->namespace,
                'type' => 'colorpicker',
                'assign' => $slug_stream_categories,
                'extra' => array('default_color' => '#FFF', 'options' => array('readonly' => true)),
                'required' => true
            ),
            // ---- Events ----
            array(
                'name' => 'lang:name_label',
                'slug' => 'name',
                'namespace' => $this->namespace,
                'type' => 'text',
                'extra' => array('max_length' => 200),
                'assign' => $slug_stream,
                'title_column' => true,
                'required' => true
            ),
            array(
                'name' => 'lang:desc_label',
                'slug' => 'description',
                'namespace' => $this->namespace,
                'type' => 'wysiwyg',
                'extra' => array('editor_type' => 'simple'),
                'assign' => $slug_stream,
                'required' => true
            ),
            array(
                'name' => "lang:{$this->namespace}:category_label",
                'slug' => $slug_stream . '_category_id',
                'namespace' => $this->namespace,
                'type' => 'relationship',
                'assign' => $slug_stream,
                'extra' => array('choose_stream' => $categories_stream_id, 'link_uri' => "admin/{$this->namespace}/{$slug_stream_categories}/edit/-id-/"),
                'required' => true
            ),
            array(
                'name' => "lang:{$this->namespace}:start_date_label",
                'slug' => 'start_date',
                'namespace' => $this->namespace,
                'type' => 'datetime',
                'assign' => $slug_stream,
                'extra' => array('use_time' => 'yes', 'storage' => 'datetime', 'input_type' => 'datepicker', 'start_date' => 0),
                'required' => true
            ),
            array(
                'name' => "lang:{$this->namespace}:end_date_label",
                'slug' => 'end_date',
                'namespace' => $this->namespace,
                'type' => 'datetime',
                'assign' => $slug_stream,
                'extra' => array('use_time' => 'yes', 'storage' => 'datetime', 'input_type' => 'datepicker', 'start_date' => 0),
                'required' => true
            ),
            array(
                'name' => "lang:{$this->namespace}:place_label",
                'slug' => 'place',
                'namespace' => $this->namespace,
                'type' => 'text',
                'assign' => $slug_stream,
                'required' => true
            ),
            array(
                'name' => "lang:{$this->namespace}:show_map_label",
                'slug' => 'show_map',
                'namespace' => $this->namespace,
                'type' => 'choice',
                'extra' => array(
                    'choice_data' => sprintf("1 : %s\n0 : %s", lang('global:yes'), lang('global:no')),
                    'choice_type' => 'radio',
                    'default_value' => 0
                ),
                'assign' => $slug_stream
            ),
            array(
                'name' => "lang:{$this->namespace}:multiple_images_label",
                'slug' => 'images',
                'namespace' => $this->namespace,
                'type' => 'multiple_images',
                'extra' => array(
                    'folder' => $images_folder->id,
                    'resource_id_column' => 'intranet_event_id',
                    'max_limit_images' => 5
                ),
                'assign' => $slug_stream
            ),
            array(
                'name' => "lang:{$this->namespace}:map_label",
                'slug' => 'map',
                'namespace' => $this->namespace,
                'type' => 'geocoder',
                'assign' => $slug_stream
            ),
            array(
                'name' => "lang:{$this->namespace}:video_url_label",
                'slug' => 'video_url',
                'namespace' => $this->namespace,
                'type' => 'video_url',
                'assign' => $slug_stream
            )
        );

        // Verify modules dependencies
        if (module_installed('entities'))
        {
            $fields[] = array(
                'name' => "lang:{$this->namespace}:building_label",
                'slug' => 'building_id',
                'namespace' => $this->namespace,
                'type' => 'building_entity',
                'assign' => $slug_stream
            );
        }

        if (!$this->streams->fields->add_fields($fields))
        {
            return false;
        }


        // ==== Update view options

        $this->streams->streams->update_stream($slug_stream_categories, $this->namespace, array(
            'view_options' => array(
                'category_name',
                'created'
            )
        ));

        // ==== Install tables

        $tables = array(
            $slug_stream . '_images' => array(
                'intranet_event_id' => array('type' => 'INT', 'constraint' => 9, 'null' => false, 'primary' => true),
                'file_id' => array('type' => 'CHAR', 'constraint' => 15, 'null' => false, 'primary' => true),
            )
        );

        if (!$this->install_tables($tables))
        {
            return false;
        }

        // ==== Relationships FOREIGN KEYS

        $foreign_key_format = "ALTER TABLE {$this->db->dbprefix}%s ADD CONSTRAINT %s FOREIGN KEY (%s) references {$this->db->dbprefix}%s(id) ON DELETE %s ON UPDATE %s";

        $foreigns_keys = array(
            // module => categories
            array(
                $slug_stream,
                'fk_in_events_category',
                $slug_stream . '_category_id',
                $slug_stream . '_' . $slug_stream_categories,
                'SET NULL',
                'CASCADE'
            ),
            // Images => module
            array(
                $slug_stream . '_images',
                'fk_in_events_images_event',
                'intranet_event_id',
                $slug_stream,
                'CASCADE',
                'CASCADE'
            ),
            // Images => files
            array(
                $slug_stream . '_images',
                'fk_in_events_images_file',
                'file_id',
                'files',
                'CASCADE',
                'CASCADE'
            ),
        );

        // Verify modules dependencies
        if (module_installed('entities'))
        {
            // module => building
            $foreigns_keys[] = array(
                $slug_stream,
                'fk_in_events_building',
                'building_id',
                'buildings',
                'SET NULL',
                'CASCADE'
            );
        }

        foreach ($foreigns_keys as $fk)
        {
            if (!$this->db->query(vsprintf($foreign_key_format, $fk)))
            {
                return false;
            }
        }

        // ==== First seeders

        $first_category_id = $this->db->insert($slug_stream . '_' . $slug_stream_categories, array(
            'category_name' => 'General',
            'created' => date('Y-m-d h:i:s'),
            'category_background_color' => '#3A87AD',
            'category_font_color' => '#FFF',
        ));

        if (empty($first_category_id))
        {
            return false;
        }


        return true;
    }

    public function uninstall()
    {
        // This is a core module, lets keep it around.
        return $this->_clear_info();
    }

    public function upgrade($old_version)
    {
        return true;
    }

    public function admin_menu(&$menu)
    {
        $menu['Eventos']['Tabla'] = 'admin/in_events/table';
        $menu['Eventos']['Calendario'] = 'admin/in_events';
        $menu['Eventos']['Categorías'] = 'admin/in_events/categories/index';
    }

    /**
     * Clear info of module (Reset)
     * 
     * @return boolean
     */
    private function _clear_info()
    {
        // Check foreign keys false
        $this->db->query('SET foreign_key_checks = 0;');

        $this->streams->utilities->remove_namespace($this->namespace);

        if ($this->db->table_exists('data_streams'))
        {
            $this->db->where('stream_namespace', $this->namespace)->delete('data_streams');
        };
        {
            // Check foreign keys true
            $this->dbforge->drop_table('inevents_images');

            $this->db->query('SET foreign_key_checks = 1;');
            return true;
        }
    }

}
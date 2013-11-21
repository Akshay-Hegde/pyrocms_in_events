<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mostrar Próximos eventos
 * @author Jose Luis Fonseca
 */
class Widget_Upcoming_events extends Widgets {

    /**
     * The translations for the widget title
     *
     * @var array
     */
    public $title = array(
        'en' => 'Upcoming events',
        'es' => 'Próximos eventos'
    );

    /**
     * The translations for the widget description
     *
     * @var array
     */
    public $description = array(
        'en' => 'Show the upcomming events',
        'es' => 'Muestra los proximos eventos'
    );

    /**
     * The author of the widget
     *
     * @var string
     */
    public $author = 'Jose Fonseca';

    /**
     * The author's website.
     * 
     * @var string 
     */
    public $website = 'http://josefonseca.me/';

    /**
     * The version of the widget
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Runs code and logic required to display the widget.
     */
    public function run() {
        $this->load->model('in_events/events_m');
        $q = $this->events_m->get_next_events(5);
        return array('events' => $q);
    }

}
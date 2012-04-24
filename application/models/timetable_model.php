<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Timetable_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function add_entry() {
        //process menu link
        // build array for the model


        $now = time();
        $datetime = $now;
        $form_data = array(
            'day' => $this->input->post('day'),
            'timetable_category' => $this->input->post('timetableCategory'),
            'from' => $this->input->post('startTime'),
            'to' => $this->input->post('endTime'),
            'class' => $this->input->post('className'),
            'instructor' => $this->input->post('instructor'),
            'level' => $this->input->post('level'),
            'where' => $this->input->post('location'),
            'date_added' => $datetime
        );
        $insert = $this->db->insert('timetables', $form_data);
        return $insert;
    }

    function get_timetable($category) {

        $this->db->order_by('from');
        $this->db->where('timetable_category', $category);
        $query = $this->db->get('timetables');
        if ($query->num_rows > 0) {
            return $query->result();
        }
    }

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('captcha_model');
                $this->load->model('menu_model');
	}

	public function index()
	{
echo "hello";
        }

        public function view_menu($id)
        {
             $data['menudata'] = $this->menu_model->get_menu($id);
             foreach($data['menudata'] as $row):

			$data['title'] = $row->menu_title;


		endforeach;
                $data['main_content'] = "menus/menu";
                $this->load->vars($data);
		$this->load->view('template/main');
        }
           public function list_menus()
        {
             


				$data['menu'] = 'Menu';


		$data['content'] = $this->content_model->get_content($data['menu']);

		foreach($data['content'] as $row):

			$data['title'] = $row->title;
			$data['sidebox'] = $row->sidebox;

		endforeach;
                $data['sidebar'] = "sidebox/side";
                $data['menus'] =	$this->menu_model->get_menus();
		$data['main_content'] = "menus/list_menus";
		$data['cats'] = $this->products_model->get_cats();
		$data['products'] = $this->products_model->get_all_products();
		$data['section2'] = 'global/links';
		if($this->session->flashdata('message'))
			{
				$data['message'] = $this->session->flashdata('message');
			}

		$data['slideshow'] = 'header/slideshow';
		$this->load->vars($data);
		$this->load->view('template/main');
        }

}
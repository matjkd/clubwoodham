<?php

class Admin extends MY_Controller {

    function __Construct() {
        parent::__Construct();
        $this->load->library(array('encrypt', 'form_validation'));
        $this->is_logged_in();
        $this->load->model('content_model');
        $this->load->model('captcha_model');
        $this->load->model('gallery_model');
        $this->load->model('menu_model');
        $this->load->library('s3');
    }

    function index() {
        $data['main_content'] = "admin/dashboard";
        $data['pages'] = $this->content_model->get_all_content();
        $data['seo_links'] = $this->content_model->get_seo_links();
        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function content() {
        if (($this->uri->segment(3)) < 1) {
            $id = 1;
        } else {
            $id = $this->uri->segment(3);
        }
        $data['content'] = $this->content_model->get_content($id);
        $data['captcha'] = $this->captcha_model->initiate_captcha();
        $data['main'] = "pages/dynamic";
        $data['edit'] = "admin/edit/$id";
        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function edit() {


        $id = $this->uri->segment(3);
        $data['menu'] = $id;
        $data['page'] = $id;
        $data['content'] = $this->content_model->get_content_id($id);
        $data['captcha'] = $this->captcha_model->initiate_captcha();
        $data['seo_links'] = $this->content_model->get_seo_links();
        $data['main_content'] = "admin/edit_content";



        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function edit_product() {


        $id = $this->uri->segment(3);
        $data['menu'] = $id;
        $data['page'] = $id;
        $data['content'] = $this->products_model->get_product($id);

        $data['main_content'] = "admin/edit_product";


        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function edit_content() {
        $this->form_validation->set_rules('title', 'title', 'trim');
        $this->form_validation->set_rules('menu', 'menu', 'trim|required');
        if ($this->form_validation->run() == FALSE) { // validation hasn'\t been passed
            echo "validation error";
        } else { // passed validation proceed to post success logic
            $id = $this->uri->segment(3);
            $this->content_model->edit_content($id);


            $this->upload_image($id);


            redirect("admin/edit/$id");
        }
    }

    function edit_product_content() {
        $id = $this->uri->segment(3);
        $this->products_model->edit_product($id);

        redirect("products/main/$id");
    }

    function create_news() {
        $data['page'] = "news";
        $data['content'] = $this->content_model->get_content('news');
        $data['main'] = "admin/create_news";
        $data['menu'] = $this->content_model->get_menus();
        $data['news'] = $this->news_model->list_news();
        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function editnews() {

        $id = $this->uri->segment(3);
        $data['page'] = 'news';
        $data['content'] = $this->content_model->get_content('news');
        $data['news'] = $this->news_model->get_news($id);

        $data['main'] = "admin/edit_news";
        $data['menu'] = $this->content_model->get_menus();
        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function edit_news() {
        $id = $this->uri->segment(3);
        $this->news_model->edit_news($id);
        redirect("admin/editnews/$id");
    }

    function editpro() {

        $id = $this->uri->segment(3);
        $data['page'] = 'professionals';
        $data['content'] = $this->content_model->get_content('professionals');
        $data['professional'] = $this->professionals_model->get_professional($id);
        foreach ($data['professional'] as $row):

            $data['practice'] = $this->professionals_model->practice_areas();
            $data['professional_id'] = $id;
        endforeach;

        $data['cases'] = $this->cases_model->list_cases();

        $data['assigned_cases'] = $this->cases_model->assigned_cases($id);

        $data['news'] = $this->news_model->list_news();
        $data['main'] = "admin/edit_user";
        $data['menu'] = $this->content_model->get_menus();

        $data['assigned_practices'] = $this->professionals_model->assigned_practice_areas($id);

        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function edit_pro() {
        $id = $this->uri->segment(3);
        $this->professionals_model->edit_pro($id);
        redirect("admin/editpro/$id");
    }

    function edit_practice() {

        $id = $this->uri->segment(3);
        $data['page'] = 'practices';
        $data['content'] = $this->content_model->get_content('news');
        $data['practice'] = $this->practice_model->get_practice($id);
        $data['news'] = $this->news_model->list_news();
        $data['main'] = "admin/edit_practice";
        $data['menu'] = $this->content_model->get_menus();
        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function edit_practice_submit() {
        $id = $this->uri->segment(3);
        $this->practice_model->edit_practice($id);
        redirect("admin/edit_practice/$id");
    }

    function upload_image($id = 0) {

        $this->gallery_model->do_upload();


        if (!empty($_FILES) && $_FILES['file']['error'] != 4) {

            $fileName = $_FILES['file']['name'];
            $tmpName = $_FILES['file']['tmp_name'];
            $filelocation = $fileName;

            $thefile = file_get_contents($tmpName, true);

            //add filename into database
            //get blog id
            if ($id == 0) {
                $blog_id = mysql_insert_id();
            } else {
                $blog_id = $id;
            }
            $this->content_model->add_file($fileName, $blog_id);
            //move the file

            if ($this->s3->putObject($thefile, "clubwoodham", $filelocation, S3:: ACL_PUBLIC_READ)) {
                //echo "We successfully uploaded your file.";
                $this->session->set_flashdata('message', 'News Added and file uploaded successfully');
            } else {
                //echo "Something went wrong while uploading your file... sorry.";
                $this->session->set_flashdata('message', 'News Added, but your file did not upload');
            }

            //uploadthumb
            $thumblocation = base_url() . 'images/temp/thumbs/' . $fileName;
            $newfilename = "thumb_" . $fileName;


            $newfile = file_get_contents($thumblocation, true);

            if ($this->s3->putObject($newfile, "clubwoodham", $newfilename, S3:: ACL_PUBLIC_READ)) {
                //echo "We successfully uploaded your file.";
                $this->session->set_flashdata('message', 'News Added and file uploaded successfully');
            } else {
                //echo "Something went wrong while uploading your file... sorry.";
                $this->session->set_flashdata('message', 'News Added, but your file did not upload');
            }
//delete files from server
            $this->gallery_path = "./images/temp";
            unlink($this->gallery_path . '/' . $fileName . '');
            unlink($this->gallery_path . '/thumbs/' . $fileName . '');
        } else {

            $this->session->set_flashdata('message', 'News Added');
        }
    }
    
    

    function submit_content() {
        $this->form_validation->set_rules('title', 'Title', 'trim|max_length[255]|required');
        $this->form_validation->set_rules('content', 'Content', 'trim');
        $this->form_validation->set_rules('menu', 'menu', 'trim');
        $this->form_validation->set_rules('category', 'Page Type', 'trim|max_length[11]');
        $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');

        if ($this->form_validation->run() == FALSE) { // validation hasn'\t been passed
            echo "validation error";
        } else { // passed validation proceed to post success logic
            if ($this->content_model->add_content()) { // the information has therefore been successfully saved in the db
                //now process the image
                // run insert model to write data to db
                //upload file
                //retrieve uploaded file
                $this->upload_image();




                redirect('/admin');   // or whatever logic needs to occur
            } else {
                echo 'An error occurred saving your information. Please try again later';
                // Or whatever error handling is necessary
            }
        }
    }

    function add_case() {
        $id = "selected_cases";

        $data['content'] = $this->content_model->get_content($id);
        $data['cases'] = $this->cases_model->list_cases();

        $data['menu'] = $this->content_model->get_menus();
        $data['main'] = "admin/add_case";
        $data['slideshow'] = "global/slideshow1";
        $data['news'] = $this->news_model->list_news();
        $data['sidebar'] = 'sidebar/news';
        $data['page'] = $id;
        $is_logged_in = $this->session->userdata('is_logged_in');

        if ($is_logged_in != NULL) {
            $data['edit'] = site_url("admin/edit/$id");
            $data['add'] = site_url("admin/add_case/");
        }




        $this->load->vars($data);
        $this->load->view('template');
    }

    function add_menu() {
        $data['main_content'] = "admin/add_menu";
        $data['cats'] = $this->products_model->get_cats();
        $data['products'] = $this->products_model->get_all_products();
        $data['section2'] = 'global/links';
        if ($this->session->flashdata('message')) {
            $data['message'] = $this->session->flashdata('message');
        }

        $data['slideshow'] = 'header/slideshow';
        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function view_menus() {
        $data['main_content'] = "admin/view_menus";
        $data['page'] = 'practices';
        $data['menus'] = $this->menu_model->get_menus();

        $data['slideshow'] = 'header/slideshow';
        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function add_new_menu() {
        $this->menu_model->add_menu();
        return;
    }

    function update_menu() {
        echo $this->input->post('published');
        $this->menu_model->update_menu();
        //  return;
    }

    function edit_menu($id) {
        $data['main_content'] = "admin/edit_menu";
        $data['menudata'] = $this->menu_model->get_menu($id);
        $data['cats'] = $this->products_model->get_cats();
        $data['products'] = $this->products_model->get_all_products();
        $data['section2'] = 'global/links';
        if ($this->session->flashdata('message')) {
            $data['message'] = $this->session->flashdata('message');
        }

        $data['slideshow'] = 'header/slideshow';
        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function assign_practice() {
        $segment_active = $this->uri->segment(3);
        if ($segment_active == NULL) {
            redirect('welcome', 'refresh');
        } else {
            $this->professionals_model->assign_practice($segment_active);

            redirect('admin/editpro/' . $segment_active . '');
        }
    }

    function assign_case() {
        $segment_active = $this->uri->segment(3);
        if ($segment_active == NULL) {
            redirect('welcome', 'refresh');
        } else {
            $this->cases_model->assign_case($segment_active);

            redirect('admin/editpro/' . $segment_active . '');
        }
    }

    function delete_assigned_cases($id) {

        $data['case_id'] = $this->cases_model->delete_assigned_case($id);
        foreach ($data['case_id'] as $key => $row):
            $professional = $row['professional_id'];
        endforeach;

        redirect('admin/editpro/' . $professional . '', 'refresh');
    }

    function delete_assigned_practice($id) {

        $data['practice_id'] = $this->professionals_model->delete_assigned_practice($id);
        foreach ($data['practice_id'] as $key => $row):
            $professional = $row['professional_id'];
        endforeach;

        redirect('admin/editpro/' . $professional . '', 'refresh');
    }

    function do_upload() {
        if (isset($_FILES['file'])) {
            $file = read_file($_FILES['file']['tmp_name']);
            $name = basename($_FILES['file']['name']);
            $name = str_replace(' ', '_', $name);
            $name = str_replace(',', '', $name);
            write_file('uploads/' . $name, $file);

            $this->cases_model->add($name);
            redirect('cases/view');
        }

        else
            $this->load->view('upload');
    }

    function add_content() {
        $data['main_content'] = "admin/add_content";
        $data['pages'] = $this->content_model->get_all_content();

        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function add_seo_content() {
        $data['slideshowtoggle'] = "off";
        $data['main_content'] = "admin/add_content";
        $data['seo_links'] = $this->content_model->get_seo_links();
        $data['captcha'] = $this->captcha_model->initiate_captcha();
        $data['pages'] = $this->content_model->get_all_content();
        $data['category'] = "seo";
        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function add_testimonial_content() {
        $data['slideshowtoggle'] = "off";
        $data['main_content'] = "admin/add_content";
        $data['seo_links'] = $this->content_model->get_seo_links();
        $data['captcha'] = $this->captcha_model->initiate_captcha();
        $data['pages'] = $this->content_model->get_all_content();
        $data['category'] = "testimonial";
        $this->load->vars($data);
        $this->load->view('template/main');
    }

    function add_gallery_content() {
        $data['slideshowtoggle'] = "off";
        $data['main_content'] = "admin/add_content";
        $data['seo_links'] = $this->content_model->get_seo_links();
        $data['captcha'] = $this->captcha_model->initiate_captcha();
        $data['pages'] = $this->content_model->get_all_content();
        $data['category'] = "gallery";
        $this->load->vars($data);
        $this->load->view('template/main');
    }
    
    function sort_gallery() {
          $pages = $this->input->post('pages');
        parse_str($pages, $pageOrder);

        // list id is retrieved from the ID on the sortable list
        foreach ($pageOrder['gallery'] as $key => $value):
            mysql_query("UPDATE ignite_content SET `order` = '$key' WHERE `content_id` = '$value'") or die(mysql_error());


        //$this->db->update('practice_area_links', $pro_update);
        endforeach;
    }

    function is_logged_in() {
        $is_logged_in = $this->session->userdata('is_logged_in');
        $role = $this->session->userdata('role');
        if (!isset($is_logged_in) || $role != 1) {
            $data['message'] = "You don't have permission";
            redirect('welcome/login', 'refresh');
        }
    }

}
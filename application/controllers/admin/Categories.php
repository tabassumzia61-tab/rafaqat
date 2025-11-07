<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'particles');
        $this->session->set_userdata('sub_menu', 'admin/categories.html');

        $data["page_title"]     = 'Add Category';
        $data["name"]           = "";
        $data["code"]           = "";
        $data["slug"]           = "";
        $data["parent"]         = "";
        $data["description"]    = "";
        
        $categorieslist = $this->categories_model->getParentCategories();
        $data["categorieslist"] = $categorieslist;

        // --- SEARCH + PAGINATION ---
        $search = $this->input->get('search', true); //trim(); // safe get
        $page   = (int) $this->input->get('per_page') ?? 0; // page offset (query-string)

        $limit  = 10; // items per page - change as needed
        $offset = ($page && $page > 0) ? $page : 0;

        $total_rows = $this->categories_model->count_categories($search);
        $resultlist = $this->categories_model->get_categories($limit, $offset, $search);

        // Build pagination config
        $config = array();
        $config['base_url']             = base_url('admin/categories.html'); 
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $limit;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'per_page';

        // Keep the search parameter in the pagination links
        if (!empty($search))
        {
            $config['suffix']       = '&search=' . urlencode($search);
            $config['first_url']    = $config['base_url'] . '?search=' . urlencode($search);
        }

        // Basic bootstrap 4/5 friendly pagination markup
        $config['full_tag_open']    = '<nav aria-label="Page navigation"><ul class="pagination justify-content-end">';
        $config['full_tag_close']   = '</ul></nav>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '</span></li>';
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['first_tag_open']   = '<li class="page-item">';
        $config['first_tag_close']  = '</li>';
        $config['last_tag_open']    = '<li class="page-item">';
        $config['last_tag_close']   = '</li>';
        $config['attributes']       = array('class' => 'page-link');
    
        $this->load->library('pagination');
        $this->pagination->initialize($config);
         
        $data['resultlist']         = $resultlist;
        $data['pagination_links']   = $this->pagination->create_links();
        $data['search']             = $search;
        $data['total_rows']         = $total_rows;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/categories/categorieslist', $data);
        $this->load->view('layout/footer', $data);
    }


    /*public function index()
    {
        $this->session->set_userdata('top_menu', 'particles');
        $this->session->set_userdata('sub_menu', 'admin/categories');

        $categorieslist = $this->categories_model->getParentCategories();
        $resultlist     = $this->categories_model->get();


        $data['page_title']     = 'Add Category';
        $data["name"]           = "";
        $data["code"]           = "";
        $data["slug"]           = "";
        $data["parent"]         = "";
        $data["description"]    = "";
        $data["categorieslist"] = $categorieslist;
        $data["resultlist"]     = $resultlist;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/categories/categorieslist', $data);
        $this->load->view('layout/footer', $data);
    }*/

    function create()
    {
        if (!$this->rbac->hasPrivilege('categories', 'can_add')) {
            access_denied();
        }
        
        $categorieslist         = $this->categories_model->getParentCategories();
        $resultlist             = $this->categories_model->get();

        $data['page_title']     = 'Add Category';
        $data["categorieslist"] = $categorieslist;
        $data["resultlist"]     = $resultlist;
        $data["name"]           = "";
        $data["slug"]           = "";
        $data["parent"]         = "";
        $data["description"]    = "";

        $this->form_validation->set_rules('name', 'Name', array('required',array('check_exists', array($this->categories_model, 'check_exists'))));
        //$this->form_validation->set_rules('code', $this->lang->line('category').' '.$this->lang->line('code'), 'trim|is_unique[categories.code]|required');
        //$this->form_validation->set_rules('slug', $this->lang->line('slug'), 'required|is_unique[categories.slug]|alpha_dash');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/categories/categorieslist', $data);
            $this->load->view('layout/footer', $data);
        }
        else
        {
            $img_name   = $this->media_storage->fileupload("documents", "./uploads/categories/");
            $code       = $this->generateNextCode();
            
            $data       = array(
                'name'          => $this->input->post('name'),
                'slug'          => $this->input->post('slug'),
                'code'          => $code,                           //$this->input->post('code'),
                'parent_id'     => $this->input->post('parent'),
                'image'         => $img_name,
                'description'   => $this->input->post('description'),
                'is_active'     => 'yes'
            );

            $this->categories_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Category added successfully</div>');
            redirect('admin/categories.html');
        }
    }

    public function generateNextCode()
    {
        $prefix = "IC_";

        $this->db->select('code');
        $this->db->from('categories');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);

        $query  = $this->db->get();
        $row    = $query->row();

        if ($row && preg_match('/IC_(\d+)/', $row->code, $matches))
        {
            $num = (int)$matches[1] + 1;
        } else {
            $num = 1; // first time
        }

        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    function edit()
    {
        $id = $this->input->get('id');
        if (!$this->rbac->hasPrivilege('categories', 'can_edit')) {
            access_denied();
        }

        $categorieslist = $this->categories_model->getParentCategories();
        $resultlist     = $this->categories_model->get();
        $result         = $this->categories_model->get($id);
        
        if(!$result )
        {
            $this->session->set_flashdata('msg', '<div class="alert alert-ganger text-left">Categorie item not found</div>');
            redirect('admin/categories.html');
        }

        $data["page_title"]     = 'Add Category';


        // --- SEARCH + PAGINATION ---
        $search = $this->input->get('search', true); //trim(); // safe get
        $page   = (int) $this->input->get('per_page') ?? 0; // page offset (query-string)

        $limit  = 10; // items per page - change as needed
        $offset = ($page && $page > 0) ? $page : 0;

        $total_rows = $this->categories_model->count_categories($search);
        $resultlist = $this->categories_model->get_categories($limit, $offset, $search);

        // Build pagination config
        $config = array();
        $config['base_url']             = base_url('admin/categories.html'); 
        $config['total_rows']           = $total_rows;
        $config['per_page']             = $limit;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'per_page';

        // Keep the search parameter in the pagination links
        if (!empty($search))
        {
            $config['suffix']       = '&search=' . urlencode($search);
            $config['first_url']    = $config['base_url'] . '?search=' . urlencode($search);
        }

        // Basic bootstrap 4/5 friendly pagination markup
        $config['full_tag_open']    = '<nav aria-label="Page navigation"><ul class="pagination justify-content-end">';
        $config['full_tag_close']   = '</ul></nav>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '</span></li>';
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['first_tag_open']   = '<li class="page-item">';
        $config['first_tag_close']  = '</li>';
        $config['last_tag_open']    = '<li class="page-item">';
        $config['last_tag_close']   = '</li>';
        $config['attributes']       = array('class' => 'page-link');
    
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data['pagination_links']   = $this->pagination->create_links();
        $data['search']             = $search;
        $data['total_rows']         = $total_rows;

        $data["categorieslist"] = $categorieslist;
        $data["resultlist"]     = $resultlist;
        $data['id']             = $id;
        $data["result"]         = $result;
        $data["name"]           = $result["name"];
        $data["slug"]           = $result["slug"];
        $data["parent"]         = $result["parent_id"];
        $data["description"]    = $result["description"];

        $this->form_validation->set_rules('name', 'Name', array('required', array('check_exists', array($this->categories_model, 'check_exists')) ));
        //$this->form_validation->set_rules('code', $this->lang->line('category').' '.$this->lang->line('code'), 'trim|required');
        
        // if ($this->input->post('code') != $result["code"]) {
        //     $this->form_validation->set_rules('code', $this->lang->line('category').' '.$this->lang->line('code'), 'trim|is_unique[categories.code]|required');
        // }
        
        //$this->form_validation->set_rules('slug', $this->lang->line('slug'), 'required|alpha_dash');

        // if ($this->input->post('slug') != $result["slug"])
        // {
        //     $this->form_validation->set_rules('slug', $this->lang->line('slug'), 'required|is_unique[categories.slug]|alpha_dash');
        // }

        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        
        if ($this->form_validation->run() == FALSE)
            {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/categories/categorieslist', $data);
            $this->load->view('layout/footer', $data);
        }
        else
        {
            $data = array(
                'id'            => $id,
                'name'          => $this->input->post('name'),
                'slug'          => $this->input->post('slug'),
                //'code'          => $this->input->post('code'),
                'parent_id'     => $this->input->post('parent'),
                'description'   => $this->input->post('description'),
                'is_active'     => 'yes'
            );

            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name'])))
            {
                $img_name   = $this->media_storage->fileupload("documents", "./uploads/categories/");
            }
            else
            {
                $img_name   = $result['image'];
            }

            $data['image']  = $img_name;

            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name'])))
            {
                $this->media_storage->filedelete($result['image'], "uploads/categories");
            }

            $this->categories_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Category updated successfully</div>');
            redirect('admin/categories/index');
        }
    }

    function delete()
    {
        $id = $this->input->get('id');

        if (!$this->rbac->hasPrivilege('categories', 'can_delete')) {
            access_denied();
        }

        if (!empty($id)) {
            $this->categories_model->delete($id);
        }

        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Category Deleted successfully</div>');
        redirect('admin/categories/index');
    }

    public function handle_upload(){
        $image_validate = $this->config->item('file_validate');
        
        $result         = $this->filetype_model->get();
        if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {
            $file_type         = $_FILES["documents"]['type'];
            $file_size         = $_FILES["documents"]["size"];
            $file_name         = $_FILES["documents"]["name"];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES['documents']['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                    return false;
                }
                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', "File Type / Extension Error Uploading  Image");
                return false;
            }

            return true;
        }
        return true;
    }

    public function slug(){
        echo $this->customlib->slug($this->input->get('title', true), $this->input->get('type', true));
        exit();
    }

}

?>
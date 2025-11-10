<?php
class Units extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('media_storage');
    }

    // application/controllers/Units.php (inside class Units extends Admin_Controller)

public function index()
{
    $this->session->set_userdata('top_menu', 'particles');
    $this->session->set_userdata('sub_menu', 'admin/units.html');

    $data['title']              = 'Add Unit';
    $data["name"]               = "";
    $data["short_name"]         = "";
    $data["code"]               = "";
    $data["primary_unit"]       = "";
    $data["is_active"]          = "";
    $data["description"]        = "";

    // parent list for the create/edit form (unchanged)
    $unitslist  = $this->units_model->getParentunits();
    $data["unitslist"] = $unitslist;

    // --- SEARCH + PAGINATION ---
    $search = $this->input->get('search', true); //trim(); // safe get
    $page   = (int) $this->input->get('per_page') ?? 0; // page offset (query-string)

    $limit  = 10; // items per page - change as needed
    $offset = ($page && $page > 0) ? $page : 0;

    $total_rows = $this->units_model->count_units($search);
    $units     = $this->units_model->get_units($limit, $offset, $search);

    // Build pagination config
    $config = array();
    $config['base_url']             = base_url('admin/units.html'); 
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
    
    $data['resultlist']         = $units;
    $data['pagination_links']   = $this->pagination->create_links();
    $data['search']             = $search;
    $data['total_rows']         = $total_rows;

    // load view
    $this->load->view('layout/header', $data);
    $this->load->view('admin/units/unitslist', $data);
    $this->load->view('layout/footer', $data);
}


    // public function old__index()
    // {
    //     $this->session->set_userdata('top_menu', 'particles');
    //     $this->session->set_userdata('sub_menu', 'admin/units.html');

    //     $data['title']              = 'Add Unit';
    //     $data["name"]               = "";
    //     $data["code"]               = "";
    //     $data["base_unit"]          = "";
    //     $data["base_unit"]          = "";
    //     $data["operator"]           = "";
    //     $data["operation_value"]    = "";
    //     $data["description"]        = "";

    //     $unitslist  = $this->units_model->getParentunits();
    //     $data["unitslist"]          = $unitslist;

    //     $resultlist = $this->units_model->get();
    //     $data["resultlist"]         = $resultlist;

    //     $this->load->view('layout/header', $data);
    //     $this->load->view('admin/units/unitslist', $data);
    //     $this->load->view('layout/footer', $data);
    // }

    function create()
    {
        if (!$this->rbac->hasPrivilege('units', 'can_add'))
        {
            access_denied();
        }

        $data['title']              = 'Add Unit';

        $unitslist  = $this->units_model->getParentunits();
        $data["unitslist"]          = $unitslist;

        $resultlist = $this->units_model->get();
        $data["resultlist"]         = $resultlist;
        $data["name"]               = "";
        $data["short_name"]         = "";
        $data["code"]               = "";
        $data["primary_unit"]       = "";
        $data["is_Active"]          = "";
        $data["description"]        = "";

        $this->form_validation->set_rules('name', 'Name', array('required', array('check_exists', array($this->units_model, 'check_exists'))));
        //$this->form_validation->set_rules('short_name', 'Short name', array('required'));
        //$this->form_validation->set_rules('code', $this->lang->line('Unit').' '.$this->lang->line('code'), 'trim|is_unique[units.code]|required');
        
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/units/unitslist', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $code = $this->generateNextCode("IU");

            $data = array(
                'name'              => $this->input->post('name'),
                'short_name'        => $this->input->post('short_name'),
                'code'              => $code,
                'primary_unit'      => $this->input->post('primary_unit'),
                'is_active'         => $this->input->post('is_active'),
                'description'       => $this->input->post('description'),
            );

            $this->units_model->add($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Unit added successfully</div>');
            redirect('admin/units.html');
        }
    }

    public function generateNextCode($prefix)
    {
        $this->db->select('code');
        $this->db->from('units');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);

        $query  = $this->db->get();
        $row    = $query->row();

        if ($row && preg_match('/IU_(\d+)/', $row->code, $matches))
        {
            $num = (int)$matches[1] + 1;
        }
        else
        {
            $num = 1; // first time
        }

        return $prefix .'_'. str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    function edit()
    {
        $id = $this->input->get('id');

        if (!$this->rbac->hasPrivilege('units', 'can_edit')) {
            access_denied();
        }
        
        $unitslist  = $this->units_model->getParentunits();
        $resultlist = $this->units_model->get();
        $result     = $this->units_model->get($id);
        if(!$result)
		{
            $this->session->set_flashdata('msg', '<div class="alert alert-ganger text-left">Unit item  not found</div>');
            redirect('admin/units.html');
		}

        $data['title']  = 'Edit Units';
        
        // --- SEARCH + PAGINATION ---
        $search = $this->input->get('search', true); //trim(); // safe get
        $page   = (int) $this->input->get('per_page') ?? 0; // page offset (query-string)

        $limit  = 10; // items per page - change as needed
        $offset = ($page && $page > 0) ? $page : 0;

        $total_rows = $this->units_model->count_units($search);
        $units      = $this->units_model->get_units($limit, $offset, $search);

        // Build pagination config
        $config = array();
        $config['base_url']             = base_url('admin/units.html'); 
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
    
       // $data['resultlist']         = $units;
        $data['pagination_links']   = $this->pagination->create_links();
        $data['search']             = $search;
        $data['total_rows']         = $total_rows;

        $data["unitslist"]          = $unitslist;
        $data["resultlist"]         = $resultlist;
        $data['id']                 = $id;
        $data["result"]             = $result;
        $data["name"]               = $result["name"];
        $data["short_name"]         = $result["short_name"];
        $data["primary_unit"]       = $result["primary_unit"];
        $data["is_active"]          = $result["is_active"];
        $data["description"]        = $result["description"];


        $this->form_validation->set_rules('name', 'Name', array( 'required', array('check_exists', array($this->units_model, 'check_exists')) ));
        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/units/unitslist', $data);
            $this->load->view('layout/footer', $data);
        }
        else
        {
            $data = array(
                'id'                => $id,
                'name'              => $this->input->post('name'),
                'short_name'        => $this->input->post('short_name'),
                'primary_unit'      => $this->input->post('primary_unit'),
                'is_active'          => $this->input->post('is_active'),
                'description'       => $this->input->post('description'),
            );

            $this->units_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Unit updated successfully</div>');
            redirect('admin/units.html');
        }
    }

    function delete()
    {
        $id = $this->input->get('id');
        
        if (!$this->rbac->hasPrivilege('units', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $this->units_model->delete($id);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Unit Deleted successfully</div>');
        redirect('admin/units/index');
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
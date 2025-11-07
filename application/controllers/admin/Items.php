<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Items extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index()
    {
        $data['page_title']		= $this->lang->line('items_list');
		$data['page_header']	= $this->lang->line('items_list');
        $data["name"]           = "";
        $data["code"]           = "";
        $data["slug"]           = "";
        $data["parent"]         = "";
        $data["description"]    = "";

        $search     = $this->input->get('search', true);
        $page       = (int) $this->input->get('page') ? $this->input->get('page') : 0;
        $limit      = 10;
        $offset     = ($page && $page > 0) ? $page : 0;


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

    }

}

?>
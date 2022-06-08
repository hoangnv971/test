<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    public $title;
    public $description;
    public $content;
//     protected $_table_name = 'posts';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_last_ten_entries()
    {
        $query = $this->db->get('posts', 10);
        return $query->result();
    }

    public function getDatatable()
    {
        $request = $this->input->get();
        $columns = $request['columns'];
        $searchValue = $request['search']['value'] ?? '';
        $limit = $request['limit'] ?? 0;
        $offset = $request['length'] ?? 10;
        if(!empty($request['columns']))
        {
            
                foreach($columns as $column)
                {
                        if($column['name'] = 'action')
                        {
                                continue;
                        }
                        $this->db->or_where($column['data']." like", "%$searchValue%");
                }
        }

        if(!empty($request['order']))
        {
                foreach($request['order'] as $item)
                {
                        $columnName = $columns[$item['column']]['data'];
                        $this->db->order_by($columnName, $item['dir']);
                }
        }
        $this->db->limit($offset, $limit);

        return $this->db->get('posts')->result();
        
    }

    public function create()
    {
        $this->title    = $_POST['title']; // please read the below note
        $this->description  = $_POST['description'];
        $this->content     = $_POST['content'];
        $this->db->insert('posts', $this);
    }

    public function update()
    {
        $this->title    = $_POST['title'];
        $this->description    = $_POST['description'];
        $this->content  = $_POST['content'];

        $this->db->update('posts', $this, array('id' => $_POST['id']));
    }

    public function delete()
    {
        $this->db->delete('posts', array('id' => $_POST['id']));
    }

}

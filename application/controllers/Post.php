<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('post_model', 'post');
        $this->load->helper('debug');
    }

	public function index()
	{   
        if($this->input->is_ajax_request() ){
            $posts = $this->post->getDatatable();
            foreach ($posts as $post)
            {
                $post->action = "
                            <div class='row'>
                                <div class='col-sm-6'>
                                    <a href='/index.php/post/store' data-id='$post->id' class='btn btn-primary update-post'>update</a>
                                </div>
                                <div class='col-sm-6'>
                                    <form action='/index.php/post/destroy' method='POST' class='delete-post' >
                                        <input type='hidden' name='id' value='$post->id'>
                                        <button class='btn btn-danger' type='submit'>DELETE</button>
                                    </form>
                                </div>
                            </div>
                                ";
            }
            $this->loadJson([
                'data' => $posts
            ]);
        }
        $this->load->view('post');
	}

    public function store()
    {
        if($this->input->is_ajax_request() )
        {
            if(empty($_POST['id']))
            {
                $this->post->create();
            }else{
                $this->post->update();
            }
            $this->loadJson(['status'=> 1]);
        }
    }

    public function destroy()
    {
        if($this->input->is_ajax_request() )
        {
            $this->post->delete();
            $this->loadJson(['status'=> 1]);
        }
    }

    public function loadJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);exit;
    }
    
}

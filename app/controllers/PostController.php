<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;

class PostController extends Controller {
    private $postModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $this->postModel = new Post();
    }

    public function index() {
        $search = $this->getQuery('search');
        $posts = $search ? $this->postModel->search($search) : $this->postModel->all();

        $this->view('posts/index', [
            'title' => 'Posts',
            'posts' => $posts,
            'search' => $search
        ]);
    }

    public function create() {
        $this->view('posts/create', ['title' => 'Create Post']);
    }

    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/posts');
        }

        $title = trim($this->getPost('title'));
        $content = trim($this->getPost('content'));

        if (empty($title) || empty($content)) {
            $this->view('posts/create', [
                'title' => 'Create Post',
                'error' => 'Please fill in all fields',
                'title_value' => $title,
                'content_value' => $content
            ]);
            return;
        }

        $this->postModel->createPost($title, $content);
        $this->redirect('/posts');
    }

    public function edit($id) {
        $post = $this->postModel->find($id);
        
        if (!$post) {
            $this->redirect('/posts');
        }

        $this->view('posts/edit', [
            'title' => 'Edit Post',
            'post' => $post
        ]);
    }

    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('/posts');
        }

        $post = $this->postModel->find($id);
        if (!$post) {
            $this->redirect('/posts');
        }

        $title = trim($this->getPost('title'));
        $content = trim($this->getPost('content'));

        if (empty($title) || empty($content)) {
            $this->view('posts/edit', [
                'title' => 'Edit Post',
                'error' => 'Please fill in all fields',
                'post' => [
                    'id' => $id,
                    'title' => $title,
                    'content' => $content
                ]
            ]);
            return;
        }

        $this->postModel->updatePost($id, $title, $content);
        $this->redirect('/posts');
    }

    public function delete($id) {
        if (!$this->isPost()) {
            $this->redirect('/posts');
        }

        $post = $this->postModel->find($id);
        if ($post) {
            $this->postModel->delete($id);
        }

        $this->redirect('/posts');
    }
} 
<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use App\Models\User;

class HomeController extends Controller {
    private $postModel;
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $this->postModel = new Post();
        $this->userModel = new User();
    }

    public function index() {
        // Get total posts count
        $totalPosts = count($this->postModel->all());
        
        // Get total users count
        $totalUsers = count($this->userModel->all());
        
        // Get latest login
        $latestLogin = $this->userModel->first('last_login IS NOT NULL', [], '', 'last_login DESC');
        
        // Get recent posts
        $recentPosts = $this->postModel->getRecent(5);

        $this->view('home/index', [
            'title' => 'Dashboard',
            'totalPosts' => $totalPosts,
            'totalUsers' => $totalUsers,
            'latestLogin' => $latestLogin,
            'recentPosts' => $recentPosts
        ]);
    }
} 
<?php
namespace App\Models;

use App\Core\Model;

class Post extends Model {
    protected $table = 'posts';

    public function search($query) {
        $searchTerm = "%{$query}%";
        return $this->where(
            'title LIKE ? OR content LIKE ?',
            [$searchTerm, $searchTerm],
            'ss'
        );
    }

    public function getRecent($limit = 5) {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit], 'i');
    }

    public function createPost($title, $content) {
        return $this->create([
            'title' => $title,
            'content' => $content
        ]);
    }

    public function updatePost($id, $title, $content) {
        return $this->update($id, [
            'title' => $title,
            'content' => $content
        ]);
    }
} 
<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$conn = getDBConnection();

// Get all posts
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $result->fetch_all(MYSQLI_ASSOC);

$page_title = 'Posts';
ob_start();
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Posts</h3>
                <div class="card-tools">
                    <a href="create_post.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> New Post
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($post['created_at'])); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($post['updated_at'])); ?></td>
                            <td>
                                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'includes/layout.php';
?> 
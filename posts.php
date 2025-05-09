<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$conn = getDBConnection();

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_clause = '';
$params = [];
$types = '';

if (!empty($search)) {
    $where_clause = "WHERE title LIKE ? OR content LIKE ?";
    $search_param = "%{$search}%";
    $params = [$search_param, $search_param];
    $types = "ss";
}

// Get posts with search filter
$query = "SELECT * FROM posts " . $where_clause . " ORDER BY created_at DESC";
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
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
            <div class="card-body">
                <!-- Search Form -->
                <form method="get" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <?php if (!empty($search)): ?>
                                <a href="posts.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>

                <div class="table-responsive p-0">
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
</div>

<?php
$content = ob_get_clean();
require_once 'includes/layout.php';
?> 
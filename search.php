<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$conn = getDBConnection();
$search_results = [];
$search_query = '';

if (isset($_GET['q'])) {
    $search_query = trim($_GET['q']);
    if (!empty($search_query)) {
        $search_term = "%{$search_query}%";
        $stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC");
        $stmt->bind_param("ss", $search_term, $search_term);
        $stmt->execute();
        $result = $stmt->get_result();
        $search_results = $result->fetch_all(MYSQLI_ASSOC);
    }
}

$page_title = 'Search Posts';
ob_start();
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Search Posts</h3>
            </div>
            <div class="card-body">
                <form method="get" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search posts...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <?php if (!empty($search_query)): ?>
                    <h4>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h4>
                    <?php if (empty($search_results)): ?>
                        <p>No posts found matching your search criteria.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Content Preview</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($search_results as $post): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($post['content'], 0, 100)) . '...'; ?></td>
                                            <td><?php echo htmlspecialchars($post['created_at']); ?></td>
                                            <td>
                                                <a href="view_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'includes/layout.php';
?> 
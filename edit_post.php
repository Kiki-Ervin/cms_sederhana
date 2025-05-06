<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$conn = getDBConnection();
$error = '';
$success = '';

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    header("Location: posts.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all fields';
    } else {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $id);
        
        if ($stmt->execute()) {
            header("Location: posts.php");
            exit();
        } else {
            $error = 'Error updating post: ' . $conn->error;
        }
    }
}

$page_title = 'Edit Post';
ob_start();
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Post</h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Post</button>
                    <a href="posts.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'includes/layout.php';
?> 
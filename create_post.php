<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$conn = getDBConnection();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all fields';
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        
        if ($stmt->execute()) {
            header("Location: posts.php");
            exit();
        } else {
            $error = 'Error creating post: ' . $conn->error;
        }
    }
}

$page_title = 'Create Post';
ob_start();
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create New Post</h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Post</button>
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
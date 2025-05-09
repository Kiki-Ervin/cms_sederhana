<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Ensure user is logged in
requireLogin();

$conn = getDBConnection();
$error = '';
$success = '';

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'change_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = 'Please fill in all password fields';
        } elseif (strlen($new_password) < 6) {
            $error = 'New password must be at least 6 characters long';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New passwords do not match';
        } else {
            // Verify current password
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            if (password_verify($current_password, $user['password'])) {
                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
                
                if ($update_stmt->execute()) {
                    $success = 'Password updated successfully';
                } else {
                    $error = 'Error updating password: ' . $conn->error;
                }
            } else {
                $error = 'Current password is incorrect';
            }
        }
    } elseif ($_POST['action'] === 'delete_account') {
        // Delete user account
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            // Log out and redirect
            session_destroy();
            header("Location: login.php");
            exit();
        } else {
            $error = 'Error deleting account: ' . $conn->error;
        }
    }
}

$page_title = 'Account Settings';
ob_start();
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Change Password</h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <input type="hidden" name="action" value="change_password">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Delete Account</h3>
            </div>
            <div class="card-body">
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Warning: This action cannot be undone. All your data will be permanently deleted.
                </p>
                <form method="post" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                    <input type="hidden" name="action" value="delete_account">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'includes/layout.php';
?> 
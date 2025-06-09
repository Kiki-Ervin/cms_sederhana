<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create New Post</h3>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <form method="post" action="/posts/store">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title_value ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($content_value ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Post</button>
                    <a href="/posts" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div> 
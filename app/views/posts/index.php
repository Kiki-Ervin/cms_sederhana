<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Posts</h3>
                <div class="card-tools">
                    <a href="/posts/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> New Post
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="get" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <?php if (!empty($search)): ?>
                                <a href="/posts" class="btn btn-secondary">
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
                                    <a href="/posts/edit/<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="post" action="/posts/delete/<?php echo $post['id']; ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
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
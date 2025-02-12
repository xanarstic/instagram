<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="content">
        <h2>Selamat datang, <?= esc($userInfo['username']) ?>!</h2>

        <div id="posts">
            <?php foreach ($posts as $post): ?>
                <div class="post-container">
                    <div class="post-header">
                        <div class="profile-pic"></div>
                        <p><strong><?= esc($post['username']) ?></strong></p>
                    </div>
                    <?php if (strpos($post['media'], '.mp4') !== false): ?>
                        <video controls>
                            <source src="<?= base_url('uploads/' . $post['media']) ?>" type="video/mp4">
                        </video>
                    <?php else: ?>
                        <img src="<?= base_url('uploads/' . $post['media']) ?>" alt="Post Image">
                    <?php endif; ?>
                    <div class="post-footer">
                        <p><strong><?= esc($post['username']) ?></strong> <?= esc($post['caption']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>
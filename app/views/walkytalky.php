<?php
// walkytalky.php
session_set_cookie_params([
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

$blogFile = '/project/pilla-in-php/public/assets/blogs.json';

$adminPassword = getenv('BLOG_ADMIN_PASSWORD');

// -- Rate Limiting --
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
$maxAttempts = 5;
$lockoutTime = 300;

if (!isset($_SESSION['lockout_until'])) {
    $_SESSION['lockout_until'] = 0;
}

// -- Sanitize --
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// -- Load existing blogs --
function loadBlogs(string $file): array {
    if (!file_exists($file)) return [];
    $json = file_get_contents($file);
    return json_decode($json, true) ?? [];
}

// -- Save blogs --
function saveBlogs(string $file, array $blogs): void {
    file_put_contents($file, json_encode($blogs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

// -- Login Handler --
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && time() > $_SESSION['lockout_until']) {
    if ($_POST['password'] === $adminPassword) {
        $_SESSION['authenticated'] = true;
        $_SESSION['login_attempts'] = 0;
        header('Location: /walkytalky');
        exit;
    } else {
        $_SESSION['login_attempts'] += 1;
        if ($_SESSION['login_attempts'] >= $maxAttempts) {
            $_SESSION['lockout_until'] = time() + $lockoutTime;
            $error = "Too many attempts. Try again in 5 minutes.";
        } else {
            $error = "Incorrect password.";
        }
    }
}

// -- Logout Handler --
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: /walkytalky');
    exit;
}

$success = $error = '';

function currentFormattedDate() {
    return date('F j, Y'); // e.g., August 3, 2025
}

// -- Blog Management: Add / Modify / Delete --
if (!empty($_SESSION['authenticated'])) {
    $blogs = loadBlogs($blogFile);

    // Add new blog
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_blog'])) {
        $title = sanitize($_POST['title']);
        $rawContent = trim($_POST['content']);
        $date = !empty($_POST['date']) ? sanitize($_POST['date']) : currentFormattedDate();

        $paragraphs = [];
        $parts = preg_split('/(\[img\].*?\[\/img\])/i', $rawContent, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach ($parts as $part) {
            if (preg_match('/\[img\](.*?)\[\/img\]/i', $part, $matches)) {
                $imgSrc = sanitize($matches[1]);
                if (str_starts_with($imgSrc, './public/assets/media/') && preg_match('/\.(png|jpe?g|gif|webp)$/i', $imgSrc)) {
                    $paragraphs[] = ['type' => 'image', 'src' => $imgSrc];
                }
            } elseif (trim($part) !== '') {
                $paragraphs[] = ['type' => 'text', 'content' => sanitize($part)];
            }
        }

        $newEntry = [
            'title' => $title,
            'paragraphs' => $paragraphs,
            'date' => $date
        ];

        array_unshift($blogs, $newEntry);
        saveBlogs($blogFile, $blogs);
        $success = "Blog entry saved.";
    }

    // Modify existing blog
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_blog']) && isset($_POST['index'])) {
        $index = (int)$_POST['index'];
        if (isset($blogs[$index])) {
            $title = sanitize($_POST['title']);
            $rawContent = trim($_POST['content']);
            $date = !empty($_POST['date']) ? sanitize($_POST['date']) : currentFormattedDate();

            $paragraphs = [];
            $parts = preg_split('/(\[img\].*?\[\/img\])/i', $rawContent, -1, PREG_SPLIT_DELIM_CAPTURE);

            foreach ($parts as $part) {
                if (preg_match('/\[img\](.*?)\[\/img\]/i', $part, $matches)) {
                    $imgSrc = sanitize($matches[1]);
                    if (str_starts_with($imgSrc, './public/assets/media/') && preg_match('/\.(png|jpe?g|gif|webp)$/i', $imgSrc)) {
                        $paragraphs[] = ['type' => 'image', 'src' => $imgSrc];
                    }
                } elseif (trim($part) !== '') {
                    $paragraphs[] = ['type' => 'text', 'content' => sanitize($part)];
                }
            }

            $blogs[$index] = [
                'title' => $title,
                'paragraphs' => $paragraphs,
                'date' => $date
            ];
            saveBlogs($blogFile, $blogs);
            $success = "Blog entry updated.";
        } else {
            $error = "Invalid blog index.";
        }
    }

    // Delete blog
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_blog']) && isset($_POST['index'])) {
        $index = (int)$_POST['index'];
        if (isset($blogs[$index])) {
            array_splice($blogs, $index, 1);
            saveBlogs($blogFile, $blogs);
            $success = "Blog entry deleted.";
        } else {
            $error = "Invalid blog index.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog Admin</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 800px; margin: auto; }
        textarea, input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin: 5px 0; }
        .error { color: red; }
        .success { color: green; }
        .blog-entry { border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
        .blog-entry form { margin: 0; }
        .blog-entry h3 { margin: 0 0 5px 0; }
        .blog-controls { margin-top: 5px; }
        button { cursor: pointer; }
    </style>
</head>
<body>

<?php if (empty($_SESSION['authenticated'])): ?>
    <h2>Admin Login</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($_SESSION['lockout_until'] > time()): ?>
        <p class="error">Login disabled. Try again later.</p>
    <?php else: ?>
        <form method="POST" action="/walkytalky">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Enter</button>
        </form>
    <?php endif; ?>
<?php else: ?>
    <h2>Post New Blog</h2>
    <?php if (!empty($success)): ?>
        <p class="success"><?= $success ?></p>
    <?php elseif (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST" action="/walkytalky">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="content" rows="10" placeholder="Blog content. Use [img]./public/assets/media/yourimage.png[/img] to insert images." required></textarea>
        <input type="text" name="date" placeholder="e.g. August 1, 2025" value="<?= currentFormattedDate() ?>">
        <button type="submit" name="submit_blog">Post</button>
    </form>

    <h2>Existing Blogs</h2>

    <?php if (!empty($blogs)): ?>
        <?php foreach ($blogs as $i => $blog): ?>
            <div class="blog-entry">
                <h3><?= htmlspecialchars($blog['title'], ENT_QUOTES) ?> (<?= htmlspecialchars($blog['date'], ENT_QUOTES) ?>)</h3>
                <div>
                    <?php foreach ($blog['paragraphs'] as $p): ?>
                        <?php if ($p['type'] === 'text'): ?>
                            <p><?= nl2br(htmlspecialchars($p['content'], ENT_QUOTES)) ?></p>
                        <?php elseif ($p['type'] === 'image'): ?>
                            <img src="<?= htmlspecialchars($p['src'], ENT_QUOTES) ?>" alt="" style="max-width:100%; height:auto;">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="blog-controls">
                    <!-- Edit form -->
                    <button onclick="document.getElementById('edit-form-<?= $i ?>').style.display='block'; this.style.display='none';">Edit</button>

                    <!-- Delete form -->
                    <form method="POST" action="/walkytalky" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this blog post?');">
                        <input type="hidden" name="index" value="<?= $i ?>">
                        <button type="submit" name="delete_blog">Delete</button>
                    </form>
                </div>

                <!-- Hidden edit form -->
                <form method="POST" action="/walkytalky" id="edit-form-<?= $i ?>" style="display:none; margin-top:10px;">
                    <input type="hidden" name="index" value="<?= $i ?>">
                    <input type="text" name="title" value="<?= htmlspecialchars($blog['title'], ENT_QUOTES) ?>" required>
                    <textarea name="content" rows="6" required><?php
                        $contentText = '';
                        foreach ($blog['paragraphs'] as $p) {
                            if ($p['type'] === 'text') {
                                $contentText .= $p['content'];
                            } elseif ($p['type'] === 'image') {
                                $contentText .= '[img]' . $p['src'] . '[/img]';
                            }
                        }
                        echo htmlspecialchars($contentText, ENT_QUOTES);
                    ?></textarea>
                    <input type="text" name="date" value="<?= htmlspecialchars($blog['date'], ENT_QUOTES) ?>" required>
                    <button type="submit" name="edit_blog">Save</button>
                    <button type="button" onclick="document.getElementById('edit-form-<?= $i ?>').style.display='none'; this.parentElement.previousElementSibling.style.display='inline-block';">Cancel</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No blog entries found.</p>
    <?php endif; ?>

    <p><a href="/walkytalky?logout=1">Logout</a></p>
<?php endif; ?>

</body>
</html>

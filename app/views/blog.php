<?php
$blogFile = __DIR__ . '/../../public/assets/blogs.json';
$blogs = [];

if (file_exists($blogFile)) {
    $jsonContent = file_get_contents($blogFile);
    $decoded = json_decode($jsonContent, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $blogs = $decoded;
    } else {
        error_log("Invalid JSON in blogs.json");
    }
}
?>

<div class="main-content-section" style="margin: 40px auto; max-width: 800px; border-radius: 20px;">
  <header style="text-align: center; margin-bottom: 50px;">
    <h1 style="font-size: 3rem; color: #ffff66; text-shadow: 0 0 5px #ffff66, 0 0 10px #ffcc00, 0 0 20px #ffcc00;">
      My Journal/Blog
    </h1>
    <p style="color: #dda0dd; font-style: italic;">By Pilla • Updated June 2025</p>
  </header>

  <?php foreach ($blogs as $entry): ?>
    <article style="margin-bottom: 60px;">
      <h2 style="color: #dda0dd;"><?php echo htmlspecialchars($entry['title'] ?? 'Untitled'); ?></h2>

      <?php foreach ($entry['paragraphs'] as $block): ?>
    <?php if (is_array($block) && isset($block['type'])): ?>
        <?php
        switch ($block['type']) {
            case 'text':
                ?>
                <p style="color: #fff9a6; line-height: 1.8; margin-top: 10px;">
                    <?php echo nl2br(htmlspecialchars($block['content'] ?? '')); ?>
                </p>
                <?php
                break;

            case 'image':
                $src = htmlspecialchars($block['src'] ?? '');
                $alt = htmlspecialchars($block['alt'] ?? '');
                if ($src):
                ?>
                    <div style="margin: 20px 0; text-align: center;">
                        <img src="<?php echo $src; ?>" alt="<?php echo $alt; ?>" style="max-width: 100%; border-radius: 10px;" />
                        <?php if ($alt): ?>
                            <p style="color: #dda0dd; font-size: 0.9rem; font-style: italic;"><?php echo $alt; ?></p>
                        <?php endif; ?>
                    </div>
                <?php
                endif;
                break;

            default:
                ?>
                <p style="color: #ff9999;">[Unsupported block type]</p>
                <?php
                break;
        }
        ?>
    <?php endif; ?>
<?php endforeach; ?>


      <p style="font-size: 0.9rem; color: #ffff66; margin-top: 10px;">
        Posted on <?php echo htmlspecialchars($entry['date'] ?? 'Unknown date'); ?>
      </p>
    </article>
  <?php endforeach; ?>

  <footer style="text-align: center; border-top: 1px dashed #dda0dd; padding-top: 30px;">
    <p style="color: #fff9a6;">Thanks for reading, friend 🌌</p>
    <p style="color: #dda0dd; font-size: 0.9rem;">More entries soon. Until then, live your fullest.</p>
  </footer>
</div>

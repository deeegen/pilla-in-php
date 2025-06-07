<?php
$blogFile = __DIR__ . '/../../public/assets/blogs.json';
$blogs = [];

if (file_exists($blogFile)) {
    $jsonContent = file_get_contents($blogFile);
    $blogs = json_decode($jsonContent, true);
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
      <h2 style="color: #dda0dd;"><?php echo htmlspecialchars($entry['title']); ?></h2>
      <?php foreach ($entry['paragraphs'] as $paragraph): ?>
        <p style="color: #fff9a6; line-height: 1.8; margin-top: 10px;">
          <?php echo nl2br(htmlspecialchars($paragraph)); ?>
        </p>
      <?php endforeach; ?>
      <p style="font-size: 0.9rem; color: #ffff66; margin-top: 10px;">Posted on <?php echo htmlspecialchars($entry['date']); ?></p>
    </article>
  <?php endforeach; ?>

  <footer style="text-align: center; border-top: 1px dashed #dda0dd; padding-top: 30px;">
    <p style="color: #fff9a6;">Thanks for reading, friend 🌌</p>
    <p style="color: #dda0dd; font-size: 0.9rem;">More entries soon. Until then, live your fullest.</p>
  </footer>
</div>

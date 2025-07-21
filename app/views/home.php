<?php
ob_start();
?>
<div class="main-content-section">
  <h3>Introduction</h3>
  <p>Hi! I'm Jordan, some random person still addicted to the internet.</p>
</div>

<div class="main-content-section">
  <h3>Random Stuff</h3>
  <ul>
    <li>My favorite color combo is yellow and purplee</li>
    
  </ul>
  <h3>Title:</h3>
  <ul>
    <li></li>
</ul>
</div>
<?php
$content = ob_get_clean();
$title = "Pilla66 Blog & About";
require __DIR__ . '/layout.php';

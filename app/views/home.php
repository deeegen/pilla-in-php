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
    <li>I enjoy spending company with anyoneeee</li>
    <li>Born and living in New York State (NOT NYC sadly)</li>
    <li>I've gone on vacations to places like the Bahamas</li>
    <li>Never had covid</li>
    <li>i LOVE weed, if you LOVEEE weed, hmu</li>
    <li>The government doesnt know but i am a criminal</li>
    <li>I'll respond to all your dms o_o</li>
  </ul>
  <h3>Here are some of my RED FLAGSSS:</h3>
  <ul>
    <li>I am homosexual (pansexual or fluid i think)</li>
    <li>I have mental illness that may effect you if you interact with me</li>
    <li>I'm very unsocial if I don't know you too well (doesnt mean i dont wanna talkk)</li>
  </ul>
</div>
<?php
$content = ob_get_clean();
$title = "Home";
require __DIR__ . '/layout.php';

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title><?= $title ?? 'Pillas Brain' ?></title>
    <link rel="stylesheet" href="/public/assets/style.css" />
  </head>
  <body>
    <header class="header">
      <canvas id="oscilloscope"></canvas>
      <h1>Welcome to a Lunatics Brain</h1>
      <audio id="audio" controls crossorigin="anonymous"></audio>
      <ul id="playlist"></ul>
    </header>

    <section class="grid">
      <div class="iframe-overlay" id="iframeOverlay">
        <div class="close-btn" id="closeOverlay">×</div>
        <iframe src="" id="blogIframe"></iframe>
      </div>

      <div class="side-container">
        <div class="profile">
          <div class="profile-picture-container">
            <img
              src="public/assets/media/main-pfp-yay-faggot-kys-ugly-fucking-faggot.gif"
              alt="Profile Picture"
              class="profile-img"
            />
            <img
              src="https://files.catbox.moe/hzfq0a.gif"
              alt="Icon"
              class="profile-icon"
            />
          </div>
          <h2>@Pilla</h2>
          <div class="status">wan2kms</div>
          <p>Gender: F</p>
          <div class="social-links">
            <a
              href="https://roblox.com/"
              target="_blank"
              class="social-btn"
              id="roblox-btn"
              title="Roblox"
            >
              <img
                src="public/assets/media/logo-roblox.png"
                alt="Roblox"
              />
            </a>
            <a
              href="https://github.com/deeegen"
              target="_blank"
              class="social-btn"
              id="github-btn"
              title="GitHub"
            >
              <img
                src="public/assets/media/image%20(5).png"
                alt="GitHub"
              />
            </a>
            <a
              href="https://open.spotify.com/user/va9fp0ua12jaw3vixj43cv84d?si=36bc087949b848bd"
              target="_blank"
              class="social-btn"
              id="spotify-btn"
              title="Spotify"
            >
              <img
                src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png"
                alt="Spotify"
              />
            </a>
          </div>
        </div>

        <div class="media">
          <p><b>Here are some interests of mine:</b></p>
          <img
            src="public/assets/media/1aEY.gif"
            alt="Media Image Placeholder"
          />
          <p>Making things online (games, websites)</p>
          <p>Art (pencil, digital, photography, etc)</p>
          <p>Socializing :)) I'll talk and listen to (mostly) anyone</p>
          <button class="open-blog-btn" id="openBlogBtn">Open Blog</button>
        </div>
      </div>

      <div class="main">
        <div class="main-text">
          <div class="title"></div>
          <div class="subtitle"></div>
        </div>

        <?= $content ?>

        <div class="main-content-section"></div>
      </div>
    </section>

    <script>
      document.querySelectorAll('.social-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
          alert('Opening ' + this.title + ' profile!');
        });
      });

      document.addEventListener('DOMContentLoaded', function () {
    const openBlogBtn = document.getElementById('openBlogBtn');
    const iframeOverlay = document.getElementById('iframeOverlay');
    const blogIframe = document.getElementById('blogIframe');
    const closeOverlay = document.getElementById('closeOverlay');

    const BLOG_URL = '/blog';

    openBlogBtn.addEventListener('click', function () {
      blogIframe.src = BLOG_URL;
      iframeOverlay.style.display = 'block';
    });

    closeOverlay.addEventListener('click', function () {
      iframeOverlay.style.display = 'none';
      blogIframe.src = '';
    });
  });
    </script>
    

    <script src="/public/assets/script.js"></script>
  </body>
</html>

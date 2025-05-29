<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title><?= $title ?? 'Pillas Brain' ?></title>
    <link rel="stylesheet" href="public/assets/style.css" />
    <style>
      .main-content-section {
        background: rgba(255, 255, 255, 0.05);
        padding: 20px;
        border: 2px dashed #dda0dd;
        margin-bottom: 20px;
        border-radius: 10px;
      }
      .main-content-section p, li, h3 {
        color: #fff9a6;
      }
      .social-links {
        margin-top: 16px;
        display: flex;
        justify-content: center;
        gap: 16px;
      }
      .social-btn {
        display: inline-block;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        background: #fff9a6;
        border: 2px solid #dda0dd;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 0 8px #dda0dd44;
      }
      .social-btn img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
      }
      .social-btn:hover {
        transform: scale(1.1) rotate(-8deg);
        box-shadow: 0 0 16px #ffff66cc;
        border-color: #ffff66;
      }
    </style>
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
        <iframe src="https://example.com"></iframe>
      </div>

      <div class="side-container">
        <div class="profile">
          <div class="profile-picture-container">
            <img
              src="https://cdn.glitch.me/c15fc835-384e-45f6-84ec-fcea3518f542/images_(2)_V1.gif?v=1747745801434"
              alt="Profile Picture"
              class="profile-img"
            />
            <img
              src="https://cdn.glitch.global/5970b456-17e9-4f56-b0f2-46f0d4636862/90285d63-04d5-4e70-bc3b-3a3a56c19ec4.image.png?v=1745414426506"
              alt="Icon"
              class="profile-icon"
            />
          </div>
          <h2>@Pilla</h2>
          <div class="status">Status Placeholder</div>
          <p>"AHHHHHHHH"</p>
          <p>Gender: F</p>
          <div class="social-links">
            <a
              href="https://twitter.com/"
              target="_blank"
              class="social-btn"
              id="twitter-btn"
              title="Twitter"
            >
              <img
                src="https://cdn-icons-png.flaticon.com/512/733/733579.png"
                alt="Twitter"
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
                src="https://cdn-icons-png.flaticon.com/512/733/733553.png"
                alt="GitHub"
              />
            </a>
            <a
              href="https://instagram.com/"
              target="_blank"
              class="social-btn"
              id="instagram-btn"
              title="Instagram"
            >
              <img
                src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png"
                alt="Instagram"
              />
            </a>
          </div>
        </div>

        <div class="media">
          <img
            src="https://cdn.glitch.global/5970b456-17e9-4f56-b0f2-46f0d4636862/download%20(3).png?v=1745414711074"
            alt="Media Image Placeholder"
          />
          <p><b>Here are some interests of mine:</b></p>
          <p>Making things online (games, websites)</p>
          <p>Art (pencil, digital, photography, etc)</p>
          <p>Socializing :)) I'll talk and listen to (mostly) anyone</p>
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
    </script>

    <script src="public/assets/script.js"></script>
  </body>
</html>

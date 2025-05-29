const playlist = [
  {
    title: "Slutty Mind",
    url: "https://cdn.glitch.global/c15fc835-384e-45f6-84ec-fcea3518f542/SpotiMate.io%20-%20slutty%20mind__%20-%20ilymeow.mp3?v=1747838760689",
  },
  {
    title: "Whats up",
    url: "https://cdn.glitch.global/5970b456-17e9-4f56-b0f2-46f0d4636862/Whats%20Up%20-%20Astrid%20Issbrokie.mp3?v=1745345033951",
  },
  {
    title: "Audition",
    url: "https://cdn.glitch.global/5970b456-17e9-4f56-b0f2-46f0d4636862/Audition%20-%20Who%20Who.mp3?v=1745411534137",
  },
  {
    title: "LOSING SLEEP",
    url: "https://cdn.glitch.global/5970b456-17e9-4f56-b0f2-46f0d4636862/LOSING%20SLEEP%20-%20pupsies.mp3?v=1746642001329",
  },
  {
    title: "u dont love me or want me",
    url: "https://cdn.glitch.global/5970b456-17e9-4f56-b0f2-46f0d4636862/u%20dont%20love%20me%20or%20want%20me%20-%20Marluxiam.mp3?v=1746641976716",
  },
  {
    title: "my femcel life",
    url: "https://cdn.glitch.global/5970b456-17e9-4f56-b0f2-46f0d4636862/my%20femcel%20life%20-%20pupsies.mp3?v=1746641954687",
  },
];

const audio = document.getElementById("audio");
const playlistEl = document.getElementById("playlist");

playlist.forEach((song, idx) => {
  const li = document.createElement("li");
  li.textContent = song.title;
  li.addEventListener("click", () => loadTrack(idx));
  playlistEl.appendChild(li);
});

let currentTrackIndex = null;

function getRandomTrackIndex(excludeIndex) {
  let index;
  do {
    index = Math.floor(Math.random() * playlist.length);
  } while (index === excludeIndex);
  return index;
}

function loadTrack(index) {
  currentTrackIndex = index;
  audio.src = playlist[index].url;
  audio.play();
  updateSelection(index);
}

function updateSelection(selectedIndex) {
  Array.from(playlistEl.children).forEach((li, idx) => {
    li.classList.toggle("selected", idx === selectedIndex);
  });
}

loadTrack(getRandomTrackIndex(null));

audio.addEventListener("ended", () => {
  const nextIndex = getRandomTrackIndex(currentTrackIndex);
  loadTrack(nextIndex);
});

const canvas = document.getElementById("oscilloscope");
const ctx = canvas.getContext("2d");
audio.crossOrigin = "anonymous";

const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
const analyser = audioCtx.createAnalyser();
analyser.fftSize = 2048;

const bufferLength = analyser.fftSize;
const dataArray = new Uint8Array(bufferLength);

const source = audioCtx.createMediaElementSource(audio);
source.connect(analyser);
analyser.connect(audioCtx.destination);

function resizeCanvas() {
  canvas.width = canvas.offsetWidth;
  canvas.height = canvas.offsetHeight;
}
resizeCanvas();
window.addEventListener("resize", resizeCanvas);

function draw() {
  requestAnimationFrame(draw);
  analyser.getByteTimeDomainData(dataArray);
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.strokeStyle = "rgba(255,255,102,0.8)";
  ctx.lineWidth = 5;
  ctx.beginPath();
  const sliceWidth = canvas.width / bufferLength;
  let x = 0;
  for (let i = 0; i < bufferLength; i++) {
    const v = dataArray[i] / 128.0;
    const y = (v * canvas.height) / 2;
    i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
    x += sliceWidth;
  }
  ctx.lineTo(canvas.width, canvas.height / 2);
  ctx.stroke();
}

audio.onplay = () => {
  if (audioCtx.state === "suspended") audioCtx.resume();
  draw();
};

const navLink2 = document.getElementById("navLink2");
const overlay = document.getElementById("iframeOverlay");
const closeBtn = document.getElementById("closeOverlay");

navLink2.addEventListener("click", (e) => {
  e.preventDefault();
  overlay.style.display = "block";
});
closeBtn.addEventListener("click", () => {
  overlay.style.display = "none";
});

document.querySelectorAll(".social-circle").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    const url = btn.getAttribute("href");
    const width = 500;
    const height = 600;
    const left = window.screen.width / 2 - width / 2;
    const top = window.screen.height / 2 - height / 2;

    if (url && url !== "#") {
      window.open(
        url,
        "_blank",
        `width=${width},height=${height},top=${top},left=${left},status=no,toolbar=no,menubar=no,location=no`
      );
      btn.classList.add("clicked");
      setTimeout(() => btn.classList.remove("clicked"), 400);
      e.preventDefault();
    }
  });
});

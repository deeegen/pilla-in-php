import { playlist } from "./playlist.js";

export function initPlayer(audio, playlistEl, canvas) {
  // Build playlist UI
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

  // Start with a random track
  loadTrack(getRandomTrackIndex(null));

  // Auto-advance on end
  audio.addEventListener("ended", () => {
    const nextIndex = getRandomTrackIndex(currentTrackIndex);
    loadTrack(nextIndex);
  });

  // Audio visualization setup
  const ctx = canvas.getContext("2d");
  audio.crossOrigin = "anonymous";

  const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
  const analyser = audioCtx.createAnalyser();
  analyser.fftSize = 1024;

  const bufferLength = analyser.fftSize;
  const dataArray = new Uint8Array(bufferLength);

  const source = audioCtx.createMediaElementSource(audio);
  source.connect(analyser);
  analyser.connect(audioCtx.destination);

  // Resize canvas with debounce
  function debounce(fn, delay) {
    let timeout;
    return (...args) => {
      clearTimeout(timeout);
      timeout = setTimeout(() => fn(...args), delay);
    };
  }

  function resizeCanvas() {
    canvas.width = canvas.clientWidth;
    canvas.height = canvas.clientHeight;
  }

  window.addEventListener("resize", debounce(resizeCanvas, 150));
  resizeCanvas();

  ctx.strokeStyle = "rgba(255,255,102,0.8)";
  ctx.lineWidth = 4;

  function draw() {
    requestAnimationFrame(draw);
    analyser.getByteTimeDomainData(dataArray);

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.beginPath();

    const halfH = canvas.height / 2;
    let x = 0;
    const sliceW = canvas.width / bufferLength;

    for (let i = 0; i < bufferLength; i++) {
      const v = dataArray[i] / 128.0;
      const y = v * halfH;
      i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
      x += sliceW;
    }
    ctx.lineTo(canvas.width, halfH);
    ctx.stroke();
  }

  draw();

  audio.onplay = () => {
    if (audioCtx.state === "suspended") audioCtx.resume();
    draw();
  };
}

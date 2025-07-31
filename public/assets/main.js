import { initPlayer } from "./player.js";
import { initUI } from "./ui.js";

document.addEventListener("DOMContentLoaded", () => {
  const audio = document.getElementById("audio");
  const playlistEl = document.getElementById("playlist");
  const canvas = document.getElementById("oscilloscope");

  initPlayer(audio, playlistEl, canvas);
  initUI("#navLink2", "#iframeOverlay", "#closeOverlay", ".social-circle");
});

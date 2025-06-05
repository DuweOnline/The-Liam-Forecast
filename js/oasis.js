////cdn.jsdelivr.net/npm/@lottiefiles/dotlottie-web/+esm
import { DotLottie } from "./lottie.js";

const dropdown = document.getElementById("dropdown");
const dropdownMenu = document.getElementById("dropdownMenu");
const dropdownItems = dropdownMenu.querySelectorAll(".dropdown-item");
const allSoundButtons = document.querySelectorAll(".allSoundButton");
const weather = document.querySelector(".weather");
const allsounds = document.querySelector(".allsounds");
const weatherBg = document.getElementById("weatherBg");
const title = document.querySelector(".title");
const liam = document.querySelector(".item img");
const lyric = document.querySelector(".lyric");
const back = document.getElementById("back");
const reverse = document.getElementById("reverse");
const audioElement = document.querySelector("audio");

dropdown.addEventListener("click", () => {
  dropdown.classList.toggle("active");
  dropdownMenu.classList.toggle("show");
});

dropdownItems.forEach((item) => {
  item.addEventListener("click", () => {
    var bg = item.dataset.bg;
    var code = item.dataset.code;
    var temperature = item.dataset.temperature;
    var location = item.dataset.location;
    var day = item.dataset.day;
    var date = item.dataset.date;
    var lyrics = item.dataset.lyric;
    var sound = item.dataset.sound;
    dropdown.querySelector("span").textContent = item.textContent;
    dropdown.classList.remove("active");
    dropdownMenu.classList.remove("show");
    back.classList.add("show");
    weather.classList.add("open");
    title.classList.add("hide");
    new DotLottie({
      autoplay: true,
      loop: true,
      canvas: weatherBg,
      src: "lottie/" + bg + ".lottie",
    });
    setTimeout(function () {
      weatherBg.classList.remove("hide");
    }, 250);
    lyric.classList.add("show");
    lyric.innerHTML = `${lyrics}`;
    weather.innerHTML = `
			<h2><span>forecast</span>${code}</h2>
			<h2 class="temperature"><span>temperature</span>${temperature}&deg;</h2>
			<h2><span>date</span>${day}<div>${date}, ${location}</div></h2>
			<div class="buttons">
				<button id="encore">Encore</button>
				<button id="sounds">Hear all sounds</button>
			</div>
	`;
    liam.classList.add("move");
    audioElement.src = sound;
    if (audioElement.state === "suspended") {
      audioElement.resume();
      audioElement.play();
    }
    const encore = document.getElementById("encore");
    const sounds = document.getElementById("sounds");
    encore.addEventListener("click", (e) => {
      lyric.innerHTML = `${lyrics}`;
      new DotLottie({
        autoplay: true,
        loop: true,
        canvas: weatherBg,
        src: "lottie/" + bg + ".lottie",
      });
      audioElement.src = sound;
      if (audioElement.state === "suspended") {
        audioElement.resume();
        audioElement.play();
      }
    });
    sounds.addEventListener("click", (e) => {
      allsounds.classList.add("open");
    });
    reverse.addEventListener("click", (e) => {
      allsounds.classList.remove("open");
    });
  });
});
allSoundButtons.forEach((allSoundButton) => {
  allSoundButton.addEventListener("click", () => {
    var newAudio = allSoundButton.dataset.audio;
    var newLyric = allSoundButton.dataset.lyric;
    var newBg = allSoundButton.dataset.bg;
    lyric.innerHTML = `${newLyric}`;
    new DotLottie({
      autoplay: true,
      loop: true,
      canvas: weatherBg,
      src: "lottie/" + newBg + ".lottie",
    });
    audioElement.src = newAudio;
    if (audioElement.state === "suspended") {
      audioElement.resume();
      audioElement.play();
    }
  });
});
back.addEventListener("click", (e) => {
  e.preventDefault();
  dropdown.querySelector("span").textContent = "Please select your date";
  dropdown.classList.remove("active");
  dropdownMenu.classList.remove("show");
  back.classList.remove("show");
  weather.classList.remove("open");
  lyric.classList.remove("show");
  weatherBg.classList.add("hide");
  lyric.classList.remove("show");
  liam.classList.remove("move");
  title.classList.remove("hide");
  allsounds.classList.remove("open");
  lyric.innerHTML = "";
});
console.info(
  "Built by mark@duwe.co.uk - get in touch if you want something awesome built too - https://duwe.co.uk",
);

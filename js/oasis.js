////cdn.jsdelivr.net/npm/@lottiefiles/dotlottie-web/+esm
import { DotLottie } from "./lottie.js";

// --- Cached DOM Elements ---
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

// --- Lottie Instance Caching ---
// Initialize the lottie player once and update its source later
let lottiePlayer = null;

// --- Helper Functions ---
function toggleDropdown(isOpen) {
    const isCurrentlyOpen = dropdown.classList.contains("active");
    const newState = isOpen !== undefined ? isOpen : !isCurrentlyOpen;

    dropdown.classList.toggle("active", newState);
    dropdownMenu.classList.toggle("show", newState);
    
    // ♿️ ARIA: Update aria-expanded state
    dropdown.setAttribute("aria-expanded", newState);
}

function updateUI(item) {
    const bg = item.dataset.bg;
    const code = item.dataset.code;
    const temperature = item.dataset.temperature;
    const location = item.dataset.location;
    const day = item.dataset.day;
    const date = item.dataset.date;
    const lyrics = item.dataset.lyric;
    const sound = item.dataset.sound;

    dropdown.querySelector("span").textContent = item.textContent;
    toggleDropdown(false); // Close dropdown

    back.classList.add("show");
    weather.classList.add("open");
    title.classList.add("hide");

    // 🖼️ Lottie: Load animation or re-init if not done
    if (!lottiePlayer) {
        lottiePlayer = new DotLottie({
            autoplay: true,
            loop: true,
            canvas: weatherBg,
            src: "lottie/" + bg + ".lottie",
        });
    } else {
        lottiePlayer.load("lottie/" + bg + ".lottie");
    }

    setTimeout(function () {
        weatherBg.classList.remove("hide");
    }, 250);
    
    lyric.classList.add("show");
    // 💡 Best Practice: Use textContent where possible to prevent XSS
    lyric.textContent = lyrics;

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

    // 🎧 Audio: Simplified control
    audioElement.src = sound;
    audioElement.play().catch(error => {
        console.warn("Audio play failed (user gesture required):", error);
    });

    // --- Dynamic Button Listeners (Attached only once after creation) ---
    // Note: The Encore button logic is now a simple repeat of this function, but
    // to match the original structure, we'll keep the new listeners here.
    const encore = document.getElementById("encore");
    const sounds = document.getElementById("sounds");

    encore.onclick = () => { // Use onclick for easy reassignment
        // This is a much cleaner way to re-run the "encore" logic
        updateUI(item); 
    };
    
    sounds.onclick = () => {
        allsounds.classList.add("open");
    };

    reverse.onclick = () => { // Re-assigning to ensure it works after 'sounds' button is clicked
        allsounds.classList.remove("open");
    };
}


// --- Event Listeners ---

// 1. Dropdown Toggle (Click and Keyboard)
dropdown.addEventListener("click", () => toggleDropdown());
dropdown.addEventListener("keydown", (e) => {
    // ♿️ ARIA: Handle keyboard navigation (Enter/Space to open/close)
    if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        toggleDropdown();
    } 
    // Esc closes the dropdown
    if (e.key === "Escape") {
        e.preventDefault();
        toggleDropdown(false);
    }
});


// 2. Dropdown Item Clicks
dropdownItems.forEach((item) => {
    item.addEventListener("click", () => {
        updateUI(item);
    });

    // ♿️ ARIA: Handle keyboard selection on list items (Enter)
    item.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            updateUI(item);
        }
    });
});


// 3. All Sounds Button Clicks
allSoundButtons.forEach((allSoundButton) => {
    allSoundButton.addEventListener("click", () => {
        const newAudio = allSoundButton.dataset.audio;
        const newLyric = allSoundButton.dataset.lyric;
        const newBg = allSoundButton.dataset.bg;

        lyric.textContent = newLyric;
        
        // 🖼️ Lottie: Update Lottie source
        lottiePlayer.load("lottie/" + newBg + ".lottie");

        // 🎧 Audio: Simplified control
        audioElement.src = newAudio;
        audioElement.play().catch(error => {
            console.warn("Audio play failed:", error);
        });
    });
});


// 4. Back Button Reset
back.addEventListener("click", (e) => {
    e.preventDefault();
    dropdown.querySelector("span").textContent = "Please select your date";
    
    // Reset ARIA and classes
    toggleDropdown(false); 
    
    back.classList.remove("show");
    weather.classList.remove("open");
    lyric.classList.remove("show");
    weatherBg.classList.add("hide");
    liam.classList.remove("move");
    title.classList.remove("hide");
    allsounds.classList.remove("open");
    lyric.textContent = "";
});


console.info(
    "Built by mark@duwe.co.uk - get in touch if you want something awesome built too - https://duwe.co.uk",
);
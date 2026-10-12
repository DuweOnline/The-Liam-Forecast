////cdn.jsdelivr.net/npm/@lottiefiles/dotlottie-web/+esm
import { DotLottie } from "./lottie.js";

// --- Cached DOM Elements ---
// The gig picker is only rendered when there are gigs in the next two weeks
const gigPicker = document.getElementById("gigPicker");
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
const gigStatus = document.getElementById("gigStatus");
const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

// --- Lottie Instance Caching ---
// Initialize the lottie player once and update its source later
let lottiePlayer = null;

// Set by the gig picker so the Back button can reset it
let resetGigPicker = null;

// --- Helper Functions ---
function escapeHtml(value) {
    const entities = { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" };
    return String(value).replace(/[&<>"']/g, (char) => entities[char]);
}

// Lower-case and strip accents so "olimpic" finds "Olímpic"
function normalise(value) {
    return value.normalize("NFD").replace(/[̀-ͯ]/g, "").toLowerCase();
}

// ♿️ Screen reader announcements via the polite live region
function announce(message) {
    if (gigStatus) {
        gigStatus.textContent = message;
    }
}

// ♿️ Closed panels sit off-screen, so keep them out of the tab order and away from screen readers
function setPanelOpen(panel, isOpen) {
    panel.classList.toggle("open", isOpen);
    panel.inert = !isOpen;
}

// 🖼️ Lottie: the animations are decorative. For anyone who asks for reduced motion,
// show a still frame instead of a looping animation.
function showAnimation(bg) {
    const config = {
        src: "lottie/" + bg + ".lottie",
        loop: true,
        autoplay: !prefersReducedMotion.matches,
    };
    if (!lottiePlayer) {
        lottiePlayer = new DotLottie({ ...config, canvas: weatherBg });
        lottiePlayer.addEventListener("load", () => {
            if (prefersReducedMotion.matches) {
                lottiePlayer.setFrame(Math.floor(lottiePlayer.totalFrames / 2));
            }
        });
    } else {
        lottiePlayer.load(config);
    }
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

    back.classList.add("show");
    back.inert = false;
    setPanelOpen(weather, true);
    title.classList.add("hide");

    showAnimation(bg);

    setTimeout(function () {
        weatherBg.classList.remove("hide");
    }, 250);

    lyric.classList.add("show");
    lyric.textContent = lyrics;

    // Values come from the database, so escape them. The forecast text contains a deliberate <br>.
    // ♿️ A description list pairs each label with its value for screen readers.
    const forecast = code.split("<br>").map(escapeHtml).join("<br>");
    weather.innerHTML = `
        <dl class="forecast">
            <div class="stat"><dt>forecast</dt><dd>${forecast}</dd></div>
            <div class="stat temperature"><dt>temperature</dt><dd>${escapeHtml(temperature)}&deg;</dd></div>
            <div class="stat"><dt>date</dt><dd>${escapeHtml(day)}<span class="stat-detail">${escapeHtml(date)}, ${escapeHtml(location)}</span></dd></div>
        </dl>
        <div class="buttons">
            <button type="button" id="encore">Encore</button>
            <button type="button" id="sounds">Hear all sounds</button>
        </div>
    `;

    liam.classList.add("move");

    // 🎧 Audio: Simplified control
    audioElement.src = sound;
    audioElement.play().catch(error => {
        console.warn("Audio play failed (user gesture required):", error);
    });

    // ♿️ Move focus to the forecast and read it out
    weather.focus({ preventScroll: true });
    setTimeout(() => {
        announce(`Forecast for ${location}, ${day} ${date}: ${code.replace("<br>", ", ")}, ${temperature} degrees.`);
    }, 100);

    // --- Dynamic Button Listeners (re-attached because the buttons are re-created above) ---
    const encore = document.getElementById("encore");
    const sounds = document.getElementById("sounds");

    encore.onclick = () => {
        updateUI(item);
        // The old button was replaced, so put focus on the new one
        document.getElementById("encore").focus();
    };

    sounds.onclick = () => {
        setPanelOpen(allsounds, true);
        reverse.focus();
    };

    reverse.onclick = () => {
        setPanelOpen(allsounds, false);
        document.getElementById("sounds").focus();
    };
}


// --- Searchable Gig Picker ---
// Follows the WAI-ARIA combobox pattern: https://www.w3.org/WAI/ARIA/apg/patterns/combobox/
function initGigPicker() {
    const input = document.getElementById("gigSearch");
    const button = document.getElementById("dropdownButton");
    const toggle = document.getElementById("dropdown");
    const menu = gigPicker.querySelector(".dropdown-menu");
    const emptyMessage = gigPicker.querySelector(".dropdown-empty");
    const options = Array.from(gigPicker.querySelectorAll("[role='option']")).map((el) => ({
        el,
        searchText: normalise(`${el.textContent} ${el.dataset.day} ${el.dataset.date}`),
    }));

    let visibleOptions = options.map(({ el }) => el);
    let activeIndex = -1;
    let announceTimer = null;

    const isOpen = () => toggle.classList.contains("active");

    function setListOpen(open) {
        toggle.classList.toggle("active", open);
        menu.classList.toggle("show", open);
        input.setAttribute("aria-expanded", open);
        button.setAttribute("aria-expanded", open);
        button.setAttribute("aria-label", open ? "Hide gigs" : "Show all gigs");
        if (!open) {
            setActive(-1);
        }
    }

    function setActive(index) {
        options.forEach(({ el }) => el.classList.remove("is-active"));
        activeIndex = index;
        const active = visibleOptions[index];
        if (active) {
            active.classList.add("is-active");
            input.setAttribute("aria-activedescendant", active.id);
            active.scrollIntoView({ block: "nearest" });
        } else {
            input.removeAttribute("aria-activedescendant");
        }
    }

    // Show every gig when the box is empty or still holds the chosen gig, otherwise filter by what's typed
    function showsSelection() {
        const selected = options.find(({ el }) => el.getAttribute("aria-selected") === "true");
        return input.value === "" || (selected && input.value === selected.el.textContent.trim());
    }

    function filterOptions(showAll, shouldAnnounce = true) {
        const terms = showAll ? [] : normalise(input.value).split(/\s+/).filter(Boolean);
        visibleOptions = [];
        options.forEach(({ el, searchText }) => {
            const matches = terms.every((term) => searchText.includes(term));
            el.hidden = !matches;
            if (matches) {
                visibleOptions.push(el);
            }
        });
        emptyMessage.hidden = visibleOptions.length > 0;
        setActive(-1);

        clearTimeout(announceTimer);
        if (shouldAnnounce) {
            // Wait for a pause in typing so screen readers aren't flooded
            announceTimer = setTimeout(() => {
                const count = visibleOptions.length;
                announce(count === 0 ? "No gigs match your search" : `${count} ${count === 1 ? "gig" : "gigs"} available`);
            }, 400);
        }
    }

    function openList() {
        filterOptions(showsSelection());
        setListOpen(true);
    }

    function selectOption(el) {
        options.forEach((option) => option.el.setAttribute("aria-selected", option.el === el));
        input.value = el.textContent.trim();
        clearTimeout(announceTimer);
        setListOpen(false);
        updateUI(el);
    }

    input.addEventListener("input", () => {
        filterOptions(false);
        setListOpen(true);
    });

    input.addEventListener("click", () => {
        if (!isOpen()) {
            openList();
        }
    });

    input.addEventListener("keydown", (e) => {
        switch (e.key) {
            case "ArrowDown":
                e.preventDefault();
                if (!isOpen()) {
                    openList();
                }
                // Alt+Down only opens the list
                if (!e.altKey) {
                    setActive(Math.min(activeIndex + 1, visibleOptions.length - 1));
                }
                break;
            case "ArrowUp":
                e.preventDefault();
                if (!isOpen()) {
                    openList();
                }
                setActive(activeIndex <= 0 ? visibleOptions.length - 1 : activeIndex - 1);
                break;
            case "Enter":
                if (!isOpen()) {
                    break;
                }
                e.preventDefault();
                if (visibleOptions[activeIndex]) {
                    selectOption(visibleOptions[activeIndex]);
                } else if (visibleOptions.length === 1) {
                    selectOption(visibleOptions[0]);
                }
                break;
            case "Escape":
                if (isOpen()) {
                    e.preventDefault();
                    setListOpen(false);
                } else if (input.value !== "") {
                    e.preventDefault();
                    input.value = "";
                    filterOptions(true, false);
                }
                break;
            case "Tab":
                setListOpen(false);
                break;
        }
    });

    // The arrow button and the padding around the search box open the full list too
    button.addEventListener("click", () => {
        if (isOpen()) {
            setListOpen(false);
        } else {
            openList();
        }
        input.focus();
    });

    toggle.addEventListener("click", (e) => {
        if (e.target === toggle) {
            input.focus();
            if (!isOpen()) {
                openList();
            }
        }
    });

    options.forEach(({ el }) => {
        // Keep focus in the search box while clicking an option
        el.addEventListener("mousedown", (e) => e.preventDefault());
        el.addEventListener("click", () => selectOption(el));
    });

    // Close when clicking anywhere else
    document.addEventListener("pointerdown", (e) => {
        if (isOpen() && !gigPicker.contains(e.target)) {
            setListOpen(false);
        }
    });

    resetGigPicker = () => {
        input.value = "";
        options.forEach(({ el }) => el.setAttribute("aria-selected", "false"));
        filterOptions(true, false);
        setListOpen(false);
        input.focus();
    };
}

if (gigPicker) {
    initGigPicker();
}


// --- Event Listeners ---

// 1. All Sounds Button Clicks
allSoundButtons.forEach((allSoundButton) => {
    allSoundButton.addEventListener("click", () => {
        lyric.textContent = allSoundButton.dataset.lyric;
        showAnimation(allSoundButton.dataset.bg);

        // 🎧 Audio: Simplified control
        audioElement.src = allSoundButton.dataset.audio;
        audioElement.play().catch(error => {
            console.warn("Audio play failed:", error);
        });
    });
});


// 2. Back Button Reset
back.addEventListener("click", (e) => {
    e.preventDefault();

    back.classList.remove("show");
    back.inert = true;
    setPanelOpen(weather, false);
    setPanelOpen(allsounds, false);
    lyric.classList.remove("show");
    weatherBg.classList.add("hide");
    liam.classList.remove("move");
    title.classList.remove("hide");
    lyric.textContent = "";
    announce("");

    // ♿️ Return focus to the search box
    if (resetGigPicker) {
        resetGigPicker();
    }
});


console.info(
    "Built by mark@duwe.co.uk - get in touch if you want something awesome built too - https://duwe.co.uk",
);

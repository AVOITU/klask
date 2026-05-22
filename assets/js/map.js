// Chargement ASYNCHRONE des sphères

const OVERLAY = document.getElementById("map-overlays");
const BUBBLE = document.getElementById("activity-bubble");
const BUBNAME = document.getElementById("bubble-name");
const BUBDESC = document.getElementById("bubble-desc");
const BUBWAIT = document.getElementById("bubble-wait");

function createSphere(sphere) {
    const el = document.createElement("div");
    el.className = "sphere-zone";
    el.style.cssText = `--c:${sphere.color};left:${sphere.centerX}%;top:${sphere.centerY}%;width:${sphere.size}%`;
    el.innerHTML = `<span class="sphere-label">${sphere.name}</span>`;
    return el;
}

function createPin(activity, color) {
    const btn = document.createElement("button");
    btn.className = `activity-pin${activity.isAvailable ? "" : " unavailable"}`;
    btn.style.cssText = `--c:${color};left:${activity.pointXActivity}%;top:${activity.pointYActivity}%`;
    btn.dataset.name = activity.name;
    btn.dataset.desc = activity.descriptionActivity;
    btn.dataset.avail = activity.isAvailable ? "1" : "0";
    btn.dataset.wait = activity.waitMinutes ?? "";
    btn.setAttribute("aria-label", activity.name);
    return btn;
}

// Lecture du JSON embarqué dans le HTML (pas de fetch)
function loadMap() {
    try {
        const raw = document.getElementById("map-data");
        if (!raw) throw new Error("Données carte introuvables.");

        const spheres = JSON.parse(raw.textContent);
        const frag = document.createDocumentFragment();

        for (const sphere of spheres) {
            frag.appendChild(createSphere(sphere));
            for (const act of sphere.activities) {
                frag.appendChild(createPin(act, sphere.color));
            }
        }

        document.getElementById("map-loading")?.remove();
        OVERLAY.appendChild(frag);
    } catch (err) {
        const el = document.getElementById("map-loading");
        if (el) el.textContent = "Erreur de chargement de la carte.";
    }
}

// Bulle d'activité -> aide par Claude

OVERLAY.addEventListener("click", (e) => {
    const pin = e.target.closest(".activity-pin");
    if (!pin) return;

    e.stopPropagation();

    BUBNAME.textContent = pin.dataset.name;
    BUBDESC.textContent = pin.dataset.desc;
    BUBWAIT.textContent =
        pin.dataset.avail !== "1"
            ? "Stand indisponible pour le moment."
            : pin.dataset.wait
              ? `Temps d'attente estimé : ${pin.dataset.wait} min`
              : "";

    BUBBLE.hidden = false;
});

document.getElementById("btn-close-bubble")?.addEventListener("click", (e) => {
    e.stopPropagation();
    BUBBLE.hidden = true;
});

document.addEventListener("click", () => {
    BUBBLE.hidden = true;
});

// Bouton aide

const helpModal = document.getElementById("help-modal");

document.getElementById("btn-help")?.addEventListener("click", () => {
    helpModal.hidden = false;
});

helpModal?.addEventListener("click", (e) => {
    if (e.target === helpModal || e.target.closest(".intro-close")) helpModal.hidden = true;
});

document.querySelector("[data-intro-ack]")?.addEventListener("click", function () {
    fetch(this.dataset.introAck, { method: "POST", credentials: "same-origin" })
        .then((r) => { if (r.ok) document.getElementById("intro-overlay")?.remove(); });
});

// Pan + Zoom -> aide par Claude

(function initPanZoom() {
    const area = document.querySelector(".map-area");
    const canvas = document.querySelector(".map-canvas");
    if (!area || !canvas) return;

    let scale = 1,
        tx = 0,
        ty = 0;
    let dragging = false,
        startX = 0,
        startY = 0,
        originTx = 0,
        originTy = 0;

    const SCALE_MIN = 0.5,
        SCALE_MAX = 4;

    function applyTransform() {
        canvas.style.transform = `translate(${tx}px,${ty}px) scale(${scale})`;
    }

    // Zoom molette
    area.addEventListener(
        "wheel",
        (e) => {
            e.preventDefault();
            const rect = area.getBoundingClientRect();
            const mouseX = e.clientX - rect.left;
            const mouseY = e.clientY - rect.top;
            const delta = e.deltaY < 0 ? 1.1 : 0.9;
            const next = Math.min(
                SCALE_MAX,
                Math.max(SCALE_MIN, scale * delta),
            );
            tx = mouseX - (mouseX - tx) * (next / scale);
            ty = mouseY - (mouseY - ty) * (next / scale);
            scale = next;
            applyTransform();
        },
        { passive: false },
    );

    // Pan souris
    area.addEventListener("mousedown", (e) => {
        if (
            e.target.closest(
                ".activity-pin, #activity-bubble, .sidebar-container, .btn-help, #help-modal",
            )
        )
            return;
        dragging = true;
        startX = e.clientX;
        startY = e.clientY;
        originTx = tx;
        originTy = ty;
    });
    window.addEventListener("mousemove", (e) => {
        if (!dragging) return;
        tx = originTx + e.clientX - startX;
        ty = originTy + e.clientY - startY;
        applyTransform();
    });
    window.addEventListener("mouseup", () => {
        dragging = false;
    });

    // Pan + zoom touch (pinch)
    let lastDist = null,
        lastMidX = 0,
        lastMidY = 0,
        touchOriginTx = 0,
        touchOriginTy = 0;

    area.addEventListener(
        "touchstart",
        (e) => {
            if (e.touches.length === 1) {
                dragging = true;
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                originTx = tx;
                originTy = ty;
            } else if (e.touches.length === 2) {
                dragging = false;
                const [a, b] = e.touches;
                lastDist = Math.hypot(
                    b.clientX - a.clientX,
                    b.clientY - a.clientY,
                );
                lastMidX =
                    (a.clientX + b.clientX) / 2 -
                    area.getBoundingClientRect().left;
                lastMidY =
                    (a.clientY + b.clientY) / 2 -
                    area.getBoundingClientRect().top;
                touchOriginTx = tx;
                touchOriginTy = ty;
            }
        },
        { passive: true },
    );

    area.addEventListener(
        "touchmove",
        (e) => {
            e.preventDefault();
            if (e.touches.length === 1 && dragging) {
                tx = originTx + e.touches[0].clientX - startX;
                ty = originTy + e.touches[0].clientY - startY;
                applyTransform();
            } else if (e.touches.length === 2) {
                const [a, b] = e.touches;
                const dist = Math.hypot(
                    b.clientX - a.clientX,
                    b.clientY - a.clientY,
                );
                const next = Math.min(
                    SCALE_MAX,
                    Math.max(SCALE_MIN, (scale * dist) / lastDist),
                );
                tx = lastMidX - (lastMidX - touchOriginTx) * (next / scale);
                ty = lastMidY - (lastMidY - touchOriginTy) * (next / scale);
                scale = next;
                lastDist = dist;
                applyTransform();
            }
        },
        { passive: false },
    );

    area.addEventListener("touchend", () => {
        dragging = false;
        lastDist = null;
    });
})();

// Init - synchrone, données déjà dans le DOM

loadMap();

document.addEventListener('DOMContentLoaded', () => {
    console.log("🟢 JS chargé (thèmes uniquement)");
});

function changerTheme(theme) {
    document.body.setAttribute('data-theme', theme);
    // TODO: eviter que le changement de theme se fasse quand on relance le dé.
}
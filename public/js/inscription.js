document.addEventListener('DOMContentLoaded', () => {
    console.log("🟢 JS chargé (thèmes uniquement)");
});

function changerTheme(theme) {
    document.body.setAttribute('data-theme', theme);
}
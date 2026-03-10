document.addEventListener('DOMContentLoaded', () => {
    console.log('🟢 JS chargé pour les thèmes');

    document.querySelectorAll('.btn-access').forEach(button => {
        button.addEventListener('click', (event) => {
            const theme = event.currentTarget.dataset.theme;
            document.body.setAttribute('data-theme', theme);
        });
    });
});

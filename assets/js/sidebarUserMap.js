document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar-panel');
    const trigger = document.getElementById('sidebar-trigger-zone');

    if (!sidebar || !trigger) return;

    trigger.addEventListener('click', function () {
        const open = sidebar.classList.toggle('open');
        trigger.classList.toggle('is-open', open);
    });
});

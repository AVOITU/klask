console.log('JS chargé pour Sidebar user map');


document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar-panel');
    const btnOpen = document.getElementById('sidebar-trigger-zone');
    const btnClose = document.getElementById('btn-close-sidebar');

    if (!sidebar || !btnOpen || !btnClose) return;


    btnOpen.addEventListener('click', function() {
        sidebar.classList.add('open');
        btnOpen.style.display = 'none';
    });


    function closeSidebar() {
        sidebar.classList.remove('open');
        
        
        setTimeout(() => {
            btnOpen.style.display = 'flex'; 
        }, 400);
    }

    btnClose.addEventListener('click', closeSidebar);
});
document.getElementById('toggleSidebar').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.toggle('collapsed');
});

document.getElementById('toggleDark').addEventListener('click', function () {
    document.body.classList.toggle('dark');
});
// Toggle account dropdown
document.getElementById('accountButton').addEventListener('click', function() {
    const dropdown = document.getElementById('accountDropdown');
    dropdown.classList.toggle('show');
});

// Close dropdown when clicking outside
window.addEventListener('click', function(event) {
    if (!event.target.closest('.account_button')) {
        const dropdown = document.getElementById('accountDropdown');
        if (dropdown?.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    }
});
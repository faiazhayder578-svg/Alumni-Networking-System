document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('popup');
    const closePopup = document.querySelector('.close');
    const warnButtons = document.querySelectorAll('.warn-button');
    const userIdInput = document.getElementById('user_id');
    const warnForm = document.getElementById('warnForm');

    warnButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            document.querySelector('.popup-content h3').innerText = `Warn ${userName}`;
            userIdInput.value = userId;
            popup.style.display = 'flex';
        });
    });

    closePopup.addEventListener('click', () => {
        popup.style.display = 'none';
    });

    
    window.addEventListener('click', (event) => {
        if (event.target === popup) {
            popup.style.display = 'none';
        }
    });
});

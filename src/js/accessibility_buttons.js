function main() {
    const buttonsContainer = document.getElementById('accessibility-buttons-container');

    if (!buttonsContainer) {
        console.error('No buttons container found!');
    }

    const highContrastButton = document.createElement('button');
    highContrastButton.type = 'button';
    highContrastButton.className = 'button accessibility';
    highContrastButton.addEventListener('click', () => {
        document.body.classList.toggle('high-contrast');
    });

    buttonsContainer.appendChild(highContrastButton);
}
// Popup Success Component JavaScript
class PopupComponent {
    constructor() {
        this.createPopupHTML();
    }

    createPopupHTML() {
        // Create popup overlay if not exists
        if (!document.getElementById('popupOverlay')) {
            const popupHTML = `
                <div id="popupOverlay" class="popup-overlay">
                    <div class="popup-container">
                        <div id="popupIcon" class="popup-icon success">
                            ✓
                        </div>
                        <div id="popupTitle" class="popup-title">Success!</div>
                        <div id="popupMessage" class="popup-message">
                            Clock in berhasil dilakukan
                        </div>
                        <div id="popupTime" class="popup-time">08:00</div>
                        <button id="popupButton" class="popup-button" onclick="closePopup()">
                            OK
                        </button>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', popupHTML);
        }
    }

    show(options = {}) {
        const {
            type = 'success',
            title = 'Success!',
            message = 'Operasi berhasil dilakukan',
            time = null,
            buttonText = 'OK',
            onClose = null,
            autoClose = false,
            autoCloseDelay = 3000
        } = options;

        const overlay = document.getElementById('popupOverlay');
        const icon = document.getElementById('popupIcon');
        const titleEl = document.getElementById('popupTitle');
        const messageEl = document.getElementById('popupMessage');
        const timeEl = document.getElementById('popupTime');
        const button = document.getElementById('popupButton');

        // Set content
        titleEl.textContent = title;
        messageEl.textContent = message;
        button.textContent = buttonText;

        // Set time if provided
        if (time) {
            timeEl.textContent = time;
            timeEl.style.display = 'block';
        } else {
            timeEl.style.display = 'none';
        }

        // Set icon based on type
        icon.className = `popup-icon ${type}`;
        switch (type) {
            case 'success':
                icon.textContent = '✓';
                break;
            case 'error':
                icon.textContent = '✗';
                break;
            case 'warning':
                icon.textContent = '!';
                break;
            case 'info':
                icon.textContent = 'i';
                break;
        }

        // Set up close handler
        button.onclick = () => {
            this.hide();
            if (onClose) onClose();
        };

        // Show popup
        overlay.classList.add('show');

        // Auto close if enabled
        if (autoClose) {
            setTimeout(() => {
                this.hide();
                if (onClose) onClose();
            }, autoCloseDelay);
        }
    }

    hide() {
        const overlay = document.getElementById('popupOverlay');
        overlay.classList.remove('show');
    }
}

// Global functions for easy usage
function showSuccessPopup(options = {}) {
    const popup = new PopupComponent();
    popup.show({ ...options, type: 'success' });
}

function showErrorPopup(options = {}) {
    const popup = new PopupComponent();
    popup.show({ ...options, type: 'error' });
}

function showWarningPopup(options = {}) {
    const popup = new PopupComponent();
    popup.show({ ...options, type: 'warning' });
}

function showInfoPopup(options = {}) {
    const popup = new PopupComponent();
    popup.show({ ...options, type: 'info' });
}

function closePopup() {
    const popup = new PopupComponent();
    popup.hide();
}

// Global back button function with smart fallback
function smartGoBack(fallbackUrl = '/dashboard') {
    // Check if there's actual browser history to go back to
    if (window.history.length > 1 && document.referrer && 
        document.referrer !== window.location.href &&
        !document.referrer.includes('login')) {
        
        try {
            window.history.back();
        } catch (error) {
            console.log('History.back() failed, using fallback');
            window.location.href = fallbackUrl;
        }
    } else {
        // No valid history, go to fallback URL
        window.location.href = fallbackUrl;
    }
}

// Initialize popup component when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new PopupComponent();
});
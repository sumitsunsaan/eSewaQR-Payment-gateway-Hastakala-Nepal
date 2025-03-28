document.addEventListener('DOMContentLoaded', function() {
    function showToast(message, success = true) {
        const toast = document.createElement('div');
        toast.className = `esewa-toast ${success ? 'success' : 'error'}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 2000);
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.copy-phone')) {
            const phone = e.target.dataset.phone;
            const tempInput = document.createElement('input');
            tempInput.value = phone;
            document.body.appendChild(tempInput);
            tempInput.select();
            
            try {
                document.execCommand('copy');
                showToast(`${phone} copied to clipboard!`);
            } catch (err) {
                showToast('Failed to copy number', false);
            }
            
            document.body.removeChild(tempInput);
        }
    });
});
<div class="esewa-phone-payment">
    <h4>{l s='Pay via eSewa Mobile' mod='esewaqr'}</h4>
    
    <div class="payment-phone-container">
        <div class="phone-display">
            <span class="country-code">+977</span>
            <span class="phone-number">{$esewa_phone|replace:'+977 ':''}</span>
        </div>
        
        <button class="copy-phone btn btn-primary" data-phone="+977{$esewa_phone|replace:'+977 ':''}">
            <i class="material-icons">content_copy</i>
            {l s='Copy Number' mod='esewaqr'}
        </button>
    </div>

    <p class="instruction">
        {l s='Paste this number in eSewa app to complete payment' mod='esewaqr'}
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.copy-phone').forEach(button => {
        button.addEventListener('click', function() {
            const phone = this.dataset.phone;
            navigator.clipboard.writeText(phone).then(() => {
                const alert = document.createElement('div');
                alert.className = 'esewa-copy-alert';
                alert.textContent = '{l s="Number copied!" mod="esewaqr"}';
                document.body.appendChild(alert);
                setTimeout(() => alert.remove(), 2000);
            }).catch(err => {
                console.error('Copy failed:', err);
            });
        });
    });
});
</script>
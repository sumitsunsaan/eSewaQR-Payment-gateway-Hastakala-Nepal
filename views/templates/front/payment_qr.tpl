<div class="esewa-qr-payment">
    <div class="qr-container">
        {if $qr_path}
            <img src="{$qr_path}" alt="eSewa QR Code" class="qr-image">
            {if $is_mobile}
                <a href="{$qr_path}" download class="qr-download btn btn-primary">
                    {l s='Download QR Code' mod='esewaqr'}
                </a>
            {/if}
        {else}
            <div class="alert alert-warning">
                {l s='QR code not configured' mod='esewaqr'}
            </div>
        {/if}
    </div>
    <p class="instruction">{l s='Scan the QR code using eSewa app or any QR scanner' mod='esewaqr'}</p>
</div>
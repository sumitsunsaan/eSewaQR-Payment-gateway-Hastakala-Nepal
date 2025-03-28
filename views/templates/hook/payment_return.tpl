<div class="esewa-payment-confirmation">
    <h4>{l s='Payment confirmation' mod='esewaqr'}</h4>
    <p>
        {if $payment_method == 'eSewa Phone Payment'}
            {l s='Please send payment to:' mod='esewaqr'} <strong>{$phone_number}</strong>
        {else}
            {l s='Please complete payment using the QR code' mod='esewaqr'}
        {/if}
    </p>
    <p>{l s='Total amount paid:' mod='esewaqr'} <span class="price">{$total}</span></p>
    <p>{l s='Thank you for shopping at' mod='esewaqr'} {$shop_name}</p>
</div>
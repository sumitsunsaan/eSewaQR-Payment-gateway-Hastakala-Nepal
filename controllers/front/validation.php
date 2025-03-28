<?php
class EsewaqrValidationModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $cart = $this->context->cart;
        
        if (!$this->module->active ||
            $cart->id_customer == 0 ||
            $cart->id_address_delivery == 0 ||
            $cart->id_address_invoice == 0
        ) {
            Tools::redirect($this->context->link->getPageLink('order'));
        }

        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer)) {
            Tools::redirect($this->context->link->getPageLink('order'));
        }

        $method = Tools::getValue('method');
        $paymentName = ($method === 'phone') ? 
            $this->module->l('eSewa Phone Payment') : 
            $this->module->l('eSewa QR Payment');

        $this->module->validateOrder(
            (int)$cart->id,
            Configuration::get('PS_OS_PREPARATION'),
            (float)$cart->getOrderTotal(true, Cart::BOTH),
            $paymentName,
            null,
            [],
            (int)$cart->id_currency,
            false,
            $customer->secure_key
        );

        Tools::redirect($this->context->link->getPageLink(
            'order-confirmation',
            true,
            null,
            [
                'id_cart' => $cart->id,
                'id_module' => $this->module->id,
                'id_order' => $this->module->currentOrder,
                'key' => $customer->secure_key
            ]
        ));
    }
}
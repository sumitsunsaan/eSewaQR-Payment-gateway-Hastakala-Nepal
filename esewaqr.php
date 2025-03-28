<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class EsewaQR extends PaymentModule
{
    public function __construct()
    {
        $this->name = 'esewaqr';
        $this->tab = 'payments_gateways';
        $this->version = '2.2.1';
        $this->author = 'Sumit Dahal - Hastakala Nepal Private Limited';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->controllers = ['validation'];
        $this->ps_versions_compliancy = ['min' => '8.2.0', 'max' => '8.99.99'];

        parent::__construct();

        $this->displayName = $this->l('eSewa Payment Gateway');
        $this->description = $this->l('Accept payments via eSewa QR Code and Mobile Number');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        // Create upload directory
        if (!file_exists(_PS_MODULE_DIR_.$this->name.'/uploads/')) {
            mkdir(_PS_MODULE_DIR_.$this->name.'/uploads/', 0755, true);
        }

        // Migrate existing phone numbers
        $oldNumber = Configuration::get('ESEWA_PHONE');
        if ($oldNumber && !preg_match('/^\+977\s9\d{9}$/', $oldNumber)) {
            $cleanNumber = preg_replace('/[^0-9]/', '', $oldNumber);
            if (strlen($cleanNumber) === 10) {
                Configuration::updateValue('ESEWA_PHONE', '+977 '.$cleanNumber);
            }
        }

        return parent::install() &&
            $this->registerHook('paymentOptions') &&
            $this->registerHook('paymentReturn') &&
            $this->registerHook('header') &&
            Configuration::updateValue('ESEWA_PHONE', '+977 9845053769') &&
            Configuration::updateValue('ESEWA_QR_PATH', '');
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            Configuration::deleteByName('ESEWA_PHONE') &&
            Configuration::deleteByName('ESEWA_QR_PATH');
    }

    public function getContent()
    {
        $output = '';
        $uploadError = false;

        // Handle file upload
        if (isset($_FILES['ESEWA_QR_FILE']) && $_FILES['ESEWA_QR_FILE']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleQrUpload($_FILES['ESEWA_QR_FILE']);
            if ($uploadResult['success']) {
                Configuration::updateValue('ESEWA_QR_PATH', $uploadResult['path']);
                $output .= $this->displayConfirmation($this->l('QR code updated successfully'));
            } else {
                $output .= $this->displayError($uploadResult['error']);
                $uploadError = true;
            }
        }

        // Handle phone number update
        if (Tools::isSubmit('submitEsewa') && !$uploadError) {
            $phoneNumber = Tools::getValue('ESEWA_PHONE');
            if (preg_match('/^9\d{9}$/', $phoneNumber)) {
                Configuration::updateValue('ESEWA_PHONE', '+977 '.$phoneNumber);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            } else {
                $output .= $this->displayError($this->l('Invalid number: Must be 10 digits starting with 9'));
            }
        }

        return $output . $this->renderAdminForm();
    }

    private function handleQrUpload($file)
    {
        $allowedTypes = ['image/png' => 'png', 'image/jpeg' => 'jpg'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        // Verify MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        
        if (!array_key_exists($mimeType, $allowedTypes)) {
            return ['success' => false, 'error' => $this->l('Only PNG/JPEG images allowed')];
        }

        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => $this->l('Maximum file size 2MB exceeded')];
        }

        // Generate safe filename
        $extension = $allowedTypes[$mimeType];
        $filename = 'esewa-qr-'.Tools::str2url($this->context->shop->name).'-'.time().'.'.$extension;
        $destination = _PS_MODULE_DIR_.$this->name.'/uploads/'.$filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'path' => 'modules/'.$this->name.'/uploads/'.$filename
            ];
        }

        return ['success' => false, 'error' => $this->l('Error saving uploaded file')];
    }

    private function renderAdminForm()
    {
        $currentPhone = Configuration::get('ESEWA_PHONE');
        $phoneValue = preg_replace('/^\+977\s/', '', $currentPhone);
        $qrPath = Configuration::get('ESEWA_QR_PATH');

        $form = new HelperForm();
        $form->submit_action = 'submitEsewa';
        $form->token = Tools::getAdminTokenLite('AdminModules');

        return '
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-mobile-phone"></i> '.$this->l('eSewa Configuration').'
            </div>
            
            '.($qrPath ? '
            <div class="form-group">
                <label class="control-label col-lg-3">'.$this->l('QR Code Preview').'</label>
                <div class="col-lg-9">
                    <div class="qr-thumbnail-container">
                        <a href="'._PS_BASE_URL_.__PS_BASE_URI__.$qrPath.'" target="_blank" class="qr-preview-link">
                            <img src="'._PS_BASE_URL_.__PS_BASE_URI__.$qrPath.'" 
                                 class="qr-thumbnail" 
                                 alt="QR Code Thumbnail"
                                 style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                        </a>
                        <p class="help-block">'.$this->l('Click thumbnail to view full size').'</p>
                    </div>
                </div>
            </div>' : '').'

            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-lg-3">'.$this->l('Upload New QR Code').'</label>
                    <div class="col-lg-9">
                        <input type="file" name="ESEWA_QR_FILE" accept="image/png, image/jpeg">
                        <p class="help-block">'.$this->l('Requirements: 300x300px PNG/JPEG, max 2MB').'</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-lg-3">'.$this->l('Mobile Number').'</label>
                    <div class="col-lg-9">
                        <div class="input-group fixed-width-lg">
                            <span class="input-group-addon country-code-prefix">+977</span>
                            <input type="tel" 
                                   name="ESEWA_PHONE" 
                                   value="'.$phoneValue.'"
                                   pattern="9\d{9}" 
                                   required 
                                   class="form-control"
                                   placeholder="9XXXXXXXXX">
                        </div>
                        <p class="help-block">'.$this->l('Enter 10-digit number starting with 9').'</p>
                    </div>
                </div>

                <div class="panel-footer">
                    <button type="submit" name="submitEsewa" class="btn btn-primary pull-right">
                        <i class="process-icon-save"></i> '.$this->l('Save Settings').'
                    </button>
                </div>
            </form>
        </div>';
    }

    public function hookHeader()
    {
        $this->context->controller->registerStylesheet(
            'esewa-css',
            'modules/'.$this->name.'/views/css/esewaqr.css',
            ['media' => 'all', 'priority' => 150]
        );

        $this->context->controller->registerJavascript(
            'esewa-js',
            'modules/'.$this->name.'/views/js/copy-phone.js',
            ['position' => 'bottom', 'priority' => 150]
        );
    }

    public function hookPaymentOptions($params)
    {
        if (!$this->active || !$this->checkCurrency($params['cart'])) {
            return [];
        }

        $this->context->smarty->assign([
            'esewa_phone' => Configuration::get('ESEWA_PHONE'),
            'qr_path' => Configuration::get('ESEWA_QR_PATH'),
            'is_mobile' => $this->context->isMobile(),
            'module_dir' => $this->_path
        ]);

        return [
            $this->getQrPaymentOption(),
            $this->getPhonePaymentOption()
        ];
    }

    private function getQrPaymentOption()
    {
        $qrOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
        $qrOption->setModuleName($this->name)
            ->setCallToActionText($this->l('Pay with QR Code'))
            ->setAction($this->context->link->getModuleLink($this->name, 'validation', ['method' => 'qr']))
            ->setAdditionalInformation($this->context->smarty->fetch('module:esewaqr/views/templates/front/payment_qr.tpl'));

        return $qrOption;
    }

    private function getPhonePaymentOption()
    {
        $phoneOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
        $phoneOption->setModuleName($this->name)
            ->setCallToActionText($this->l('Pay via Mobile Number'))
            ->setAction($this->context->link->getModuleLink($this->name, 'validation', ['method' => 'phone']))
            ->setAdditionalInformation($this->context->smarty->fetch('module:esewaqr/views/templates/front/payment_phone.tpl'));

        return $phoneOption;
    }

    public function hookPaymentReturn($params)
    {
        if (!$this->active || !isset($params['order'])) {
            return;
        }

        $order = $params['order'];
        $this->smarty->assign([
            'payment_method' => $order->payment,
            'total' => Tools::displayPrice($order->getOrdersTotalPaid(), new Currency($order->id_currency)),
            'phone_number' => Configuration::get('ESEWA_PHONE'),
            'shop_name' => $this->context->shop->name,
            'status' => 'ok',
            'contact_url' => $this->context->link->getPageLink('contact', true)
        ]);

        return $this->fetch('module:esewaqr/views/templates/hook/payment_return.tpl');
    }

    private function checkCurrency($cart)
    {
        $currency = new Currency($cart->id_currency);
        return in_array($currency->iso_code, ['NPR', 'USD']);
    }
}
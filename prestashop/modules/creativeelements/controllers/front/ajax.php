<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class CreativeElementsAjaxModuleFrontController extends ModuleFrontController
{
    protected $content_only = true;

    public function postProcess()
    {
        $action = Tools::getValue('action');

        Tools::getValue('submitMessage') && $this->ajaxProcessSubmitMessage();
        Tools::getValue('submitNewsletter') && $this->ajaxProcessSubmitNewsletter();

        method_exists($this, "ajaxProcess$action") && $this->{"ajaxProcess$action"}();
    }

    public function ajaxProcessSubmitMessage()
    {
        if ($contact = Module::getInstanceByName('contactform')) {
            $contact->sendMessage();

            $this->ajaxDie([
                'success' => implode(nl2br("\n", false), $this->success),
                'errors' => $this->errors,
            ]);
        }

        $this->ajaxDie([
            'errors' => ['Error: Contact Form module should be enabled!'],
        ]);
    }

    public function ajaxProcessSubmitNewsletter()
    {
        $name = 'ps_emailsubscription';
        $newsletter = Module::getInstanceByName($name);

        if (!$newsletter) {
            $this->ajaxDie([
                'errors' => ["Error: $name module should be enabled!"],
            ]);
        }

        $newsletter->newsletterRegistration(${'_POST'}['blockHookName'] = 'displayCE');

        $this->ajaxDie([
            'success' => empty($newsletter->valid) ? '' : [$newsletter->valid],
            'errors' => empty($newsletter->error) ? [] : [$newsletter->error],
        ]);
    }

    public function ajaxProcessAddToCartModal()
    {
        $cart = $this->cart_presenter->present($this->context->cart, true);
        $product = null;
        $id_product = (int) Tools::getValue('id_product');
        $id_product_attribute = (int) Tools::getValue('id_product_attribute');
        $id_customization = (int) Tools::getValue('id_customization');

        foreach ($cart['products'] as &$p) {
            if ($id_product === (int) $p['id_product'] && $id_product_attribute === (int) $p['id_product_attribute'] && $id_customization === (int) $p['id_customization']) {
                $product = $p;
                break;
            }
        }

        $this->context->smarty->assign([
            'configuration' => $this->getTemplateVarConfiguration(),
            'product' => $product,
            'cart' => $cart,
            'cart_url' => $this->context->link->getPageLink('cart', null, $this->context->language->id, [
                'action' => 'show',
            ], false, null, true),
        ]);

        $this->ajaxDie([
            'modal' => $this->context->smarty->fetch('module:ps_shoppingcart/modal.tpl'),
        ]);
    }

    protected function ajaxDie($value = null, $controller = null, $method = null)
    {
        if (null === $controller) {
            $controller = get_class($this);
        }
        if (null === $method) {
            $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $method = $bt[1]['function'];
        }
        Hook::exec('actionAjaxDie' . $controller . $method . 'Before', ['value' => $value]);

        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

        exit(json_encode($value));
    }
}

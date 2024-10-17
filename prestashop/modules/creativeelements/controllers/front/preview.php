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

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\ProductPresenter;

class CreativeElementsPreviewModuleFrontController extends ModuleFrontController
{
    protected $uid;

    protected $title;

    public function init()
    {
        if (Tools::getIsset('redirect') && CreativeElements::hasAdminToken('AdminCEEditor')) {
            if ($cookie = CE\get_post_meta(0, 'cookie', true)) {
                CE\delete_post_meta(0, 'cookie');
                $lifetime = max(1, (int) Configuration::get('PS_COOKIE_LIFETIME_BO')) * 3600 + time();
                $admin = new Cookie('psAdmin', '', $lifetime);

                foreach ($cookie as $key => &$value) {
                    $admin->$key = $value;
                }
                unset($admin->remote_addr);

                $admin->write();
            }
            Tools::redirectAdmin(urldecode(Tools::getValue('redirect')));
        }

        $this->uid = CreativeElements::getPreviewUId(false);

        if (!$this->uid) {
            Tools::redirect('index.php?controller=404');
        }

        parent::init();
    }

    public function initContent()
    {
        $model = $this->uid->getModel();

        if ('CEContent' === $model) {
            $this->warning[] = CESmarty::get(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_undefined_position');
        }
        $post = CE\get_post($this->uid);

        $this->title = $post->post_title;
        $this->context->smarty->assign($model::${'definition'}['table'], [
            'id' => $post->_obj->id,
            'content' => '',
        ]);

        if ($id_product = (int) Tools::getValue('id_product')) {
            $presenter = new ProductPresenter(
                new ImageRetriever($this->context->link),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );
            $this->context->smarty->assign('product', $presenter->present(
                (new ProductPresenterFactory($this->context))->getPresentationSettings(),
                (new ProductAssembler($this->context))->assembleProduct(['id_product' => $id_product]),
                $this->context->language
            ));
        }

        parent::initContent();

        $this->title = $post->post_title;
        $this->context->smarty->addTemplateDir(_CE_TEMPLATES_);
        $this->context->smarty->assign([
            'HOOK_LEFT_COLUMN' => '',
            'HOOK_RIGHT_COLUMN' => '',
            'breadcrumb' => $this->getBreadcrumb(),
        ]);
        $this->template = 'front/preview.tpl';
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = [
            'links' => [
                ['url' => 'javascript:;', 'title' => 'Creative Elements'],
                ['url' => 'javascript:;', 'title' => CE\__('Preview')],
            ],
        ];
        if (!empty($this->title)) {
            $breadcrumb['links'][] = ['url' => 'javascript:;', 'title' => $this->title];
        }

        return $breadcrumb;
    }

    public function getBreadcrumbPath()
    {
        $breadcrumb = $this->getBreadcrumbLinks();

        return CESmarty::capture(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_preview_breadcrumb', [
            'links' => $breadcrumb['links'],
        ]);
    }
}

<?php
require __DIR__ . '/vendor/autoload.php';

use NateWr\themehelper\ThemeHelper;
use NateWr\vite\Loader;

import('lib.pkp.classes.plugins.ThemePlugin');

class SlubTheme extends ThemePlugin
{
    public const HEADER_DEFAULT = 'default';
    public const HEADER_CENTER = 'defaultCenter';
    public const HEADER_LINE = 'line';

    public const HOMEPAGE_IMAGE_POSITION_ABOVE = 'above';
    public const HOMEPAGE_IMAGE_POSITION_ABOVE_CENTER = 'above-center';
    public const HOMEPAGE_IMAGE_POSITION_BEHIND = 'behind';
    public const HOMEPAGE_IMAGE_POSITION_BEHIND_RIGHT_TOP = 'behind-right-top';
    public const HOMEPAGE_IMAGE_POSITION_BEHIND_RIGHT_CENTER = 'behind-right-center';
    public const HOMEPAGE_IMAGE_POSITION_BEHIND_RIGHT_BOTTOM = 'behind-right-bottom';
    public const HOMEPAGE_IMAGE_POSITION_BEHIND_PATTERN = 'behind-pattern';
    public const HOMEPAGE_IMAGE_POSITION_BELOW = 'below';
    public const HOMEPAGE_IMAGE_POSITION_BELOW_CENTER = 'below-center';

    protected ThemeHelper $themeHelper;

    public function isActive()
    {
        if (defined('SESSION_DISABLE_INIT')) return true;
        return parent::isActive();
    }

    public function init()
    {
        $this->registerTemplatePlugins();

        $this->addOption('header', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.slubTheme.option.header.label'),
            'description' => __('plugins.themes.slubTheme.option.header.description'),
            'options' => [
                [
                    'value' => self::HEADER_DEFAULT,
                    'label' => __('plugins.themes.slubTheme.option.header.default'),
                ],
                [
                    'value' => self::HEADER_CENTER,
                    'label' => __('plugins.themes.slubTheme.option.header.default-center'),
                ],
                [
                    'value' => self::HEADER_LINE,
                    'label' => __('plugins.themes.slubTheme.option.header.line'),
                ],
            ],
            'default' => self::HEADER_DEFAULT,
        ]);

        $this->addOption('tagline', 'FieldText', [
            'label' => __('plugins.themes.slubTheme.option.tagline.label'),
            'description' => __('plugins.themes.slubTheme.option.tagline.description'),
            'default' => '',
        ]);

        $this->addOption('homepageImagePosition', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.slubTheme.option.homepageImagePosition.label'),
            'description' => __('plugins.themes.slubTheme.option.homepageImagePosition.description'),
            'options' => [
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_ABOVE,
                    'label' => __('plugins.themes.slubTheme.option.homepageImagePosition.above'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_ABOVE_CENTER,
                    'label' => __('plugins.themes.slubTheme.option.homepageImagePosition.above-center'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BEHIND,
                    'label' => __('plugins.themes.slubTheme.option.homepageImagePosition.behind'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BEHIND_RIGHT_TOP,
                    'label' => __('plugins.themes.slubTheme.option.homepageImagePosition.behind-right-top'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BEHIND_RIGHT_CENTER,
                    'label' => __('plugins.themes.slubTheme.option.homepageImagePosition.behind-right-center'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BEHIND_RIGHT_BOTTOM,
                    'label' => __('plugins.themes.slubTheme.option.homepageImagePosition.behind-right-bottom'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BEHIND_PATTERN,
                    'label' => __('plugins.themes.slubTheme.option.homepageImagePosition.behind-pattern'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BELOW,
                    'label' => __('plugins.themes.slubTheme.option.homepageImagePosition.below'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BELOW_CENTER,
                    'label' => __('plugins.themes.slubTheme.option.homepageImagePosition.below-center'),
                ],
            ],
            'default' => self::HOMEPAGE_IMAGE_POSITION_ABOVE,
        ]);


        $this->addMenuArea(['primary', 'user', 'homepage']);

        $this->addViteAssets(['src/main.js']);

    }

    public function getDisplayName()
    {
        return __('plugins.themes.slubTheme.name');
    }

    public function getDescription()
    {
        return __('plugins.themes.slubTheme.description');
    }

    /**
     * Add helper functions to the template manager
     *
     * Registering these helper functions ensures that they are
     * available to use in the theme's templates, even if a
     * child theme is used.
     */
    protected function registerTemplatePlugins(): void
    {
        $templateMgr = $this->getTemplateManager();

        $this->themeHelper = new ThemeHelper($templateMgr);
        $this->themeHelper->registerDefaultPlugins();
        $this->themeHelper->safeRegisterPlugin('function', 'slub_context_name_length', [$this, 'setContextNameLength']);
    }

    protected function getTemplateManager(): TemplateManager
    {
        return TemplateManager::getManager(Application::get()->getRequest());
    }

    /**
     * Get the URL to the theme's root directory
     */
    public function getPluginUrl(): string
    {
        $request = Application::get()->getRequest();
        $baseUrl = rtrim($request->getBaseUrl(), '/');
        $pluginPath = rtrim($this->getPluginPath(), '/');
        return "{$baseUrl}/{$pluginPath}";
    }

    /**
     * Sets the size of the name of the context or site
     *
     * Used in the header to determine how large the name of
     * the context should be, when there is no logo.
     *
     * @return string One of `xs`, `sm`, `md`, `lg`
     */
    public function setContextNameLength(array $params, $smarty): void
    {
        if (!$this->themeHelper->hasParams($params, ['assign'], 'slub_context_name_lenth')) {
            return;
        }

        $context = Application::get()->getRequest()->getContext();
        $length = strlen($context ? $context->getLocalizedName() : '');

        $size = 'lg';
        if ($length <= 20) {
            $size = 'xs';
        } else if ($length <= 40) {
            $size = 'sm';
        } else if ($length <= 80) {
            $size = 'md';
        }

        $smarty->assign($params['assign'], $size);
    }

    /**
     * Whether or not the theme's header layout option matches
     * one of the passed options.
     *
     * @param string[] $values
     */
    public function isHeader(array $values): bool
    {
        $header = $this->getOption('header');
        return in_array($header, $values);
    }

    /**
     * Add the script, style and other assets compiled by Vite
     */
    protected function addViteAssets(array $entryPoints): void
    {
        $viteServerUrl = $this->getViteServerUrl();
        $basePath = $viteServerUrl ? $viteServerUrl : join('/', [$this->getPluginUrl(), 'dist/']);

        $viteLoader = new Loader(
            templateManager: TemplateManager::getManager(Application::get()->getRequest()),
            manifestPath: dirname(__FILE__) . '/dist/.vite/manifest.json',
            basePath: $basePath,
            devMode: $viteServerUrl ? true : false
        );

        $viteLoader->load($entryPoints);
    }

    /**
     * Get the URL to the Vite server
     *
     * This checks the theme's root directory for a .vite.server.json
     * file. If found, it reads the Vite server URL from that file.
     *
     * @see ./vite.config.js
     */
    protected function getViteServerUrl(): string
    {
        $path = join('/', [dirname(__FILE__), '.vite.server.json']);
        if (!file_exists($path)) {
            return '';
        }
        $config = json_decode(file_get_contents($path), true);
        if (!$config) {
            return '';
        }
        if (empty($config['network'])) {
            return isset($config['local']) ? $config['local'][0] : 'http://localhost:5173/';
        }
        return $config['network'][0];
    }
}

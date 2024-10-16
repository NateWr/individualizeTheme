<?php
require __DIR__ . '/vendor/autoload.php';

use NateWr\themehelper\ThemeHelper;
use NateWr\vite\Loader;

import('lib.pkp.classes.plugins.ThemePlugin');
import('plugins.themes.slubTheme.SlubThemeOptions');

class SlubTheme extends ThemePlugin
{
    protected SlubThemeOptions $optionsHelper;
    protected ThemeHelper $themeHelper;

    public function isActive()
    {
        if (defined('SESSION_DISABLE_INIT')) return true;
        return parent::isActive();
    }

    public function init()
    {
        $this->registerTemplatePlugins();
        $this->optionsHelper = new SlubThemeOptions($this);
        $this->optionsHelper->addOptions();
        $this->addStyle('variables', $this->optionsHelper->getCssVariablesString(), ['inline' => true]);
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
     * Add helper functions to the TemplateManager
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

<?php
require __DIR__ . '/vendor/autoload.php';

use NateWr\vite\Loader;

import('lib.pkp.classes.plugins.ThemePlugin');

class SlubTheme extends ThemePlugin
{

    public function isActive()
    {
        if (defined('SESSION_DISABLE_INIT')) return true;
        return parent::isActive();
    }

    public function init()
    {
        $this->addOption('header', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.slubTheme.option.header.label'),
            'description' => __('plugins.themes.slubTheme.option.header.description'),
            'options' => [
                [
                    'value' => 'default',
                    'label' => __('plugins.themes.slubTheme.option.header.default'),
                ],
                [
                    'value' => 'defaultCenter',
                    'label' => __('plugins.themes.slubTheme.option.header.default-center'),
                ],
                [
                    'value' => 'oneLine',
                    'label' => __('plugins.themes.slubTheme.option.header.oneLine'),
                ],
            ],
            'default' => 'default',
        ]);

        $this->addMenuArea(array('primary', 'user'));

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

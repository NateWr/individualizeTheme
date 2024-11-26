<?php

import('lib.pkp.classes.plugins.ThemePlugin');
import('plugins.themes.slubTheme.classes.SlubThemeViteManifestFile');

/**
 * Initialize Vite integration
 *
 * If Vite is in dev mode, it registers assets pointing
 * to Vite's server. Otherwise, it reads the assets from
 * the manifest.json file and lo.
 *
 * All assets are registered through the PKP's TemplateManager
 * class, or a ThemePlugin class if passed in the constructor.
 */
class SlubThemeViteLoader
{
    public const DEFAULT_VITE_SERVER_URL = 'http://localhost:5173/';

    public bool $devMode;
    protected string $baseUrl;

    public function __construct(
        /**
         * TemplateManager from the PKP application
         * (OJS, OMP, or OJS)
         */
        protected TemplateManager $templateManager,

        /**
         * Absolute path to vite's manifest.json file
         */
        protected string $manifestPath,

        /**
         * Base URL to vite build directory
         */
        protected string $buildUrl,

        /**
         * Absolute path to vite server configuration
         *
         * Usually a .vite.server.json file in the root directory
         * of the plugin.
         */
        public string $serverPath,

        /**
         * Unique prefix to use with script and style assets
         *
         * Typically the plugin name.
         */
        protected string $prefix,

        /**
         * Register assets as part of a theme
         *
         * By default, assets are registered using the PKPTemplateManager
         * methods. If a ThemePlugin is provided, the assets will be
         * registered using the ThemePlugin methods.
         *
         * This makes it easier to work with child themes.
         */
        public ?ThemePlugin $theme = null,
    ) {
        $this->buildUrl = rtrim($buildUrl, '/') . '/';
        $this->setMode();
    }

    /**
     * Sets devMode and baseUrl
     *
     * In dev mode, this returns the local or network
     * address to the vite server.
     *
     * In production, this returns the relative path to the
     * vite's build directory.
     *
     * Typically, this is `/plugins/themes/<plugin>/dist`.
     */
    protected function setMode(): void
    {
        if (!file_exists($this->serverPath)) {
            $this->devMode = false;
            $this->baseUrl = $this->buildUrl;
            return;
        }

        $config = json_decode(file_get_contents($this->serverPath), true);

        if (!$config) {
            $this->devMode = false;
            $this->baseUrl = $this->buildUrl;
            return;
        }

        $this->devMode = true;

        if (empty($config['network'])) {
            $this->baseUrl = isset($config['local']) ? $config['local'][0] : self::DEFAULT_VITE_SERVER_URL;
        }

        $this->baseUrl = $config['network'][0];
    }

    /**
     * Load vite assets for one or more entry points
     *
     * Adds the scripts, styles, and other assets to the template
     * using the TemplateManager class from OJS, OMP or OPS.
     */
    public function load(array $entryPoints): void
    {
        if ($this->devMode) {
            $this->loadDev($entryPoints);
        } else {
            $this->loadProd();
        }
    }

    /**
     * Load assets for vite dev server
     */
    protected function loadDev(array $entryPoints): void
    {
        $this->loadScript($this->prefix, "{$this->baseUrl}@vite/client", ['type' => 'module']);
        foreach ($entryPoints as $entryPoint) {
            $this->loadScript("{$this->prefix}-" . $entryPoint, "{$this->baseUrl}{$entryPoint}", ['type' => 'module']);
        }
    }

    /**
     * Load built assets from vite manifest
     */
    protected function loadProd(): void
    {
        $files = $this->getFiles();
        foreach ($files as $file) {
            if (str_ends_with($file->file, '.js')) {
                $this->templateManager->addHeader("{$this->prefix}-{$file->file}-preload", $this->getPreload($file->file, true));
            }
            if ($file->isEntry) {
                $this->loadScript("{$this->prefix}-{$file->file}", "{$this->baseUrl}{$file->file}", ['type' => 'module']);
            }
            foreach ($file->css as $css) {
                $this->loadStyle("{$this->prefix}-{$file->file}-{$css}", "{$this->baseUrl}{$css}");
            }
        }
    }

    protected function getFiles(): array
    {
        if (!is_readable($this->manifestPath)) {
            throw new RuntimeException(
                file_exists($this->manifestPath)
                    ? "Manifest file is not readable: {$this->manifestPath}"
                    : "Manifest file not found: {$this->manifestPath}"
            );
        }

        return array_map(
            fn(array $chunk) => SlubThemeViteManifestFile::create($chunk),
            json_decode(file_get_contents($this->manifestPath), true)
        );
    }

    /**
     * Get preload tag
     */
    protected function getPreload(string $url, bool $module = false): string
    {
        $rel = $module ? 'modulepreload' : 'preload';
        return "<link rel=\"{$rel}\" href=\"{$this->baseUrl}{$url}\" />";
    }

    /**
     * Load script asset
     */
    protected function loadScript(string $name, string $path, array $args): void
    {
        if ($this->theme) {
            $this->theme->addScript($name, $path, $args);
        } else {
            $this->templateManager->addJavaScript($name, $path, $args);
        }
    }

    /**
     * Load style asset
     */
    protected function loadStyle(string $name, string $path, array $args = []): void
    {
        if ($this->theme) {
            $this->theme->addStyle($name, $path, $args);
        } else {
            $this->templateManager->addStyleSheet($name, $path, $args);
        }
    }
}
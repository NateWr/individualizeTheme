<?php
require __DIR__ . '/vendor/autoload.php';

use NateWr\themehelper\TemplatePlugin;
use NateWr\themehelper\ThemeHelper;

import('lib.pkp.classes.plugins.ThemePlugin');
import('plugins.themes.slubTheme.SlubThemeOptions');
import('plugins.themes.slubTheme.classes.ViteLoader');

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
        $this->useThemeHelper();
        $this->optionsHelper = new SlubThemeOptions($this);
        $this->optionsHelper->addOptions();
        $this->addFonts();
        $this->addStyle('variables', $this->optionsHelper->getCssVariablesString(), ['inline' => true]);
        $this->addMenuArea(['primary', 'user', 'homepage', 'policy']);
        $this->addScript('i18n', $this->getI18nScript(), ['inline' => true]);
        $this->addViteAssets(['src/main.js']);
        HookRegistry::register('TemplateManager::display', [$this, 'addTemplateData']);
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
     * Add data to specific templates
     */
    public function addTemplateData(string $hookName, array $args): bool
    {
        $templateMgr = $args[0];
        $template = $args[1];
        $request = Application::get()->getRequest();
        $context = $request->getContext();

        if ($template === 'frontend/pages/indexJournal.tpl') {
            $this->optionsHelper->getHomepageBlocks();
        }

        if ($template === 'frontend/pages/article.tpl') {
            AppLocale::requireComponents(LOCALE_COMPONENT_APP_EDITOR);
            if ($context) {
                /** @var UserGroupDAO */
                $userGroupDao = DAORegistry::getDAO('UserGroupDAO');
                $userGroups = $userGroupDao->getByRoleId($context->getId(), ROLE_ID_AUTHOR);
                $templateMgr->assign([
                    'authorUserGroups' => $userGroups->toArray(),
                ]);
            }
        }

        $articleListTemplates = [
            'frontend/pages/catalogCategory.tpl',
            'frontend/pages/search.tpl'
        ];

        if (in_array($template, $articleListTemplates)) {
            if ($context) {
                $templateMgr->assign([
                    'primaryGenreIds' => $this->getPrimaryFileGenreIds($context->getId()),
                ]);
            }
        }

        return false;
    }

    /**
     * Use functions from ThemeHelper
     *
     * These helper functions register custom template functions, add
     * useful data to templates, and provide other utilities.
     */
    protected function useThemeHelper(): void
    {
        $this->themeHelper = new ThemeHelper($this->getTemplateManager());
        $this->themeHelper->addCommonTemplatePlugins();
        $this->themeHelper->addTemplatePlugin(
            new TemplatePlugin(
                type: 'function',
                name: 'slub_context_name_length',
                callback: [$this, 'setContextNameLength']
            )
        );
    }

    protected function getTemplateManager(): TemplateManager
    {
        return TemplateManager::getManager(Application::get()->getRequest());
    }

    /**
     * Load the selected fonts
     */
    protected function addFonts(): void
    {
        $this->addStyle(
            'st-font',
            "
@font-face {
  font-family: 'Inter';
  font-style: italic;
  font-weight: 100 900;
  font-display: swap;
  src: url({$this->getPluginUrl()}/fonts/inter/latin-ext-italic.woff2) format('woff2');
  unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
@font-face {
  font-family: 'Inter';
  font-style: italic;
  font-weight: 100 900;
  font-display: swap;
  src: url({$this->getPluginUrl()}/fonts/inter/latin-italic.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
@font-face {
  font-family: 'Inter';
  font-style: normal;
  font-weight: 100 900;
  font-display: swap;
  src: url({$this->getPluginUrl()}/fonts/inter/latin-ext.woff2) format('woff2');
  unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
@font-face {
  font-family: 'Inter';
  font-style: normal;
  font-weight: 100 900;
  font-display: swap;
  src: url({$this->getPluginUrl()}/fonts/inter/latin.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
            ",
            ['inline' => true]
        );
    }

    protected function getI18nScript(): string
    {
        return 'window.slubTheme = '
            . json_encode([
                'i18n' => [
                    'reveal' => __('common.readMore'),
                ],
            ]);
    }

    /**
     * Add the script, style and other assets compiled by Vite
     */
    protected function addViteAssets(array $entryPoints): void
    {
        $templateMgr = TemplateManager::getManager(
            Application::get()->getRequest()
        );

        $viteLoader = new ViteLoader(
            templateManager: $templateMgr,
            manifestPath: dirname(__FILE__) . '/dist/.vite/manifest.json',
            serverPath: join('/', [dirname(__FILE__), '.vite.server.json']),
            buildUrl: join('/', [$this->getPluginUrl(), 'dist/']),
        );

        $viteLoader->load($entryPoints);
    }

    /**
     * Get primary genre file ids
     *
     * These ids represent primary file types, like Article Text,
     * rather than supplementary file types. These ids are required
     * to only show primary galleys with article summaries.
     */
    protected function getPrimaryFileGenreIds(int $contextId): array
    {
        /** @var GenreDAO $genreDao */
        $genreDao = DAORegistry::getDAO('GenreDAO');
        $primaryGenres = $genreDao->getPrimaryByContextId($contextId)->toArray();
        return array_map(fn($genre) => $genre->getId(), $primaryGenres);
    }
}

<?php
import('lib.pkp.classes.plugins.ThemePlugin');
import('plugins.themes.individualizeTheme.classes.IndividualizeThemeHelper');
import('plugins.themes.individualizeTheme.classes.IndividualizeThemeOptions');
import('plugins.themes.individualizeTheme.classes.IndividualizeThemeTemplatePlugin');
import('plugins.themes.individualizeTheme.classes.IndividualizeThemeViteLoader');

class IndividualizeTheme extends ThemePlugin
{
    protected IndividualizeThemeOptions $optionsHelper;
    protected IndividualizeThemeHelper $IndividualizeThemeHelper;

    public function isActive()
    {
        if (defined('SESSION_DISABLE_INIT')) return true;
        return parent::isActive();
    }

    public function init()
    {
        /**
         * Skip requests for publisher library files
         *
         * OJS loads and initializes all plugins when handling a request
         * to a public file in the publisher library. We don't initialize
         * the plugin for these requests in order to avoid unnecessary
         * database calls.
         */
        $request = Application::get()->getRequest();
        $router = $request->getRouter();
        if (is_a($router, 'PKPPageRouter') && $router->getRequestedPage($request) === 'libraryFiles') {
            return;
        }

        $this->useIndividualizeThemeHelper();
        $enabledFonts = $this->getEnabledFonts();
        $this->optionsHelper = new IndividualizeThemeOptions($this, $enabledFonts);
        $this->optionsHelper->addOptions();
        if (!$this->usesCustomFonts($enabledFonts)) {
            $this->addDefaultFont($enabledFonts);
        }
        $this->addStyle('variables', $this->optionsHelper->getCssVariablesString(), ['inline' => true, 'contexts' => ['frontend', 'htmlGalley']]);
        $this->addMenuArea(['primary', 'user', 'homepage', 'policy']);
        $this->addScript('i18n', $this->getI18nScript(), ['inline' => true]);
        $this->addViteAssets(['src/main.js']);
        $this->addViteAssets(['src/galley.js'], ['contexts' => ['htmlGalley']]);
        HookRegistry::register('TemplateManager::display', [$this, 'addTemplateData']);
    }

    public function getDisplayName()
    {
        return __('plugins.themes.individualizeTheme.name');
    }

    public function getDescription()
    {
        return __('plugins.themes.individualizeTheme.description');
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
        if (!$this->IndividualizeThemeHelper->hasParams($params, ['assign'], 'individualize_context_name_lenth')) {
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

        $templateMgr->assign([
            'themeRootUrl' => $this->getPluginUrl(),
        ]);

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
     * Whether or not this theme uses custom fonts
     *
     * Checks if Google Fonts have been enabled and the theme
     * option has been set.
     */
    public function usesCustomFonts(array $enabledFonts): bool
    {
        return count($enabledFonts)
            && (
                $this->getOption('font')
                || $this->getOption('titlesFont')
                || $this->getOption('actionsFont')
            );
    }

    /**
     * Use functions from IndividualizeThemeHelper
     *
     * These helper functions register custom template functions, add
     * useful data to templates, and provide other utilities.
     */
    protected function useIndividualizeThemeHelper(): void
    {
        $this->IndividualizeThemeHelper = new IndividualizeThemeHelper($this->getTemplateManager());
        $this->IndividualizeThemeHelper->addCommonIndividualizeThemeTemplatePlugins();
        $this->IndividualizeThemeHelper->addIndividualizeThemeTemplatePlugin(
            new IndividualizeThemeTemplatePlugin(
                type: 'function',
                name: 'individualize_context_name_length',
                callback: [$this, 'setContextNameLength']
            )
        );
    }

    /**
     * Get the TemplateManager instance
     */
    protected function getTemplateManager(): TemplateManager
    {
        return TemplateManager::getManager(Application::get()->getRequest());
    }

    /**
     * Load the default font
     */
    protected function addDefaultFont(): void
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
            [
                'inline' => true,
                'contexts' => ['frontend', 'htmlGalley'],
            ]
        );
    }

    /**
     * Adds translatable strings used by JavaScript code
     */
    protected function getI18nScript(): string
    {
        return 'window.individualizeTheme = '
            . json_encode([
                'i18n' => [
                    'reveal' => __('common.readMore'),
                ],
            ]);
    }

    /**
     * Add the script, style and other assets compiled by Vite
     *
     * @param array $args Pass arguments to ThemePlugin::addStyle() or TemplateManager::addStylesheet()
     */
    protected function addViteAssets(array $entryPoints, ?array $args = null): void
    {
        $templateMgr = TemplateManager::getManager(
            Application::get()->getRequest()
        );

        $viteLoader = new IndividualizeThemeViteLoader(
            templateManager: $templateMgr,
            manifestPath: dirname(__FILE__) . '/dist/.vite/manifest.json',
            serverPath: join('/', [dirname(__FILE__), '.vite.server.json']),
            buildUrl: join('/', [$this->getPluginUrl(), 'dist/']),
            prefix: $this->getPluginPath(),
            args: $args
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

    /**
     * Get enabled fonts from the Google Fonts plugin
     *
     * Font options rely upon the Google Fonts plugin, but the
     * theme is initialized before other generic plugins. For this
     * reason, we go directly to the database to get the Google
     * Fonts plugin's settings.
     */
    protected function getEnabledFonts(?int $contextId = null): array
    {
        if (is_null($contextId)) {
            $contextId = Application::get()->getRequest()->getContext()?->getId() ?? CONTEXT_ID_NONE;
        }
        /** @var PluginSettingsDAO $pluginSettingsDao */
        $pluginSettingsDao = DAORegistry::getDAO('PluginSettingsDAO');
        $enabledFonts = $pluginSettingsDao->getSetting($contextId, 'googlefontsplugin', 'fonts');
        if (is_array($enabledFonts)) {
            return $enabledFonts;
        }
        return [];
    }
}

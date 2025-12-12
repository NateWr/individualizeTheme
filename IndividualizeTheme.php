<?php
namespace APP\plugins\themes\individualizeTheme;

use APP\core\Application;
use APP\core\Services;
use APP\facades\Repo;
use APP\plugins\generic\citationStyleLanguage\CitationStyleLanguagePlugin;
use APP\submission\Submission;
use APP\plugins\themes\individualizeTheme\classes\FullText;
use APP\plugins\themes\individualizeTheme\classes\Options;
use APP\plugins\themes\individualizeTheme\classes\TemplatePlugin;
use APP\plugins\themes\individualizeTheme\classes\ThemeHelper;
use APP\plugins\themes\individualizeTheme\classes\ViteLoader;
use APP\template\TemplateManager;
use Closure;
use Laravel\SerializableClosure\Support\ReflectionClosure;
use PKP\core\Registry;
use PKP\db\DAORegistry;
use PKP\galley\Galley;
use PKP\plugins\Hook;
use PKP\plugins\ThemePlugin;
use PKP\security\Role;
use PKP\plugins\PluginRegistry;
use PKP\submissionFile\SubmissionFile;

class IndividualizeTheme extends ThemePlugin
{
    protected Options $optionsHelper;
    protected ThemeHelper $themeHelper;

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
        $this->optionsHelper = new Options($this, $enabledFonts);
        $this->optionsHelper->addOptions();
        if (!$this->usesCustomFonts($enabledFonts)) {
            $this->addDefaultFont($enabledFonts);
        }
        $this->addStyle('variables', $this->optionsHelper->getCssVariablesString(), ['inline' => true, 'contexts' => ['frontend', 'htmlGalley']]);
        $this->addMenuArea(['primary', 'user', 'homepage', 'policy']);
        $this->addScript('i18n', $this->getI18nScript(), ['inline' => true]);
        $this->addViteAssets(['src/main.js']);
        $this->addViteAssets(['src/galley.js'], ['contexts' => ['htmlGalley']]);
        Hook::add('TemplateManager::display', [$this, 'addTemplateData']);
        Hook::add('IndividualizeTheme::FullText', [$this, 'addArticleFullText']);
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
        if (!$this->themeHelper->hasParams($params, ['assign'], 'individualize_context_name_lenth')) {
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
            if ($context) {
                $templateMgr->assign([
                    'authorUserGroups' => Repo::userGroup()->getByRoleIds([Role::ROLE_ID_AUTHOR], $context->getId()),
                ]);

                $this->removeHowToCiteDefault();

                $article = $templateMgr->getTemplateVars('article');
                if ($article) {
                    $this->displayUsageStatsGraph($article->getId());
                }

                /** @var OpenScienceBadgesPlugin $plugin */
                $plugin = PluginRegistry::getPlugin('generic', 'opensciencebadgesplugin');
                if (
                    $plugin
                    && $plugin->getEnabled($context->getId())
                    && $plugin->getSetting($context->getId(), $plugin::SETTING_LOCATION) === $plugin::LOCATION_NONE
                ) {
                    $publication = $templateMgr->getTemplateVars('publication');
                    $size = $plugin->getSetting($context->getId(), $plugin::SETTING_SIZE);
                    $templateMgr->assign([
                        'openScienceBadges' => $size === $plugin::SIZE_LARGE
                            ? $plugin->getLargeBadgesHTML($publication, $templateMgr)
                            : $plugin->getSmallBadgesHTML($publication, $templateMgr),
                    ]);
                }
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
     * Disable the the default output of the CitationStyleLanguage
     * plugin.
     *
     * It disables the scripts and styles and removes the callback which
     * is hooked to Templates::Article::Details to inject the how to
     * cite block on the article landing page.
     *
     * This theme shows its own markup for the How to Cite block and
     * uses its own styles and scripts to support displaying it in
     * two locations.
     */
    public function removeHowToCiteDefault(): void
    {
        $request = Application::get()->getRequest();
        $templateMgr = TemplateManager::getManager($request);

        $templateMgr->addJavaScript('citationStyleLanguage', '', ['inline' => true]);
        $templateMgr->addStyleSheet('cslPluginStyles', '', [
            'priority' => TemplateManager::STYLE_SEQUENCE_LAST,
            'inline' => true,
            'contexts' => ['frontend']
        ]);

        $hooks = Hook::getHooks();

        if (empty($hooks['Templates::Article::Details'])) {
            return;
        }

        foreach ($hooks['Templates::Article::Details']['hooks'] as $priority => $callbacks) {
            foreach ($callbacks as $key => $callback) {
                if (is_object($callback) && is_a($callback, Closure::class)) {
                    $reflectionClosure = new ReflectionClosure($callback);
                    if ($reflectionClosure->getClosureScopeClass()->getName() === CitationStyleLanguagePlugin::class) {
                        unset($hooks['Templates::Article::Details']['hooks'][$priority][$key]);
                        Registry::set('hooks', $hooks);
                        break;
                    }
                }
            }
        }
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
     * Add the full text to the article landing page if a HTML
     * galley exists
     */
    public function addArticleFullText(string $hookName, array $args): bool
    {
        if ($this->getOption('articleFullText') !== Options::ARTICLE_FULLTEXT_SHOW) {
            return false;
        }

        $request = Application::get()->getRequest();
        $templateMgr = TemplateManager::getManager($request);
        $submission = $templateMgr->getTemplateVars('article');
        $galley = $this->getHtmlGalley($templateMgr);

        if (!$galley) {
            return false;
        }

        $file = Repo::submissionFile()->get($galley->getData('submissionFileId'));

        if (!$file) {
            return false;
        }

        $content = $this->getFileContent($file);

        if (!$content) {
            return false;
        }

        $activeTheme = TemplateManager::getManager(Application::get()->getRequest())->getTemplateVars('activeTheme');
        $themeWithExtractor = $this->getThemeWithFullTextExtractor($activeTheme);

        if (!$themeWithExtractor) {
            return false;
        }

        $extractor = $themeWithExtractor->getFullTextExtractor($content, $galley, $file, $submission);

        if (!$extractor) {
            return false;
        }

        $article = $extractor->getArticleContent();

        if (!$article) {
            return false;
        }

        $references = $extractor->getReferencesContent();

        $templateMgr->assign([
            'fullTextHtml' => $article,
            'fullTextReferences' => $references,
            'fullTextTableOfContents' => $extractor->getTableOfContents($article, (bool) $references),
        ]);

        $templateMgr->display($this->getTemplateResource('frontend/components/article-full-text.tpl'));

        return false;
    }

    /**
     * Whether or not this theme supports full-text extraction
     * from HTML galleys
     *
     * Return false to disable full-text extraction and remove
     * the theme option.
     *
     * @see self::addArticleFullText()
     */
    public function supportsArticleFullText(): bool
    {
        return true;
    }

    /**
     * Recursively check the active theme and all parent
     * themes' support for article full text
     */
    public function getThemeSupportsArticleFullText(ThemePlugin $theme): bool
    {
        while ($theme) {
            if (method_exists($theme, 'supportsArticleFullText')) {
                return $theme->supportsArticleFullText();
            }
            $theme = $theme->parent;
        }
        return false;
    }

    /**
     * Get the helper class to extract full-text HTML
     * from a HTML galley
     *
     * Override this method in a child theme to adapt the
     * extraction code to your HTML structure.
     *
     * @see FullText
     */
    public function getFullTextExtractor(string $html, Galley $galley, SubmissionFile $galleyFile, Submission $submission): ?FullText
    {
        return new FullText($html, $galley, $galleyFile, $submission);
    }

    /**
     * Recursively check the active theme and all
     * parent themes for a full text extractor
     */
    public function getThemeWithFullTextExtractor(ThemePlugin $theme): ?ThemePlugin
    {
        while ($theme) {
            if (method_exists($theme, 'getFullTextExtractor')) {
                return $theme;
            }
            $theme = $theme->parent;
        }
        return null;
    }

    /**
     * Use functions from themeHelper
     *
     * These helper functions register custom template functions, add
     * useful data to templates, and provide other utilities.
     */
    protected function useIndividualizeThemeHelper(): void
    {
        $this->themeHelper = new ThemeHelper($this->getTemplateManager());
        $this->themeHelper->addCommonTemplatePlugins();
        $this->themeHelper->addTemplatePlugin(
            new TemplatePlugin(
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
                    'nextSlide' => __('plugins.themes.individualizeTheme.nextSlide'),
                    'prevSlide' => __('plugins.themes.individualizeTheme.prevSlide'),
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

        $viteLoader = new ViteLoader(
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
            $contextId = Application::get()->getRequest()->getContext()?->getId() ?? Application::SITE_CONTEXT_ID;
        }
        /** @var PluginSettingsDAO $pluginSettingsDao */
        $pluginSettingsDao = DAORegistry::getDAO('PluginSettingsDAO');
        $enabledFonts = $pluginSettingsDao->getSetting($contextId, 'googlefontsplugin', 'fonts');
        if (is_array($enabledFonts)) {
            return $enabledFonts;
        }
        return [];
    }

    /**
     * Get the first primary galley that is a HTML file
     * from the template variables
     */
    protected function getHtmlGalley(TemplateManager $templateMgr): ?Galley
    {
        $galleys = $templateMgr->getTemplateVars('primaryGalleys');
        foreach ($galleys as $galley) {
            if ($galley->getFile()?->getData('mimetype') === 'text/html') {
                return $galley;
            }
        }
        return null;
    }

    /**
     * Get the content of a galley file
     */
    protected function getFileContent(SubmissionFile $file): string
    {
        return Services::get('file')->fs->read($file->getData('path'));
    }
}

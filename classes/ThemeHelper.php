<?php
namespace APP\plugins\themes\individualizeTheme\classes;

use APP\core\Application;
use APP\template\TemplateManager;
use Exception;
use PKP\facades\Locale;
use PKP\i18n\LocaleMetadata;
use PKP\plugins\Hook;

/**
 * A helper class for building custom themes for
 * PKP's scholarly publishing software (OJS, OMP,
 * and OPS).
 *
 * Typically used to register helper functions
 * with the TemplateManager, for use in .tpl files.
 *
 * The TemplateManager class is an instance of the
 * Smarty templating library.
 *
 * @see https://www.smarty.net
 */
class ThemeHelper
{
    /**
     * Number of page buttons to display before truncating
     * the list of pages
     */
    public const DEFAULT_MAX_PAGES = 9;

    /**
     * @var TemplatePlugin[]
     */
    protected array $templatePlugins = [];

    public function __construct(
        protected TemplateManager $templateMgr
    ) {
        $this->templateMgr = $templateMgr;
        Hook::add('TemplateManager::display', [$this, 'registerTemplatePlugins']);
    }

    /**
     * Register common helper functions with the Smarty
     * template manager
     *
     * @param TemplateManager $templateMgr
     */
    public function addCommonTemplatePlugins(): void
    {
        $this->addTemplatePlugin(
            new TemplatePlugin(
                type: 'function',
                name: 'th_locales',
                callback: [$this, 'setLocales']
            )
        );
        $this->addTemplatePlugin(
            new TemplatePlugin(
                type: 'function',
                name: 'th_filter_galleys',
                callback: [$this, 'filterGalleys']
            )
        );
        $this->addTemplatePlugin(
            new TemplatePlugin(
                type: 'function',
                name: 'th_pagination',
                callback: [$this, 'getPages']
            )
        );
    }

    /**
     * Add a template plugin
     */
    public function addTemplatePlugin(TemplatePlugin $plugin): void
    {
        $this->templatePlugins[] = $plugin;
    }

    /**
     * Register template plugins
     *
     * This method is called with the TemplateManager::display
     * hook in order to ensure that the template plugins are
     * registered after all core plugins have been registered.
     *
     * This allows core plugins to be overridden.
     */
    public function registerTemplatePlugins(string $hookName, array $args): bool
    {
        foreach ($this->templatePlugins as $plugin) {
            $this->safeRegisterIndividualizeThemeTemplatePlugin($plugin);
        }
        return false;

    }

    /**
     * Register a smarty plugin safely
     *
     * This wrapper function prevents a fatal error if a smarty plugin
     * with the same name has already been registered.
     */
    protected function safeRegisterIndividualizeThemeTemplatePlugin(TemplatePlugin $plugin): void
    {
        $registered = isset($this->templateMgr->registered_plugins[$plugin->type][$plugin->name]);
        if ($registered && $plugin->override) {
            $this->templateMgr->unregisterPlugin($plugin->type, $plugin->name);
            $this->templateMgr->registerPlugin($plugin->type, $plugin->name, $plugin->callback);
        } elseif (!$registered) {
            $this->templateMgr->registerPlugin($plugin->type, $plugin->name, $plugin->callback);
        }
    }

    /**
     * Set the locales supported by the journal or site
     *
     * @example {th_locales
     *   assign="languages"
     * }
     * @param array $params
     *   @option string assign Variable to assign the result to
     */
    public function setLocales(array $params, $smarty): void
    {
        if (!$this->hasParams($params, ['assign'], 'th_locales')) {
            return;
        }

        $request = Application::get()->getRequest();
        $context = $request->getContext();

        $locales = Locale::getFormattedDisplayNames(
            isset($context)
                ? $context->getSupportedLocales()
                : $request->getSite()->getSupportedLocales(),
            Locale::getLocales(),
            LocaleMetadata::LANGUAGE_LOCALE_ONLY
        );

        $smarty->assign($params['assign'], $locales);
    }

    /**
     * Filter a list of galleys by genreId and remoteUrl
     *
     * @example {th_filter_galleys
     *   assign="galleys"
     *   galleys=$article->getGalleys()
     *   genreIds=$primaryGenreIds
     *   remotes=true
     * }
     * @param array $params
     *   @option string assign Variable to assign the result to
     *   @option Galley[] galleys List of galleys to filter
     *   @option int[] genreIds List of genres to include in result
     *   @option bool remotes Whether to include galleys with remote urls
     */
    public function filterGalleys(array $params, $smarty): void
    {
        if (!$this->hasParams($params, ['assign', 'galleys'], 'th_filter_galleys')) {
            return;
        }

        $galleys = (array) $params['galleys'];
        $genreIds = isset($params['genreIds']) ? (array) $params['genreIds'] : [];
        $remotes = isset($params['remotes']) ? (bool) $params['remotes'] : false;

        $filteredGalleys = collect([]);

        foreach ($galleys as $galley) {
            if ($galley->getData('urlRemote') && $remotes) {
                $filteredGalleys->push($galley);
                continue;
            }
            $file = $galley->getFile();
            if (!$file) {
                continue;
            }
            if (!count($genreIds) || in_array($file->getGenreId(), $genreIds)) {
                $filteredGalleys->push($galley);
                continue;
            }
        }

        $smarty->assign($params['assign'], $filteredGalleys);
    }

    /**
     * Throw an exception if any of the required parameters
     * are missing from the array.
     *
     * Expects params a [key => value] map that is part
     * of all custom Smarty functions.
     *
     * @param array $params A [key => value] map. Typically c
     * @param string[] $requiredParams List of required param keys.
     * @throws Exception
     */
    public function hasParams(array $params, array $requiredParams, string $function): bool
    {
        foreach ($requiredParams as $requiredParam) {
            if (empty($params[$requiredParam])) {
                $exampleParams = join(
                  " ",
                  array_map(fn($param) => "{$param}=\"...\"", $requiredParams)
                );
                throw new Exception(
                    "Call to {{$function} without the required `{$requiredParam}` parameter. Usage: {{$function} {$exampleParams}}"
                );
            }
        }
        return true;
    }

    /**
     * Get an array of page numbers for pagination components
     *
     * The list of page numbers will be truncated so that it is
     * no more than maxPages. Skipped pages are returned as -1 in
     * the page list.
     *
     * Example result:
     *
     * assignCurrent = 23
     * assignPages = [1, -1, 21, 22, 23, 24, 25, -1, 82]
     *
     * @example {th_pagination
     *   assignPages="pages"
     *   assignCurrent="current"
     *   perPage="10"
     *   total="200"
     *   start="1"
     * }
     * @param array $params
     *   @option string assignPages Variable to assign the list of page numbers
     *   @option string assignCurrent Variable to assign the current page to
     *   @option int perPage Number of items on each page
     *   @option int total Total number of items
     *   @option int start Number of first item on current page, eg - 21 = 21st item in total list of items
     *   @option int maxPages (Optional) Max number of page numbers to show at once
     */
    public function getPages(array $params, $smarty): void
    {
        if (!$this->hasParams($params, ['assignPages', 'assignCurrent', 'perPage', 'total', 'start'], 'th_pagination')) {
            return;
        }

        $perPage = (int) $params['perPage'];
        $start = (int) $params['start'];
        $total = (int) $params['total'];
        $maxPages = $params['maxPages']
            ? (int) $params['maxPages']
            : self::DEFAULT_MAX_PAGES;

        $currentPage = (int) ceil($start / $perPage);
        $totalPages = (int) ceil($total / $perPage);

        $pages = [];
        for ($i = 1; $i <= $totalPages; $i++) {
            $pages[] = $i;
        }

        if ($totalPages > $maxPages) {
            if ($currentPage <= $maxPages - 4) {
                $pages = array_filter(
                    array_merge(
                        array_slice($pages, 0, $maxPages - 2),
                        [-1],
                        array_slice($pages, $totalPages - 1),
                    )
                );
            } elseif ($currentPage >= ($totalPages - 4)) {
                $end = $totalPages - $maxPages + 2;
                $pages = array_filter(
                    array_merge(
                        array_slice($pages, 0, 1),
                        [-1],
                        array_slice($pages, $end),
                    )
                );
            } else {
                $front = $currentPage - floor($maxPages / 2) + 1;
                $pages = array_filter(
                    array_merge(
                        array_slice($pages, 0, 1),
                        [-1],
                        array_slice($pages, $front, $maxPages - 4),
                        [-1],
                        array_slice($pages, -1),
                    )
                );
            }
        }

        $smarty->assign([
            $params['assignPages'] => $pages,
            $params['assignCurrent'] => $currentPage,
        ]);
    }
}
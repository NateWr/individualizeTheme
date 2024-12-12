<?php

namespace APP\plugins\themes\individualizeTheme\classes;

use APP\core\Application;
use APP\facades\Repo;
use APP\plugins\themes\individualizeTheme\IndividualizeTheme;
use APP\submission\Collector;
use APP\submission\Submission;
use APP\template\TemplateManager;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use PKP\db\DAORegistry;
use PKP\plugins\PluginRegistry;

/**
 * Helper class to define and register theme options
 * for IndividualizeTheme
 */
class Options
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

    public const HOMEPAGE_BLOCK_ANNOUNCEMENT = 'frontend/components/homepage-blocks/announcement.tpl';
    public const HOMEPAGE_BLOCK_ABOUT = 'frontend/components/homepage-blocks/about.tpl';
    public const HOMEPAGE_BLOCK_ISSUE_SUMMARY = 'frontend/components/homepage-blocks/issue-summary.tpl';
    public const HOMEPAGE_BLOCK_ISSUE_TOC = 'frontend/components/homepage-blocks/issue-toc.tpl';
    public const HOMEPAGE_BLOCK_SUBMIT = 'frontend/components/homepage-blocks/how-to-submit.tpl';
    public const HOMEPAGE_BLOCK_LATEST_ARTICLES = 'frontend/components/homepage-blocks/latest-articles.tpl';
    public const HOMEPAGE_BLOCK_BROWSE_BY_CATEGORY = 'frontend/components/homepage-blocks/browse-by-category.tpl';
    public const HOMEPAGE_BLOCK_PARTNERS = 'frontend/components/homepage-blocks/partners.tpl';
    public const HOMEPAGE_BLOCKS_DEFAULT = [
        self::HOMEPAGE_BLOCK_ANNOUNCEMENT,
        self::HOMEPAGE_BLOCK_ABOUT,
        self::HOMEPAGE_BLOCK_ISSUE_SUMMARY,
        self::HOMEPAGE_BLOCK_SUBMIT,
    ];

    public const SHOW_AUTHORS_AUTO = '';
    public const SHOW_AUTHORS_SIMPLE = 'simple';
    public const SHOW_AUTHORS_DETAILED = 'detailed';

    public const ARTICLE_METADATA_DOI = 'doi';
    public const ARTICLE_METADATA_KEYWORDS = 'keywords';
    public const ARTICLE_METADATA_PUBLISHED = 'published';
    public const ARTICLE_METADATA_PUBLISHED_BY = 'published-by';
    public const ARTICLE_METADATA_HOW_TO_CITE = 'how-to-cite';
    public const ARTICLE_METADATA_DEFAULT = [
        self::ARTICLE_METADATA_DOI,
        self::ARTICLE_METADATA_KEYWORDS,
        self::ARTICLE_METADATA_PUBLISHED,
        self::ARTICLE_METADATA_PUBLISHED_BY,
    ];

    public const ISSUE_ARCHIVE_DEFAULT = 'default';
    public const ISSUE_ARCHIVE_COVERS = 'covers';
    public const ISSUE_ARCHIVE_NO_COVERS = 'no-covers';

    public const FONT_DEFAULT = 'inter';

    public const COLOR_MODE_DEFAULT = 'default';
    public const COLOR_MODE_ADVANCED = 'advanced';

    public const COLOR_PRIMARY = '#1E102B';
    public const COLOR_ACCENT = '#BC3200';
    public const COLOR_PAGE_BACKGROUND = '#ffffff';
    public const COLOR_PAGE_TEXT = '#000000';
    public const COLOR_PRIMARY_TEXT = '#ffffff';

    /**
     * Primary locale of current context
     */
    protected string $primaryLocale;

    public function __construct(
        /**
         * Instance of the theme
         */
        protected IndividualizeTheme $theme,

        /**
         * List of enabled fonts
         *
         * From the Google Fonts plugin. Empty array
         * if the plugin is disabled or no fonts have
         * been added through the plugin.
         */
        protected array $enabledFonts
    ) {
        $request = Application::get()->getRequest();
        $context = $request->getContext();
        $this->primaryLocale = $context
            ? $context->getPrimaryLocale()
            : $request->getSite()->getPrimaryLocale();
    }

    /**
     * Add all theme options
     */
    public function addOptions(): void
    {
        $this->addHeaderOption();
        $this->addTaglineOption();
        $this->addHomepageImageOption();
        $this->addHomepageBlocksOption();
        $this->addHowToSubmitBlock();
        $this->addPartnersBlock();
        $this->addCategoriesBlock();
        $this->addLatestArticlesBlock();
        $this->addArticleAuthorsOption();
        $this->addArticleMetadataOption();
        $this->addIssueArchivesOption();
        $this->addFontOptions();
        $this->addColorOptions();
    }

    /**
     * Add CSS variables based on the theme options
     */
    public function getCssVariables(): Collection
    {
        $variables = new Collection([]);

        if ($this->theme->getOption('colorMode') === self::COLOR_MODE_DEFAULT) {
            $variables['--color-primary'] = $this->theme->getOption('primaryColor');
            $variables['--color-secondary'] = $this->theme->getOption('accentColor');
            if ($this->theme->isColourDark($this->theme->getOption('primaryColor'))) {
                $variables['--color-text-on-primary'] = 'white';
                $variables['--color-button-text'] = 'var(--color-primary)';
            } else {
                $variables['--color-text-on-primary'] = 'rgba(0, 0, 0, 0.85)';
            }
            if ($this->theme->isColourDark($this->theme->getOption('accentColor'))) {
                $variables['--color-page-links'] = 'var(--color-secondary)';
                $variables['--color-button-text'] = 'var(--color-secondary)';
            } else {
                $variables['--color-page-links'] = 'var(--color-text)';
                $variables['--color-button-text'] = 'var(--color-text)';
            }
            $variables['--color-header-background'] = 'var(--color-primary)';
            $variables['--color-header-text'] = 'var(--color-text-on-primary)';
            $variables['--color-button-background'] = 'var(--color-background)';
            $variables['--color-block-background'] = 'var(--color-primary)';
            $variables['--color-block-text'] = 'var(--color-text-on-primary)';
            $variables['--color-footer-background'] = 'var(--color-primary)';
            $variables['--color-footer-text'] = 'var(--color-text-on-primary)';
        } else {
            $variables['--color-header-background'] = $this->theme->getOption('headerBackgroundColor');
            $variables['--color-header-text'] = $this->theme->getOption('headerTextColor');
            $variables['--color-page-background'] = $this->theme->getOption('pageBackgroundColor');
            $variables['--color-page-text'] = $this->theme->getOption('pageTextColor');
            $variables['--color-page-links'] = $this->theme->getOption('pageLinkColor');
            $variables['--color-button-background'] = $this->theme->getOption('buttonBackgroundColor');
            $variables['--color-button-text'] = $this->theme->getOption('buttonTextColor');
            $variables['--color-block-background'] = $this->theme->getOption('blockBackgroundColor');
            $variables['--color-block-text'] = $this->theme->getOption('blockTextColor');
            $variables['--color-overlay-background'] = $this->theme->getOption('blockBackgroundColor');
            $variables['--color-overlay-text'] = $this->theme->getOption('blockTextColor');
            $variables['--color-footer-background'] = $this->theme->getOption('footerBackgroundColor');
            $variables['--color-footer-text'] = $this->theme->getOption('footerTextColor');
        }

        if ($this->theme->usesCustomFonts($this->enabledFonts)) {
            foreach ($this->enabledFonts as $font) {
                if ($font['id'] === $this->theme->getOption('font')) {
                    $variables['--font-base'] = "'{$font['family']}', {$this->getFontFallback($font['category'])}";
                }
                if ($font['id'] === $this->theme->getOption('titlesFont')) {
                    $variables['--font-titles'] = "'{$font['family']}', {$this->getFontFallback($font['category'])}";
                }
                if ($font['id'] === $this->theme->getOption('actionsFont')) {
                    $variables['--font-actions'] = "'{$font['family']}', {$this->getFontFallback($font['category'])}";
                }
            }
        }

        return $variables;
    }

    /**
     * Get a CSS string that assigns all variables to
     * the passed CSS selector
     *
     * For example, if $selector='body' it will return:
     *
     * body {
     *    // variables
     * }
     */
    public function getCssVariablesString(string $selector = 'body'): string
    {
        $string = $this->getCssVariables()
            ->map(fn($val, $var) => "{$var}: {$val};")
            ->join('');

        return "{$selector} {{$string}}";
    }

    /**
     * Load the homepage blocks
     *
     * This method returns a list of templates to load the homepage blocks
     * and adds any required data to the TemplateManager.
     */
    public function getHomepageBlocks(): array
    {
        $request = Application::get()->getRequest();
        $context = $request->getContext();
        $templateMgr = TemplateManager::getManager($request);

        $blocks = $this->theme->getOption('homepageBlocks');
        $templateMgr->assign('homepageBlocks', $blocks);

        foreach ($blocks as $template) {
            switch ($template) {
                case self::HOMEPAGE_BLOCK_ISSUE_TOC:
                    $pubIdPlugins = PluginRegistry::loadCategory('pubIds', true);
                    $templateMgr->assign('pubIdPlugins', $pubIdPlugins);
                    break;
                case self::HOMEPAGE_BLOCK_LATEST_ARTICLES:
                    $templateMgr->assign('individualizeLatestArticles', $this->getLatestArticles()->values()->toArray());
                    break;
                case self::HOMEPAGE_BLOCK_PARTNERS:
                    /** @var PartnerLogosPlugin */
                    $partnerLogosPlugin = PluginRegistry::getPlugin('generic', 'partnerlogosplugin');
                    if ($partnerLogosPlugin && $context) {
                        $templateMgr->assign('partnerLogosHtml', $partnerLogosPlugin->getHtml($context));
                    }
                    break;
                case self::HOMEPAGE_BLOCK_BROWSE_BY_CATEGORY:
                    if ($context) {
                        $templateMgr->assign(
                            'categories',
                            Repo::category()
                                ->getCollector()
                                ->filterByContextIds([$context->getId()])
                                ->filterByParentIds([null])
                                ->getMany()
                                ->values()
                                ->toArray()
                        );
                    }
                    break;
            }
        }

        return $blocks;
    }

    /**
     * Helper function to get all homepage block options
     */
    public function getHomepageBlockOptions(): Collection
    {
        return collect([
            [
                'value' => self::HOMEPAGE_BLOCK_ANNOUNCEMENT,
                'label' => __('plugins.themes.individualizeTheme.option.homepageBlocks.announcement'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_ABOUT,
                'label' => __('plugins.themes.individualizeTheme.option.homepageBlocks.about'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_ISSUE_SUMMARY,
                'label' => __('plugins.themes.individualizeTheme.option.homepageBlocks.issue-summary'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_ISSUE_TOC,
                'label' => __('plugins.themes.individualizeTheme.option.homepageBlocks.issue-toc'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_SUBMIT,
                'label' => __('plugins.themes.individualizeTheme.option.homepageBlocks.how-to-submit'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_LATEST_ARTICLES,
                'label' => __('plugins.themes.individualizeTheme.latestArticles'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_BROWSE_BY_CATEGORY,
                'label' => __('plugins.themes.individualizeTheme.option.homepageBlocks.browse-by-category'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_PARTNERS,
                'label' => __('plugins.themes.individualizeTheme.option.homepageBlocks.partners'),
            ],
        ]);

        return $blocks;
    }

    /**
     * Add option for the header layout
     */
    protected function addHeaderOption(): void
    {
        $this->theme->addOption('header', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.individualizeTheme.option.header.label'),
            'description' => __('plugins.themes.individualizeTheme.option.header.description'),
            'options' => [
                [
                    'value' => self::HEADER_DEFAULT,
                    'label' => __('plugins.themes.individualizeTheme.option.header.default'),
                ],
                [
                    'value' => self::HEADER_CENTER,
                    'label' => __('plugins.themes.individualizeTheme.option.header.default-center'),
                ],
                [
                    'value' => self::HEADER_LINE,
                    'label' => __('plugins.themes.individualizeTheme.option.header.line'),
                ],
            ],
            'default' => self::HEADER_DEFAULT,
        ]);
    }

    /**
     * Add option for the tagline to display beside the logo
     */
    protected function addTaglineOption(): void
    {
        $this->theme->addOption('tagline', 'FieldText', [
            'label' => __('plugins.themes.individualizeTheme.option.tagline.label'),
            'description' => __('plugins.themes.individualizeTheme.option.tagline.description'),
            'isMultilingual' => true,
        ]);
    }

    /**
     * Add option for where to display the homepage image
     */
    protected function addHomepageImageOption(): void
    {
        $this->theme->addOption('homepageImagePosition', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.individualizeTheme.option.homepageImagePosition.label'),
            'description' => __('plugins.themes.individualizeTheme.option.homepageImagePosition.description'),
            'options' => [
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_ABOVE,
                    'label' => __('plugins.themes.individualizeTheme.option.homepageImagePosition.above'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_ABOVE_CENTER,
                    'label' => __('plugins.themes.individualizeTheme.option.homepageImagePosition.above-center'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BEHIND,
                    'label' => __('plugins.themes.individualizeTheme.option.homepageImagePosition.behind'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BEHIND_RIGHT_TOP,
                    'label' => __('plugins.themes.individualizeTheme.option.homepageImagePosition.behind-right-top'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BEHIND_RIGHT_CENTER,
                    'label' => __('plugins.themes.individualizeTheme.option.homepageImagePosition.behind-right-center'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BEHIND_RIGHT_BOTTOM,
                    'label' => __('plugins.themes.individualizeTheme.option.homepageImagePosition.behind-right-bottom'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BEHIND_PATTERN,
                    'label' => __('plugins.themes.individualizeTheme.option.homepageImagePosition.behind-pattern'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BELOW,
                    'label' => __('plugins.themes.individualizeTheme.option.homepageImagePosition.below'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BELOW_CENTER,
                    'label' => __('plugins.themes.individualizeTheme.option.homepageImagePosition.below-center'),
                ],
            ],
            'default' => self::HOMEPAGE_IMAGE_POSITION_ABOVE,
        ]);
    }

    /**
     * Add option to select which blocks to show on the homepage
     */
    protected function addHomepageBlocksOption(): void
    {
        $this->theme->addOption('homepageBlocks', 'FieldOptions', [
            'type' => 'checkbox',
            'isOrderable' => true,
            'label' => __('plugins.themes.individualizeTheme.option.homepageBlocks.label'),
            'description' => __('plugins.themes.individualizeTheme.option.homepageBlocks.description'),
            'options' => $this->getHomepageBlockOptions()->toArray(),
            'default' => self::HOMEPAGE_BLOCKS_DEFAULT,
        ]);
    }

    /**
     * Add text fields for the how to submit homepage block
     */
    protected function addHowToSubmitBlock(): void
    {
        $this->theme->addOption('howToSubmitTitle', 'FieldText', [
            'label' => __('plugins.themes.individualizeTheme.option.howToSubmitTitle.label'),
            'description' => __('plugins.themes.individualizeTheme.option.howToSubmitTitle.description'),
            'isMultilingual' => true,
            'default' => [
                $this->primaryLocale => __('navigation.submissions'),
            ],
        ]);
        $this->theme->addOption('howToSubmitText', 'FieldText', [
            'label' => __('plugins.themes.individualizeTheme.option.howToSubmitText.label'),
            'description' => __('plugins.themes.individualizeTheme.option.howToSubmitText.description'),
            'isMultilingual' => true,
            'size' => 'large',
            'default' => [
                $this->primaryLocale => __('plugins.themes.individualizeTheme.option.howToSubmitText.default'),
            ],
        ]);
        $this->theme->addOption('howToSubmitAction', 'FieldText', [
            'label' => __('plugins.themes.individualizeTheme.option.howToSubmitAction.label'),
            'description' => __('plugins.themes.individualizeTheme.option.howToSubmitAction.description'),
            'isMultilingual' => true,
            'size' => 'small',
            'default' => [
                $this->primaryLocale => __('plugins.themes.individualizeTheme.option.howToSubmitAction.default'),
            ],
        ]);
    }

    /**
     * Add title and description fields for the partners homepage block
     */
    protected function addPartnersBlock(): void
    {
        $this->theme->addOption('partnersTitle', 'FieldText', [
            'label' => __('plugins.themes.individualizeTheme.option.partnersTitle.label'),
            'description' => __('plugins.themes.individualizeTheme.option.partnersTitle.description'),
            'isMultilingual' => true,
            'default' => [
                $this->primaryLocale => __('plugins.themes.individualizeTheme.partners'),
            ],
        ]);
        $this->theme->addOption('partnersDescription', 'FieldText', [
            'label' => __('plugins.themes.individualizeTheme.option.partnersDescription.label'),
            'description' => __('plugins.themes.individualizeTheme.option.partnersDescription.description'),
            'isMultilingual' => true,
            'size' => 'large',
            'default' => [
                $this->primaryLocale => __('plugins.themes.individualizeTheme.partners.description'),
            ],
        ]);
    }

    /**
     * Add title and description fields for the browse by categories homepage block
     */
    protected function addCategoriesBlock(): void
    {
        $this->theme->addOption('categoriesTitle', 'FieldText', [
            'label' => __('plugins.themes.individualizeTheme.option.categoriesTitle.label'),
            'description' => __('plugins.themes.individualizeTheme.option.categoriesTitle.description'),
            'isMultilingual' => true,
            'default' => [
                $this->primaryLocale => __('plugins.themes.individualizeTheme.browseByCategory'),
            ],
        ]);
        $this->theme->addOption('categoriesDescription', 'FieldText', [
            'label' => __('plugins.themes.individualizeTheme.option.categoriesDescription.label'),
            'description' => __('plugins.themes.individualizeTheme.option.categoriesDescription.description'),
            'isMultilingual' => true,
            'size' => 'large',
            'default' => [
                $this->primaryLocale => __('plugins.themes.individualizeTheme.browseByCategory.description'),
            ],
        ]);
    }

    /**
     * Add title and description fields for the latest articles homepage block
     */
    protected function addLatestArticlesBlock(): void
    {
        $this->theme->addOption('latestArticlesTitle', 'FieldText', [
            'label' => __('plugins.themes.individualizeTheme.option.latestArticlesTitle.label'),
            'description' => __('plugins.themes.individualizeTheme.option.latestArticlesTitle.description'),
            'isMultilingual' => true,
            'default' => [
                $this->primaryLocale => __('plugins.themes.individualizeTheme.latestArticles'),
            ],
        ]);
        $this->theme->addOption('latestArticlesDescription', 'FieldText', [
            'label' => __('plugins.themes.individualizeTheme.option.latestArticlesDescription.label'),
            'description' => __('plugins.themes.individualizeTheme.option.latestArticlesDescription.description'),
            'isMultilingual' => true,
            'size' => 'large',
            'default' => [
                $this->primaryLocale => __('plugins.themes.individualizeTheme.latestArticles.description'),
            ],
        ]);
    }

    /**
     * Add option to change how authors are displayed on
     * the article landing page
     */
    protected function addArticleAuthorsOption(): void
    {
        $this->theme->addOption('showAuthors', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.individualizeTheme.option.showAuthors.label'),
            'description' => __('plugins.themes.individualizeTheme.option.showAuthors.description'),
            'options' => [
                [
                    'value' => self::SHOW_AUTHORS_AUTO,
                    'label' => __('plugins.themes.individualizeTheme.option.showAuthors.auto'),
                ],
                [
                    'value' => self::SHOW_AUTHORS_SIMPLE,
                    'label' => __('plugins.themes.individualizeTheme.option.showAuthors.simple'),
                ],
                [
                    'value' => self::SHOW_AUTHORS_DETAILED,
                    'label' => __('plugins.themes.individualizeTheme.option.showAuthors.detailed'),
                ],
            ],
            'default' => self::SHOW_AUTHORS_AUTO,
        ]);
    }

    /**
     * Add option to highlight some metadata at the top
     * of the article landing page
     */
    protected function addArticleMetadataOption(): void
    {
        $this->theme->addOption('highlightArticleMetadata', 'FieldOptions', [
            'type' => 'checkbox',
            'isOrderable' => true,
            'label' => __('plugins.themes.individualizeTheme.option.highlightArticleMetadata.label'),
            'description' => __('plugins.themes.individualizeTheme.option.highlightArticleMetadata.description'),
            'options' => [
                [
                    'value' => self::ARTICLE_METADATA_DOI,
                    'label' => __('metadata.property.displayName.doi'),
                ],
                [
                    'value' => self::ARTICLE_METADATA_KEYWORDS,
                    'label' => __('common.keywords'),
                ],
                [
                    'value' => self::ARTICLE_METADATA_PUBLISHED,
                    'label' => __('metadata.property.displayName.date'),
                ],
                [
                    'value' => self::ARTICLE_METADATA_PUBLISHED_BY,
                    'label' => __('plugins.themes.individualizeTheme.option.highlightArticleMetadata.published-by'),
                ],
                [
                    'value' => self::ARTICLE_METADATA_HOW_TO_CITE,
                    'label' => __('submission.howToCite'),
                ],
            ],
            'default' => self::ARTICLE_METADATA_DEFAULT,
        ]);
    }

    /**
     * Add option to change how issues are displayed in
     * the issue archive
     */
    protected function addIssueArchivesOption(): void
    {
        $this->theme->addOption('issueArchives', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.individualizeTheme.option.issueArchives.label'),
            'description' => __('plugins.themes.individualizeTheme.option.issueArchives.description'),
            'options' => [
                [
                    'value' => self::ISSUE_ARCHIVE_DEFAULT,
                    'label' => __('plugins.themes.individualizeTheme.option.issueArchives.default'),
                ],
                [
                    'value' => self::ISSUE_ARCHIVE_COVERS,
                    'label' => __('plugins.themes.individualizeTheme.option.issueArchives.covers'),
                ],
                [
                    'value' => self::ISSUE_ARCHIVE_NO_COVERS,
                    'label' => __('plugins.themes.individualizeTheme.option.issueArchives.no-covers'),
                ],
            ],
            'default' => self::ISSUE_ARCHIVE_DEFAULT,
        ]);
    }

    /**
     * Add options to set typography
     */
    protected function addFontOptions(): void
    {
        if (!count($this->enabledFonts)) {
            return;
        }

        $options = [];
        foreach ($this->enabledFonts as $font) {
            $options[] = [
                'value' => $font['id'],
                'label' => $font['family'],
            ];
        }

        $this->theme->addOption('font', 'FieldSelect', [
            'label' => __('plugins.themes.individualizeTheme.option.font.label'),
            'description' => __('plugins.themes.individualizeTheme.option.font.description'),
            'options' => $options,
            'default' => self::FONT_DEFAULT,
        ]);

        $this->theme->addOption('titlesFont', 'FieldSelect', [
            'label' => __('plugins.themes.individualizeTheme.option.titlesFont.label'),
            'description' => __('plugins.themes.individualizeTheme.option.titlesFont.description'),
            'options' => $options,
            'default' => self::FONT_DEFAULT,
        ]);

        $this->theme->addOption('actionsFont', 'FieldSelect', [
            'label' => __('plugins.themes.individualizeTheme.option.actionsFont.label'),
            'description' => __('plugins.themes.individualizeTheme.option.actionsFont.description'),
            'options' => $options,
            'default' => self::FONT_DEFAULT,
        ]);
    }

    /**
     * Add options to set the colors
     */
    protected function addColorOptions(): void
    {
        $this->theme->addOption('colorMode', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.individualizeTheme.option.colorMode.label'),
            'description' => __('plugins.themes.individualizeTheme.option.colorMode.description'),
            'options' => [
                [
                    'value' => self::COLOR_MODE_DEFAULT,
                    'label' => __('plugins.themes.individualizeTheme.option.colorMode.default'),
                ],
                [
                    'value' => self::COLOR_MODE_ADVANCED,
                    'label' => __('plugins.themes.individualizeTheme.option.colorMode.advanced'),
                ],
            ],
            'default' => self::COLOR_MODE_DEFAULT,
        ]);

        // Simple mode
        $this->theme->addOption('primaryColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.primaryColor.label'),
            'description' => __('plugins.themes.individualizeTheme.option.primaryColor.description'),
            'default' => self::COLOR_PRIMARY,
            'showWhen' => ['colorMode', self::COLOR_MODE_DEFAULT],
        ]);
        $this->theme->addOption('accentColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.accentColor.label'),
            'description' => __('plugins.themes.individualizeTheme.option.accentColor.description'),
            'default' => self::COLOR_ACCENT,
            'showWhen' => ['colorMode', self::COLOR_MODE_DEFAULT],
        ]);

        // Advanced mode
        $this->theme->addOption('pageBackgroundColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.pageBackgroundColor.label'),
            'default' => self::COLOR_PAGE_BACKGROUND,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('pageTextColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.pageTextColor.label'),
            'default' => self::COLOR_PAGE_TEXT,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('pageLinkColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.pageLinkColor.label'),
            'default' => self::COLOR_ACCENT,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('headerBackgroundColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.headerBackgroundColor.label'),
            'default' => self::COLOR_PRIMARY,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('headerTextColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.headerTextColor.label'),
            'default' => self::COLOR_PRIMARY_TEXT,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('buttonBackgroundColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.buttonBackgroundColor.label'),
            'default' => self::COLOR_PAGE_BACKGROUND,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('buttonTextColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.buttonTextColor.label'),
            'default' => self::COLOR_PRIMARY,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('blockBackgroundColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.blockBackgroundColor.label'),
            'default' => self::COLOR_PRIMARY,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('blockTextColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.blockTextColor.label'),
            'default' => self::COLOR_PRIMARY_TEXT,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('footerBackgroundColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.footerBackgroundColor.label'),
            'default' => self::COLOR_PRIMARY,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('footerTextColor', 'FieldColor', [
            'label' => __('plugins.themes.individualizeTheme.option.footerTextColor.label'),
            'default' => self::COLOR_PRIMARY_TEXT,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
    }

    protected function getLatestArticles(): LazyCollection
    {
        return Repo::submission()->getCollector()
            ->filterByContextIds([Application::get()->getRequest()->getContext()?->getId() ?? 0])
            ->filterByStatus([Submission::STATUS_PUBLISHED])
            ->orderBy(Collector::ORDERBY_DATE_PUBLISHED)
            ->limit(5)
            ->getMany();
    }

    /**
     * Get the fallback font statement based on a font category
     *
     * The category is usually serif or sans-serif, but may be
     * other categories from Google Fonts, such as display and
     * handwriting.
     */
    protected function getFontFallback(string $category): string
    {
        switch ($category) {
            case 'serif':
                return 'serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';
            case 'sans-serif':
            default:
                return 'system-ui, -apple-system, BlinkMacSystemFont, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';
        }
    }
}

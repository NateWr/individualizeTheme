<?php

use Illuminate\Support\Collection;

/**
 * Helper class to define and register theme options
 * for SlubTheme
 */
class SlubThemeOptions
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

    public const COLOR_MODE_DEFAULT = 'default';
    public const COLOR_MODE_ADVANCED = 'advanced';

    public const COLOR_PRIMARY = '#cc0000';
    public const COLOR_ACCENT = '#116677';
    public const COLOR_PAGE_BACKGROUND = '#ffffff';
    public const COLOR_PAGE_TEXT = '#000000';
    public const COLOR_PRIMARY_TEXT = '#ffffff';

    public function __construct(public SlubTheme $theme)
    {
        //
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
        $this->addColorOptions();

        // Must be last option
        $this->fixOrderedHomepageBlocks();
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
        $templateMgr = TemplateManager::getManager(Application::get()->getRequest());

        $blocks = $this->theme->getOption('homepageBlocks');
        $templateMgr->assign('homepageBlocks', $blocks);

        foreach ($blocks as $template) {
            switch ($template) {
                case self::HOMEPAGE_BLOCK_ISSUE_TOC:
                    $pubIdPlugins = PluginRegistry::loadCategory('pubIds', true);
                    $templateMgr->assign('pubIdPlugins', $pubIdPlugins);
                case self::HOMEPAGE_BLOCK_LATEST_ARTICLES:
                    import('lib.pkp.classes.submission.PKPSubmissionDAO');
                    $templateMgr->assign('slubLatestArticles', $this->getLatestArticles());
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
                'label' => __('plugins.themes.slubTheme.option.homepageBlocks.announcement'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_ABOUT,
                'label' => __('plugins.themes.slubTheme.option.homepageBlocks.about'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_ISSUE_SUMMARY,
                'label' => __('plugins.themes.slubTheme.option.homepageBlocks.issue-summary'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_ISSUE_TOC,
                'label' => __('plugins.themes.slubTheme.option.homepageBlocks.issue-toc'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_SUBMIT,
                'label' => __('plugins.themes.slubTheme.option.homepageBlocks.how-to-submit'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_LATEST_ARTICLES,
                'label' => __('plugins.themes.slubTheme.latestArticles'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_BROWSE_BY_CATEGORY,
                'label' => __('plugins.themes.slubTheme.option.homepageBlocks.browse-by-category'),
            ],
            [
                'value' => self::HOMEPAGE_BLOCK_PARTNERS,
                'label' => __('plugins.themes.slubTheme.option.homepageBlocks.partners'),
            ],
        ]);
    }

    /**
     * Add option for the header layout
     */
    protected function addHeaderOption(): void
    {
        $this->theme->addOption('header', 'FieldOptions', [
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
    }

    /**
     * Add option for the tagline to display beside the logo
     */
    protected function addTaglineOption(): void
    {
        $this->theme->addOption('tagline', 'FieldText', [
            'label' => __('plugins.themes.slubTheme.option.tagline.label'),
            'description' => __('plugins.themes.slubTheme.option.tagline.description'),
            'default' => '',
        ]);
    }

    /**
     * Add option for where to display the homepage image
     */
    protected function addHomepageImageOption(): void
    {
        $this->theme->addOption('homepageImagePosition', 'FieldOptions', [
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
    }

    /**
     * Add option to select which blocks to show on the homepage
     */
    protected function addHomepageBlocksOption(): void
    {
        $this->theme->addOption('homepageBlocks', 'FieldOptions', [
            'type' => 'checkbox',
            'isOrderable' => true,
            'label' => __('plugins.themes.slubTheme.option.homepageBlocks.label'),
            'description' => __('plugins.themes.slubTheme.option.homepageBlocks.description'),
            'options' => $this->getHomepageBlockOptions()->toArray(),
            'default' => self::HOMEPAGE_BLOCKS_DEFAULT,
        ]);
    }

    /**
     * Workaround for bug in 3.3.x where the form does not display the options
     * in the selected order. This function must be called after all other
     * options are defined. All option values are loaded and stored in memory
     * once. Any theme options defined after this function is called will not
     * have their values loaded.
     */
    protected function fixOrderedHomepageBlocks(): void
    {
        $enabled = $this->theme->getOption('homepageBlocks');
        if (!$enabled) {
            $enabled = self::HOMEPAGE_BLOCKS_DEFAULT;
        }

        $options = $this->getHomepageBlockOptions()->values()->toArray();
        usort($options, function ($a, $b) use ($enabled) {
            $aIndex = array_search($a['value'], $enabled);
            $aIndex = $aIndex === false ? PHP_INT_MAX : $aIndex;
            $bIndex = array_search($b['value'], $enabled);
            $bIndex = $bIndex === false ? PHP_INT_MAX : $bIndex;
            return $aIndex < $bIndex ? -1 : 1;
        });

        $this->theme->options['homepageBlocks']->options = $options;
    }

    protected function addHowToSubmitBlock(): void
    {
        $this->theme->addOption('howToSubmitTitle', 'FieldText', [
            'label' => __('plugins.themes.slubTheme.option.howToSubmitTitle.label'),
            'description' => __('plugins.themes.slubTheme.option.howToSubmitTitle.description'),
            'default' => __('navigation.submissions'),
        ]);
        $this->theme->addOption('howToSubmitText', 'FieldText', [
            'label' => __('plugins.themes.slubTheme.option.howToSubmitText.label'),
            'description' => __('plugins.themes.slubTheme.option.howToSubmitText.description'),
            'size' => 'large',
            'default' => __('plugins.themes.slubTheme.option.howToSubmitText.default'),
        ]);
        $this->theme->addOption('howToSubmitAction', 'FieldText', [
            'label' => __('plugins.themes.slubTheme.option.howToSubmitAction.label'),
            'description' => __('plugins.themes.slubTheme.option.howToSubmitAction.description'),
            'size' => 'small',
            'default' => __('plugins.themes.slubTheme.option.howToSubmitAction.default'),
        ]);
    }

    /**
     * Add options to set the colors
     */
    protected function addColorOptions(): void
    {
        $this->theme->addOption('colorMode', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.slubTheme.option.colorMode.label'),
            'description' => __('plugins.themes.slubTheme.option.colorMode.description'),
            'options' => [
                [
                    'value' => self::COLOR_MODE_DEFAULT,
                    'label' => __('plugins.themes.slubTheme.option.colorMode.default'),
                ],
                [
                    'value' => self::COLOR_MODE_ADVANCED,
                    'label' => __('plugins.themes.slubTheme.option.colorMode.advanced'),
                ],
            ],
            'default' => self::COLOR_MODE_DEFAULT,
        ]);

        // Simple mode
        $this->theme->addOption('primaryColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.primaryColor.label'),
            'description' => __('plugins.themes.slubTheme.option.primaryColor.description'),
            'default' => self::COLOR_PRIMARY,
            'showWhen' => ['colorMode', self::COLOR_MODE_DEFAULT],
        ]);
        $this->theme->addOption('accentColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.accentColor.label'),
            'description' => __('plugins.themes.slubTheme.option.accentColor.description'),
            'default' => self::COLOR_ACCENT,
            'showWhen' => ['colorMode', self::COLOR_MODE_DEFAULT],
        ]);

        // Advanced mode
        $this->theme->addOption('pageBackgroundColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.pageBackgroundColor.label'),
            'default' => self::COLOR_PAGE_BACKGROUND,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('pageTextColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.pageTextColor.label'),
            'default' => self::COLOR_PAGE_TEXT,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('pageLinkColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.pageLinkColor.label'),
            'default' => self::COLOR_ACCENT,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('headerBackgroundColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.headerBackgroundColor.label'),
            'default' => self::COLOR_PRIMARY,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('headerTextColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.headerTextColor.label'),
            'default' => self::COLOR_PRIMARY_TEXT,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('buttonBackgroundColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.buttonBackgroundColor.label'),
            'default' => self::COLOR_PAGE_BACKGROUND,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('buttonTextColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.buttonTextColor.label'),
            'default' => self::COLOR_PRIMARY,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('blockBackgroundColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.blockBackgroundColor.label'),
            'default' => self::COLOR_PRIMARY,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('blockTextColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.blockTextColor.label'),
            'default' => self::COLOR_PRIMARY_TEXT,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('footerBackgroundColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.footerBackgroundColor.label'),
            'default' => self::COLOR_PRIMARY,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
        $this->theme->addOption('footerTextColor', 'FieldColor', [
            'label' => __('plugins.themes.slubTheme.option.footerTextColor.label'),
            'default' => self::COLOR_PRIMARY_TEXT,
            'showWhen' => ['colorMode', self::COLOR_MODE_ADVANCED],
        ]);
    }

    protected function getLatestArticles(): array
    {
        return iterator_to_array(
            Services::get('submission')->getMany([
                'contextId' => Application::get()->getRequest()->getContext()?->getId() ?? 0,
                'status' => STATUS_PUBLISHED,
                'orderBy' => ORDERBY_DATE_PUBLISHED,
                'count' => 5,
            ])
        );
    }
}

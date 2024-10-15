<?php
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
}
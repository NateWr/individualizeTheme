<?php
import('lib.pkp.classes.plugins.ThemePlugin');

class SlubTheme extends ThemePlugin {

	public function isActive() {
		if (defined('SESSION_DISABLE_INIT')) return true;
		return parent::isActive();
	}

	public function init() {
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
	}

	public function getDisplayName() {
		return __('plugins.themes.slubTheme.name');
	}

	public function getDescription() {
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
}

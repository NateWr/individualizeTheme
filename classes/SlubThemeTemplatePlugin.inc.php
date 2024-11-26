<?php
/**
 * Class to represent a Smarty plugin
 */
class SlubThemeTemplatePlugin
{
    public function __construct(
        /**
         * Smarty plugin type
         *
         * Typically `function`, `block` or `modifier`.
         *
         * @see https://www.smarty.net/docs/en/api.register.plugin.tpl
         */
        public string $type,

        /**
         * Name of this plugin
         *
         * Example: `th_example` would result in a template
         * tag `{th_example}`.
         */
        public string $name,

        /**
         * Function to call when plugin is fired in
         * array form.
         *
         * Example: [$object, $method]
         */
        public array $callback,

        /**
         * Whether this plugin should override any existing plugin
         * with the same name.
         */
        public bool $override = false
    ) {
        //
    }
}

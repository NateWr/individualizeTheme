<!doctype html>
<html lang="{$currentLocale|replace:"_":"-"}" xml:lang="{$currentLocale|replace:"_":"-"}">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>{title|strip_tags value=$pageTitleTranslated}</title>
	{load_header context="frontend"}
	{load_stylesheet context="frontend"}
</head>

 {**
 * Get the list of locales supported by the journal or site
 *}
{th_locales assign="slubLocales"}

<body
  dir="{$currentLocaleLangDir|escape|default:"ltr"}"
>
  <a name="back-to-top" class="sr-only">{translate key="common.top"}</a>
  {include file="frontend/components/header-mobile.tpl"}
  {include file="frontend/components/header-desktop.tpl"}
  <main class="pt-16">
    <div class="container">

      {block name="content"}{/block}

    </div>
  </main>
  <footer>
    <div class="footer footer-back-to-top">
      <div class="footer-blocks">
        <a href="#back-to-top" class="footer-back-to-top-link">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 19L8.125 19L8.125 6.875L4.075 10.925L3 9.85L8.925 4L14.75 9.825L13.675 10.9L9.625 6.85L9.625 17.5L20 17.5V19Z" fill="currentColor"/>
          </svg>
          {translate key="plugins.themes.slubTheme.backToTop"}
        </a>
      </div>
    </div>
    {if $currentContext}
      <div class="footer footer-context">
        <div class="footer-blocks">
          <h2 class="footer-block footer-name">
            {$currentContext->getLocalizedName()|escape}
          </h2>
        </div>
        <div class="footer-blocks">
          <div class="footer-block footer-block-masthead">
            <div class="footer-description html-text">
              {$currentContext->getLocalizedDescription()|strip_unsafe_html}
            </div>
            {if $currentContext->getData('onlineIssn') || $currentContext->getData('printIssn')}
              <table class="footer-metadata">
                <tbody>
                  {if $currentContext->getData('printIssn')}
                    <tr>
                      <th>ISSN</th>
                      <td>{$currentContext->getData('printIssn')}</td>
                    </tr>
                  {/if}
                  {if $currentContext->getData('onlineIssn')}
                    <tr>
                      <th>eISSN</th>
                      <td>{$currentContext->getData('onlineIssn')}</td>
                    </tr>
                  {/if}
                </tbody>
              </table>
            {/if}
            <div class="footer-links">
            <a class="link tab-focus" href="{url page="about" op="privacy"}">{translate key="plugins.themes.slubTheme.privacyPolicy"}</a>
              <a class="link tab-focus" href="#">{translate key="plugins.themes.slubTheme.accessibility"}</a>
            </div>
          </div>
          {if $primaryMenuBase}
            <div class="footer-block footer-block-menu menu-vertical-wrapper">
              {$primaryMenuBase|replace:'__id__':'footer-nav-primary'}
            </div>
          {/if}
          {if $userMenuBase}
            <div class="footer-block footer-block-menu menu-vertical-wrapper">
              {$userMenuBase|replace:'__id__':'footer-nav-user'}
            </div>
          {/if}
          <div class="footer-block footer-block-sidebar">
            {call_hook name="Templates::Common::Sidebar"}
          </div>
        </div>
      </div>
    {/if}
  </footer>
  {load_script context="frontend"}
</html>
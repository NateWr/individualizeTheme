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
{th_locales assign="individualizeLocales"}

<body
  dir="{$currentLocaleLangDir|escape|default:"ltr"}"
>
  <a name="back-to-top" class="sr-only">{translate key="common.top"}</a>
  {include file="frontend/components/skip-links.tpl"}
  {include file="frontend/components/header-mobile.tpl"}
  {include file="frontend/components/header-desktop.tpl"}
  <main class="pt-16" id="skip-to-main">
    <div class="container">

      {block name="content"}{/block}

    </div>
  </main>
  <footer class="footer" id="skip-to-footer">
    <div class="container">
      <div class="footer-back-to-top">
        <a href="#back-to-top" class="footer-back-to-top-link tab-focus">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 19L8.125 19L8.125 6.875L4.075 10.925L3 9.85L8.925 4L14.75 9.825L13.675 10.9L9.625 6.85L9.625 17.5L20 17.5V19Z" fill="currentColor"/>
          </svg>
          {translate key="plugins.themes.individualizeTheme.backToTop"}
        </a>
      </div>
    </div>
    <div class="footer-inner">
      <div class="container">
        <h2 class="footer-block footer-name">
          {$currentContext->getLocalizedName()|escape}
        </h2>
        <div class="footer-blocks">
          <div class="footer-block footer-block-masthead">
            <div class="footer-description html-text" data-reveal data-height="30">
              {$currentContext->getLocalizedDescription()|strip_unsafe_html}
            </div>
            {if $currentContext->getData('onlineIssn') || $currentContext->getData('printIssn')}
              <table class="footer-metadata">
                <tbody>
                  {if $currentContext->getData('printIssn')}
                    <tr>
                      <th>{translate key="journal.issn"}</th>
                      <td>{$currentContext->getData('printIssn')}</td>
                    </tr>
                  {/if}
                  {if $currentContext->getData('onlineIssn')}
                    <tr>
                      <th>{translate key="metadata.property.displayName.eissn"}</th>
                      <td>{$currentContext->getData('onlineIssn')}</td>
                    </tr>
                  {/if}
                </tbody>
              </table>
            {/if}
            <div class="footer-links">
              {capture assign="policyMenu"}{strip}
                {load_menu name="policy" id="nav-footer-policy" path="frontend/components/menu.tpl"}
              {/strip}{/capture}
              {if $policyMenu}
                {$policyMenu}
              {else}
                <ul class="menu" id="nav-footer-policy">
                  <li class="menu-item">
                    <a class="menu-button" href="{url page="about" op="privacy"}">
                      {translate key="plugins.themes.individualizeTheme.privacyPolicy"}
                    </a>
                  </li>
                  <li class="menu-item">
                    <a class="menu-button" href="{url page="about" op="contact"}">
                      {translate key="about.contact"}
                    </a>
                  </li>
                </ul>
              {/if}
            </div>
          </div>
          <div class="footer-block footer-block-menu menu-vertical-wrapper">
            {load_menu name="primary" id="nav-footer-primary" path="frontend/components/menu.tpl"}
          </div>
          <div class="footer-block footer-block-menu menu-vertical-wrapper">
            {load_menu name="user" id="nav-footer-user" path="frontend/components/menu.tpl"}
          </div>
        </div>
        {capture assign="sidebar"}{strip}
          {call_hook name="Templates::Common::Sidebar"}
        {/strip}{/capture}
        {if $sidebar}
          <div class="footer-sidebar">
            {$sidebar}
          </div>
        {/if}
      </div>
    </div>
    {call_hook name="IndividualizeTheme::Footer"}
  </footer>
  {load_script context="frontend"}
  {call_hook name="Templates::Common::Footer::PageFooter"}
</html>
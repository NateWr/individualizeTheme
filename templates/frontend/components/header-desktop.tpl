{capture assign="primaryMenu"}{strip}{load_menu name="primary" id="nav-desktop-primary" path="frontend/components/menu-desktop-main.tpl"}{/strip}{/capture}
{capture assign="userMenu"}{strip}{load_menu name="user" id="nav-desktop-user" path="frontend/components/menu.tpl"}{/strip}{/capture}
{if $currentContext}
  {assign var="homepageImage" value=$currentContext->getLocalizedData('homepageImage')}
  {if in_array($activeTheme->getOption('homepageImagePosition'), ['above', 'above-center'])}
    {assign var="isHomepageImageBehind" value=true}
  {/if}
{/if}

<header class="
  header-desktop
  header-desktop-{$activeTheme->getOption('header')|escape}
  header-desktop-image-{$activeTheme->getOption('homepageImagePosition')|escape}
">
  {if $homepageImage && in_array($activeTheme->getOption('homepageImagePosition'), ['above', 'above-center'])}
    <div class="header-desktop-above">
      <img class="header-desktop-image" src="{$publicFilesDir}/{$homepageImage.uploadName|escape:"url"}" alt="{$homepageImage.altText|escape}">
    </div>
  {/if}
  <div class="header-desktop-inner">
    {if $homepageImage && in_array($activeTheme->getOption('homepageImagePosition'), ['behind', 'behind-right-top', 'behind-right-center', 'behind-right-bottom', 'behind-pattern'])}
      <div class="header-desktop-behind">
        {if $activeTheme->getOption('homepageImagePosition') === 'behind-pattern'}
          <div class="header-desktop-image" style="background-image: url('{$publicFilesDir}/{$homepageImage.uploadName|escape:"url"}');"></div>
        {else}
          <img class="header-desktop-image" src="{$publicFilesDir}/{$homepageImage.uploadName|escape:"url"}" alt="{$homepageImage.altText|escape}">
        {/if}
      </div>
    {/if}
    <div class="header-desktop-inner-content">
      <div class="header-desktop-context">
        <a href="{url page="index"}" class="tab-focus">
          {if $displayPageHeaderLogo}
            <div class="sr-only">
              {$currentContext->getLocalizedName()}
            </div>
            <img class="header-desktop-logo" src="{$publicFilesDir}/{$displayPageHeaderLogo.uploadName|escape:"url"}">
          {else}
            {individualize_context_name_length assign="size"}
            <div class="header-desktop-name header-desktop-name-{$size}">
              {$currentContext->getLocalizedName()}
            </div>
          {/if}
        </a>
        {if $displayPageHeaderLogo && $activeTheme->getLocalizedOption('tagline') && in_array($activeTheme->getOption('header'), ['default', 'defaultCenter'])}
          <div class="header-desktop-tagline">
            {$activeTheme->getLocalizedOption('tagline')}
          </div>
        {/if}
      </div>
      {if $activeTheme->getOption('header') === 'line'}
        <nav class="header-desktop-nav-main-line" id="skip-to-nav-desktop">
          {$primaryMenu}
        </nav>
      {/if}
      {if in_array($activeTheme->getOption('header'), ['default', 'line'])}
        <div class="header-desktop-utility">
          <form action="{url page="search"}" method="GET">
            {include
              file="frontend/components/search-input.tpl"
              id="header-desktop-search"
              name="query"
            }
          </form>
          {if $individualizeLocales|count > 1}
            <div class="dropdown">
              <button
                class="dropdown-toggle tab-focus"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                id="skip-to-language-desktop"
              >
                <span class="sr-only">
                  {translate key="plugins.themes.individualizeTheme.changeLanguage"}
                </span>
                {include file="frontend/icons/globe.svg"}
              </button>
              <div class="dropdown-menu">
                <div class="header-desktop-languages">
                  <h2 class="sr-only">
                    {translate key="plugins.themes.individualizeTheme.changeLanguage"}
                  </h2>
                  {foreach key="localeKey" item="name" from=$individualizeLocales}
                    <a
                      class="
                        header-desktop-language
                        tab-focus
                        dropdown-item
                        {if $localeKey === $currentLocale}
                          header-desktop-language-selected
                        {/if}
                      "
                      href="{url page="user" op="setLocale" path=$localeKey}"
                    >
                      {include file="frontend/icons/check.svg"}
                      {$name}
                    </a>
                  {/foreach}
                </div>
              </div>
            </div>
          {/if}
          {if $userMenu}
            <div class="dropdown">
              <button
                class="dropdown-toggle tab-focus"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                id="skip-to-user-nav-desktop"
              >
                <span class="sr-only">
                  {translate key="plugins.themes.individualizeTheme.account"}
                </span>
                {include file="frontend/icons/account-circle.svg"}
              </button>
              <div class="dropdown-menu">
                {load_menu name="user" id="nav-desktop-user" path="frontend/components/menu.tpl"}
                {if $isUserLoggedIn}
                  <hr class="dropdown-divider">
                  {include file="frontend/components/logged-in-as.tpl"}
                {/if}
              </div>
            </div>
          {/if}
        </div>
      {/if}
    </div>
  </div>
  {if in_array($activeTheme->getOption('header'), ['default', 'defaultCenter'])}
    <nav class="header-desktop-nav-main" id="skip-to-nav-desktop">
      {$primaryMenu}
    </nav>
  {/if}
  {if $homepageImage && in_array($activeTheme->getOption('homepageImagePosition'), ['below', 'below-center'])}
    <div class="header-desktop-below">
      <img class="header-desktop-image" src="{$publicFilesDir}/{$homepageImage.uploadName|escape:"url"}" alt="{$homepageImage.altText|escape}">
    </div>
  {/if}
</header>
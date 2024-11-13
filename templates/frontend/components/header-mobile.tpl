<header class="header-mobile">
  <div class="header-mobile-inner">
    <div class="header-mobile-context">
      <a href="{url page="index"}" class="tab-focus">
        {if $displayPageHeaderLogo}
          <h1 class="sr-only">
            {$currentContext->getLocalizedName()}
          </h1>
          <img class="header-mobile-logo" src="{$publicFilesDir}/{$displayPageHeaderLogo.uploadName|escape:"url"}">
        {else}
          {slub_context_name_length assign="size"}
          <div class="header-mobile-name header-mobile-name-{$size}">
            {$currentContext->getLocalizedName()}
          </div>
        {/if}
      </a>
    </div>
    {assign var="dropdownId" value="header-mobile-dropdown"}
    <button
      class="header-mobile-button"
      type="button"
      data-mobile-menu="#{$dropdownId|escape}"
    >
      <span class="sr-only">
        {translate key="plugins.themes.slubTheme.menu"}
      </span>
      <span class="header-mobile-button-icon" aria-hidden="true">
        <span></span>
        <span></span>
        <span></span>
      </span>
    </button>
  </div>
  <div class="header-mobile-dropdown" id="{$dropdownId|escape}">
    <div class="header-mobile-dropdown-inner">
      <form action="{url page="search"}" method="GET">
        {include
          file="frontend/components/search-input.tpl"
          id="header-mobile-search"
          name="query"
        }
      </form>
      <nav class="header-mobile-primary-menu" id="skip-to-nav-mobile">
        {load_menu name="primary" id="nav-mobile-primary" path="frontend/components/menu.tpl"}
      </nav>
      {if $slubLocales|count > 1}
        <div class="header-mobile-languages">
          <h2 class="sr-only" id="skip-to-language-mobile">
            {translate key="plugins.themes.slubTheme.changeLanguage"}
          </h2>
          {include file="frontend/icons/globe.svg"}
          <div class="header-mobile-languages-list">
            {foreach key="localeKey" item="name" from=$slubLocales}
              <a
                class="
                  header-mobile-language
                  tab-focus
                  {if $localeKey === $currentLocale}
                    header-mobile-language-selected
                  {/if}
                "
                href="{url router=$smarty.const.ROUTE_PAGE page="user" op="setLocale" path=$localeKey source=$smarty.server.REQUEST_URI}"
              >
                {$name}
              </a>
            {/foreach}
          </div>
        </div>
      {/if}
      {if $isUserLoggedIn}
        <div class="header-mobile-logged-in-as">
          {include file="frontend/icons/account-circle.svg"}
          {include file="frontend/components/logged-in-as.tpl"}
        </div>
      {/if}
      <nav class="header-mobile-utility-menu" id="skip-to-user-nav-mobile">
        {load_menu name="user" id="nav-mobile-user" path="frontend/components/menu.tpl"}
      </nav>
    </div>
  </div>
</header>
{**
 * Pagination component used by several templates
 *
 * In most cases, the $urlPattern is enough to create all
 * the correct URLs. However, the catalog handler does not support
 * a /page/1 URL. In this case, we pass a $firstPageUrl variable
 * that overrides the $urlPattern.
 *
 * @param int $currentPage
 * @param string $urlPattern The URL for pages with a __page__ placeholder
 *   where the page number should go. For example: /issue/archives/__page__
 * @param string $firstPageUrl (optional) See comment above.
 *}
<nav
  aria-label="{translate|escape key="plugins.themes.individualizeTheme.pagination.prompt"}"
  class="pagination"
  role="navigation"
>
  <a
    aria-label="{translate|escape key="plugins.themes.individualizeTheme.pagination.previousPage"}"
    class="pagination-text-link tab-focus"
    {if $currentPage <= 1}
      disabled=true
    {else}
      {assign var="previousPage" value=$currentPage-1}
      {if $previousPage == 1 && $firstPageUrl}
        href="{$firstPageUrl}"
      {else}
        href="{$urlPattern|replace:'__page__':$previousPage}"
      {/if}
    {/if}
  >
    {include file="frontend/icons/arrow-left.svg"}
    {translate key="common.back"}
  </a>
  <ul class="pagination-pages">
    {foreach from=$pages item="page" name="pages"}
      <li>
        {if $page < 0}
          <span class="pagination-skip">···</span>
        {else}
          <a
            aria-label="{translate|escape key="plugins.themes.individualizeTheme.pagination.goToPage" page=$page}"
            class="pagination-page"
            {if $page == $currentPage}
              aria-current=true
            {elseif $page == 1}
              {if $firstPageUrl}
                href="{$firstPageUrl}"
              {else}
                href="{$urlPattern|replace:'__page__':''}"
              {/if}
            {else}
              href="{$urlPattern|replace:'__page__':$page}"
            {/if}
          >
            {$page}
          </a>
        {/if}
        {if $smarty.foreach.pages.last}
          {assign var="lastPage" value=$page}
        {/if}
      </li>
    {/foreach}
  </ul>
  <a
    aria-label="{translate|escape key="plugins.themes.individualizeTheme.pagination.nextPage"}"
    class="pagination-text-link tab-focus"
    {if $currentPage >= $lastPage}
      disabled=true
    {else}
      {assign var="nextPage" value=$currentPage+1}
      href="{$urlPattern|replace:'__page__':$nextPage}"
    {/if}
  >
    {translate key="help.next"}
    {include file="frontend/icons/arrow-right.svg"}
  </a>
</nav>
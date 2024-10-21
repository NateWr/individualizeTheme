{**
 * The small nav menu displayed within the homepage block
 * that includes about the journal or homepage content.
 *}
{if $navigationMenu}
  <ul class="homepage-about-menu">
    {foreach key="field" item="navigationMenuItemAssignment" from=$navigationMenu->menuTree}
      <a class="button" href="{$navigationMenuItemAssignment->navigationMenuItem->getUrl()}">
        {$navigationMenuItemAssignment->navigationMenuItem->getLocalizedTitle()}
      </a>
    {/foreach}
  </ul>
{/if}
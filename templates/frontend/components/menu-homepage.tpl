{**
 * The small nav menu displayed within the homepage block
 * that includes about the journal or homepage content.
 *}
{if $navigationMenu}
  <ul class="menu-homepage">
    {foreach key="field" item="navigationMenuItemAssignment" from=$navigationMenu->menuTree}
      <a class="button" href="{$navigationMenuItemAssignment->navigationMenuItem->getUrl()}">
        {$navigationMenuItemAssignment->navigationMenuItem->getLocalizedTitle()}
      </a>
    {/foreach}
  </ul>
{/if}
{**
 * Navigation menu
 *
 * This shows a NavigationMenu in a vertical list.
 *}
{if $navigationMenu}
  <ul id="{$id|escape}" class="menu {$ulClass|escape}">
    {foreach key="field" item="navigationMenuItemAssignment" from=$navigationMenu->menuTree}
      {if !$navigationMenuItemAssignment->navigationMenuItem->getIsDisplayed()}
        {continue}
      {/if}
      {assign var="isSearch" value=($navigationMenuItemAssignment->navigationMenuItem->getType() === $smarty.const.NMI_TYPE_SEARCH)}
      <li class="menu-item {$liClass|escape}">
        <a
          class="menu-button {if $isSearch}menu-button-with-icon{/if}"
          href="{$navigationMenuItemAssignment->navigationMenuItem->getUrl()}"
        >
          {if $isSearch}
            {include file="frontend/icons/search.svg"}
          {/if}
          {$navigationMenuItemAssignment->navigationMenuItem->getLocalizedTitle()}
        </a>
        {if $navigationMenuItemAssignment->navigationMenuItem->getIsChildVisible()}
          {capture assign="submenuId"}{$id|escape}-{$field}{/capture}
          <div id="{$submenuId}" class="menu-submenu">
            <ul class="menu">
              {foreach key=childField item=childNavigationMenuItemAssignment from=$navigationMenuItemAssignment->children}
                {if $childNavigationMenuItemAssignment->navigationMenuItem->getIsDisplayed()}
                  <li class="menu-item {$liClass|escape}">
                    <a class="menu-button" href="{$childNavigationMenuItemAssignment->navigationMenuItem->getUrl()}">
                      {$childNavigationMenuItemAssignment->navigationMenuItem->getLocalizedTitle()}
                    </a>
                  </li>
                {/if}
              {/foreach}
            </ul>
          </div>
        {/if}
      </li>
    {/foreach}
  </ul>
{/if}
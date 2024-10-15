{**
 * Main navigation menu
 *
 * This shows the main menu when it is displayed in the header
 * on desktop devices.
 *}
{if $navigationMenu}
  <ul id="{$id|escape}" class="menu-desktop-main {$ulClass|escape}">
    {foreach key="field" item="navigationMenuItemAssignment" from=$navigationMenu->menuTree}
      {if !$navigationMenuItemAssignment->navigationMenuItem->getIsDisplayed()}
        {continue}
      {/if}
      {assign var="isSearch" value=($navigationMenuItemAssignment->navigationMenuItem->getType() === $smarty.const.NMI_TYPE_SEARCH)}
      <li class="menu-item {if $isSearch}menu-item-search{/if} {$liClass|escape}">
        {if !$navigationMenuItemAssignment->navigationMenuItem->getIsChildVisible()}
          <a
            class="menu-button"
            href="{$navigationMenuItemAssignment->navigationMenuItem->getUrl()}"
          >
            {if $isSearch}
              {include file="frontend/icons/search.svg"}
            {/if}
            {$navigationMenuItemAssignment->navigationMenuItem->getLocalizedTitle()}
          </a>
        {else}
          {capture assign="submenuId"}{$id|escape}-{$field}{/capture}
          <button
            class="menu-button dropdown-toggle tab-focus"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            {$navigationMenuItemAssignment->navigationMenuItem->getLocalizedTitle()}
            {include file="frontend/icons/dropdown.svg"}
          </button>
          <div id="{$submenuId}" class="menu-submenu dropdown-menu" data-open="false">
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
{**
 * List of editorial users and roles
 *
 * Used for editorial history as well. When doing so,
 * it should receive a flag, {$history},
 *}

{if $mastheadRoles && $mastheadUsers}
  {foreach from=$mastheadRoles item="mastheadRole"}
    {if array_key_exists($mastheadRole->id, $mastheadUsers)}
      <h2>{$mastheadRole->getLocalizedData('name')|escape}</h2>
      <ul class="masthead" role="list">
        {foreach from=$mastheadUsers[$mastheadRole->id] item="mastheadUser"}
          <li class="masthead-person">
            <h3 class="masthead-name">
              {$mastheadUser['user']->getFullName()|escape}
              {if $mastheadUser['user']->getData('orcid') && $mastheadUser['user']->hasVerifiedOrcid()}
                <span class="masthead-orcid">
                  <a
                    href="{$mastheadUser['user']->getData('orcid')|escape}"
                    target="_blank"
                    aria-label="{translate key="common.editorialHistory.page.orcidLink" name=$mastheadUser['user']->getFullName()|escape}"
                  >
                    {$orcidIcon}
                  </a>
                </span>
              {/if}
            </h3>
            {if !empty($mastheadUser['user']->getLocalizedData('affiliation'))}
              <div class="masthead-affiliation">
                {$mastheadUser['user']->getLocalizedData('affiliation')|escape}
              </div>
            {/if}
            <div class="masthead-dates">
              {**
               * Editorial history supports many periods of service
               *}
              {if $history}
                {foreach name="services" from=$mastheadUser['services'] item="service"}
                  {translate key="common.fromUntil" from=$service['dateStart'] until=$service['dateEnd']}
                  {if !$smarty.foreach.services.last}{translate key="common.commaListSeparator"}{/if}
                {/foreach}
              {else if $mastheadUser['dateStart']}
                {translate key="common.fromUntil" from=$mastheadUser['dateStart'] until=""}
              {/if}
            </div>
          </li>
        {/foreach}
      </ul>
    {/if}
  {/foreach}
{/if}
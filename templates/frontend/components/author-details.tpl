{assign var="count" value=$publication->getData('authors')|count}

<div class="
  author-details
  author-details-{$count}
  {if $count > 1}
    author-details-many
  {/if}
">
  {foreach from=$publication->getData('authors') item="author"}
    <div class="author-details-author">
      <div class="author-details-name">
        {$author->getFullName()|escape}
      </div>
      {assign var="authorUserGroup" value=$userGroupsById[$author->getData('userGroupId')]}
      {if $authorUserGroup && $authorUserGroup->showTitle}
        <div class="author-details-userGroup">
          {$authorUserGroup->getLocalizedData('name')|escape}
        </div>
      {/if}
      {if count($author->getAffiliations()) > 0}
        <div class="author-details-affiliation">
          {assign var="renderedAffiliation" value=false}
          {foreach from=$author->getAffiliations() item="affiliation"}
            {if $affiliation->getLocalizedName() || $affiliation->getRor()}
              {if $renderedAffiliation}{translate key="common.commaListSeparator"}{/if}
              {if $affiliation->getLocalizedName()}<span>{$affiliation->getLocalizedName()|escape}</span>{/if}
              {if $affiliation->getRor()}<a href="{$affiliation->getRor()|escape}">{$rorIdIcon}</a>{/if}
              {assign var="renderedAffiliation" value=true}
            {/if}
          {/foreach}
        </div>
      {/if}
      {if $author->getData('orcid')}
        <a
          href="{$author->getData('orcid')|escape}"
          class="author-details-orcid tab-focus"
          target="_blank"
          aria-label="{translate
            key="common.editorialHistory.page.orcidLink"
            name=$author->getFullName()|escape
          }"
        >
          {if $author->hasVerifiedOrcid()}
            {$orcidIcon}
          {else}
            {$orcidUnauthenticatedIcon}
          {/if}
          {$author->getOrcidDisplayValue()|escape}
        </a>
      {/if}
    </div>
  {/foreach}
</div>
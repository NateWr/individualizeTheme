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
      {if $author->getLocalizedData('affiliation')}
        <div class="author-details-affiliation">
          {$author->getLocalizedData('affiliation')|escape}
          {if $author->getData('rorId')}
            <a href="{$author->getData('rorId')|escape}">{$rorIdIcon}</a>
          {/if}
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
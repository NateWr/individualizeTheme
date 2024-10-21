{if !$heading}
  {assign var="heading" value="h2"}
{/if}
{if !$context}
  {assign var="context" value=$currentContext}
{/if}
{if !$publication}
  {assign var="publication" value=$article->getCurrentPublication()}
{/if}

<div class="
  article-summary
  {if $cover}
    article-summary-with-cover
  {/if}
">
  {if $cover && $publication->getLocalizedData('coverImage')}
    <a
      class="article-summary-cover tab-focus"
      href="{url journal=$context->getPath() page="article" op="view" path=$article->getBestId()}"
    >
      {assign var="coverImage" value=$publication->getLocalizedData('coverImage')}
      <img
        class="article-summary-cover-image"
        src="{$publication->getLocalizedCoverImageUrl($article->getData('contextId'))|escape}"
        alt="{$coverImage.altText|escape|default:''}"
      >
    </a>
  {else}
    <div>{* Empty on purpose *}</div>
  {/if}
  <div class="article-summary-inner">
    <a
      class="article-summary-content tab-focus"
      href="{url journal=$context->getPath() page="article" op="view" path=$article->getBestId()}"
    >
      <{$heading} class="article-summary-title">
        {$article->getLocalizedFullTitle()|strip_unsafe_html}
      </{$heading}>
      <div class="article-summary-authors">
        {$article->getAuthorString()|escape}
      </div>
    </a>
    {th_filter_galleys
      assign="galleys"
      galleys=$article->getGalleys()
      genreIds=$primaryGenreIds
      remotes=true
    }
    {if $galleys|count}
      <div class="article-summary-galleys">
        {foreach from=$galleys item="galley"}
          {capture assign="galleyUrl"}{strip}
            {if $galley->getRemoteUrl()}
              {$galley->getRemoteUrl()}
            {else}
              {url page="article" op="view" path=$article->getBestId()|to_array:$galley->getBestGalleyId()}
            {/if}
          {/strip}{/capture}
          <a class="button" href="{$galleyUrl}">
            {if $galley->isPdfGalley()}
              {include file="frontend/icons/download.svg"}
            {/if}
            {$galley->getGalleyLabel()|escape}
          </a>
        {/foreach}
      </div>
    {/if}
  </div>
</div>
{if $issue}
  {capture assign="publishedByImage"}{strip}
    {if $issue->getLocalizedCoverImage()}
      {$issue->getLocalizedCoverImageUrl()}
    {elseif $displayPageHeaderLogo}
      {$publicFilesDir}/{$displayPageHeaderLogo.uploadName|escape:"url"}
    {/if}
  {/strip}{/capture}
  {capture assign="publishedInUrl"}{strip}
    {url page="issue" op="view" path=$issue->getBestIssueId()}
  {/strip}{/capture}
  <div class="article-metadata-table-row">
    <h3 class="article-metadata-table-heading">
      {if $publishedByImage}
        <span class="sr-only">
          {translate key="context.context"}
        </span>
        <a class="article-metadata-cover" tab-focus" href="{$publishedInUrl|escape}">
          <img
            src="{$publishedByImage|escape}"
            alt="{$issue->getLocalizedCoverImageAltText()|escape|default:''}"
          >
        </a>
      {else}
        {translate key="context.context"}
      {/if}
    </h3>
    <div class="html-text">
      <p>
        {translate
          key="plugins.themes.individualizeTheme.publishedInIssue"
          issue=$issue->getIssueIdentification(['showTitle' => false])
          issueUrl=$publishedInUrl
          journal=$currentContext->getLocalizedName()|escape
          journalUrl={url page="index"}
        }
      </p>
      {$currentContext->getLocalizedDescription()|strip_unsafe_html}
      <a class="arrow-link tab-focus" href="{url page="index"}">
        {translate key="about.aboutContext"}
        {include file="frontend/icons/arrow-right.svg"}
      </a>
    </div>
  </div>
{/if}
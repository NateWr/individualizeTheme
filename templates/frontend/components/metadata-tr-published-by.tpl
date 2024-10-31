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
  <tr>
    <th>
      {if $publishedByImage}
        <a class="tab-focus" href="{$publishedInUrl|escape}">
          <img src="{$publishedByImage|escape}">
        </a>
      {else}
        {translate key="context.context"}
      {/if}
    </th>
    <td>
      <div class="html-text">
        <p>
          {translate
            key="plugins.themes.slubTheme.publishedInIssue"
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
    </td>
  </tr>
{/if}
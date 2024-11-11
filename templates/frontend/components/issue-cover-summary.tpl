<a
  class="issue-cover-summary tab-focus"
  href="{url page="issue" op="view" path=$issue->getBestIssueId()}"
>
  {if $issue->getLocalizedCoverImageUrl()}
    <img
      class="issue-cover-summary-image"
      src="{$issue->getLocalizedCoverImageUrl()}"
      alt="{$issue->getLocalizedCoverImageAltText()|escape|default:''}"
    >
  {else}
    {include file="frontend/components/issue-cover-default.svg"}
  {/if}
  <h3 class="issue-cover-summary-details">
    <div class="issue-cover-summary-vol">
      {$issue->getIssueIdentification(['showTitle' => false, 'showYear' => false])}
    </div>
    {if $issue->getYear()}
      <div class="issue-cover-summary-year">
        {$issue->getYear()}
      </div>
    {/if}
    {if $issue->getLocalizedTitle()}
      <div class="issue-cover-summary-title">
        {$issue->getLocalizedTitle()|escape}
      </div>
    {/if}
  </h3>
</a>
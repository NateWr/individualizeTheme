{if $issue}
  <div class="article-metadata-table-row">
    <h3 class="article-metadata-table-heading">
      {translate key="issue.issue"}
    </h3>
    <div class="html-text">
      <a class="arrow-link tab-focus" href="{url page="issue" op="view" path=$issue->getBestIssueId()}">
        {$issue->getIssueIdentification()}
        {include file="frontend/icons/arrow-right.svg"}
      </a>
    </div>
  </div>
{/if}
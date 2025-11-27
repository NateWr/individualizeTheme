<div class="
  issue-summary
  {if $hideCover}
    issue-summary-no-cover
  {/if}
">
  {if !$hideCover}
    <a
      class="issue-summary-cover tab-focus"
      href="{url page="issue" op="view" path=$issue->getBestIssueId()}"
    >
      {if $issue->getLocalizedCoverImageUrl()}
        <img
          class="issue-summary-cover-image"
          src="{$issue->getLocalizedCoverImageUrl()}"
          alt="{$issue->getLocalizedCoverImageAltText()|escape|default:''}"
        >
      {else}
        {include file="frontend/components/issue-cover-default.svg"}
      {/if}
    </a>
  {/if}
  <div class="issue-summary-inner">
    <div class="issue-summary-header">
      <a
        class="tab-focus"
        href="{url page="issue" op="view" path=$issue->getBestIssueId()}"
      >
        <h3>
          <div class="issue-summary-vol">
            {$issue->getIssueIdentification(['showTitle' => false, 'showYear' => false])}
          </div>
          {if $issue->getYear()}
            <div class="issue-summary-year">
              {$issue->getYear()}
            </div>
          {/if}
          {if $issue->getLocalizedTitle()}
            <div class="issue-summary-title">
              {$issue->getLocalizedTitle()|escape}
            </div>
          {/if}
        </h3>
      </a>
      {if !empty($issueGalleys)}
        <div class="issue-summary-galleys">
          {foreach from=$issueGalleys item=galley}
            <a
              class="button"
              href="{url page="issue" op="view" path=$issue->getBestIssueId()|to_array:$galley->getBestGalleyId()}"
              aria-label="{translate
                key="submission.representationOfTitle"
                representation=$galley->getGalleyLabel()|escape
                title=$issue->getIssueIdentification()|escape
              }"
            >
              {if $galley->isPdfGalley()}
                {include file="frontend/icons/download.svg"}
              {/if}
              <span class="button-truncate-text">
                {$galley->getGalleyLabel()|escape}
              </span>
            </a>
          {/foreach}
        </div>
      {/if}
    </div>
    <div class="issue-summary-description">
      {if $issue->getLocalizedDescription()}
        <div class="html-text">
          {$issue->getLocalizedDescription()|strip_unsafe_html}
        </div>
      {/if}
      <div class="issue-summary-button">
        <a
          class="arrow-link tab-focus"
          href="{url page="issue" op="view" path=$issue->getBestIssueId()}"
        >
          {translate key="issue.viewIssue"}
          {include file="frontend/icons/arrow-right.svg"}
        </a>
      </div>
    </div>
  </div>
</div>
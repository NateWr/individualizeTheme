{if $issue}
  <section class="homepage homepage-issue-summary">
    <h2 class="sr-only">{translate key="journal.currentIssue"}</h2>
    <div class="
      issue-summary
      {if !$issue->getLocalizedCoverImageUrl()}
        issue-summary-no-cover
      {/if}
    ">
      {if $issue->getLocalizedCoverImageUrl()}
        <img
          class="issue-summary-cover"
          src="{$issue->getLocalizedCoverImageUrl()}"
          alt="{$issue->getLocalizedCoverImageAltText()|escape|default:''}"
        >
      {/if}
      <div class="issue-summary-inner">
        <div class="issue-summary-header">
          <a
            class="issue-summary-link tab-focus"
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
            <div class="arrow-link tab-focus">
              {translate key="issue.viewIssue"}
              {include file="frontend/icons/arrow-right.svg"}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
{/if}

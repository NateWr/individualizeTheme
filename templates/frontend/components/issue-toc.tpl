{**
 * Set up the correct <h*> tags to use for the table of contents
 *
 * Defaults to the top-level heading <h1>. Pass an
 * int as $headingStart to start at <h2> or below
 * if the issue is not the main item on the page.
 *}
{if !$headingStart}
  {assign var="headingStart" value=1}
{/if}
{capture assign="issueHeading"}h{$headingStart}{/capture}
{capture assign="sectionHeading"}h{$headingStart + 1}{/capture}
{capture assign="articleHeading"}h{$headingStart + 2}{/capture}

<div class="
  issue-toc
  {if $issue->getLocalizedCoverImageUrl()}
    issue-toc-with-cover
  {/if}
">
  <div class="issue-toc-header">
    {if $issue->getLocalizedCoverImageUrl()}
      <div class="issue-toc-cover">
        <img
          class="issue-toc-cover-image"
          src="{$issue->getLocalizedCoverImageUrl()}"
          alt="{$issue->getLocalizedCoverImageAltText()|escape|default:''}"
        >
      </div>
    {/if}
    <div class="issue-toc-header-content">
      <div class="issue-toc-header-top">

        {* Issue Identification (Vol. X, No. X) and Galleys *}
        <{$issueHeading} class="issue-toc-identification">
          <div class="issue-toc-vol">
            {$issue->getIssueIdentification(['showTitle' => false, 'showYear' => false])}
          </div>
          {if $issue->getYear()}
            <div class="issue-toc-year">
              {$issue->getYear()}
            </div>
          {/if}
          {if $issue->getLocalizedTitle()}
            <div class="issue-toc-title">
              {$issue->getLocalizedTitle()|escape}
            </div>
          {/if}
        </{$issueHeading}>
        {if !empty($issueGalleys)}
          <div class="issue-toc-galleys">
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

      {* Metadata (DOIs, PubIDs, Published Date, etc.) *}
      {foreach from=$pubIdPlugins item="pubIdPlugin"}
        {assign var="pubId" value=$issue->getStoredPubId($pubIdPlugin->getPubIdType())}
        {if $pubId}
          {assign var="doiUrl" value=$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}
          <div class="issue-toc-metadata issue-metadata-{$pubIdPlugin->getPubIdType()|escape}">
            <strong>
              {translate key="semicolon" label=$pubIdPlugin->getPubIdDisplayType()|escape}
            </strong>
            {if $doiUrl}
              <a class="link" href="{$doiUrl|escape}">
                {$doiUrl}
              </a>
            {else}
              {$pubId}
            {/if}
          </div>
        {/if}
      {/foreach}
      {if $issue->getDatePublished()}
        <div class="issue-toc-metadata issue-toc-metadata-published">
          <strong>
            {capture assign="publishedLabel"}{translate key="submissions.published"}{/capture}
            {translate key="semicolon" label=$publishedLabel}
          </strong>
          {$issue->getDatePublished()|date_format:$dateFormatLong}
        </div>
      {/if}

      {* Issue Description *}
      {assign var="issueDescriptionId" value="issue-{$issue->getId()}-description"}
      <div class="issue-toc-description html-text" data-reveal>
        {$issue->getLocalizedDescription()|strip_unsafe_html}
      </div>
    </div>
  </div>

  {* Articles *}
  <ul class="issue-toc-sections">
    {assign var="showCovers" value=false}
    {if $issue->getLocalizedCoverImageUrl()}
      {assign var="showCovers" value=true}
    {/if}
    {foreach from=$publishedSubmissions item="section"}
      {if $section.articles}
        <li class="issue-toc-section">
          {if $section.title}
            <{$sectionHeading} class="issue-toc-section-name">
              {$section.title|escape}
            </{$sectionHeading}>
          {/if}
          <ul class="issue-toc-articles">
            {foreach from=$section.articles item="article"}
              <li class="issue-toc-article">
                {include
                  file="frontend/components/article-summary.tpl"
                  article=$article
                  context=$currentContext
                  cover=$showCovers
                  galleys=true
                  heading=$articleHeading
                }
              </li>
            {/foreach}
          </ul>
        </li>
      {/if}
    {/foreach}
  </ul>
</div>
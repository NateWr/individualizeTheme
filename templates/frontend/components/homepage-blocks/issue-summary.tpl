{if $issue}
  {assign var="coverImage" value=$issue->getLocalizedCoverImageUrl()}
  <section class="homepage homepage-issue-summary">
    <h2 class="sr-only">{translate key="journal.currentIssue"}</h2>
    {include
      file="frontend/components/issue-summary.tpl"
      hideCover=empty($coverImage)
    }
  </section>
{/if}

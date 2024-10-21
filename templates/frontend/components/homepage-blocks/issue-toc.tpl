{if $issue}
  <section class="homepage homepage-issue-toc">
    <h2 class="sr-only">{translate key="journal.currentIssue"}</h2>
    {include
      file="frontend/components/issue-toc.tpl"
      issue=$issue
      headingStart="3"
    }
  </section>
{/if}

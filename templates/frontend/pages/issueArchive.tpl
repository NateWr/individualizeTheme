{assign var="pageTitleTranslated" value={translate key="plugins.themes.individualizeTheme.allIssues"}}

{if $prevPage || $nextPage}
  {capture assign="urlPattern"}{url page="issue" op="archive" path="__page__"}{/capture}
  {th_pagination
    assignPages="pages"
    assignCurrent="currentPage"
    perPage=$itemsPerPage
    total=$total
    start=$showingStart
  }
{/if}

{extends file="frontend/layout.tpl"}

{block name="content"}

  <div class="
    issue-archive
    issue-archive-{$activeTheme->getOption('issueArchives')}
  ">
    <div class="issue-archive-header">
      <h1 class="issue-archive-title">
        {translate key="plugins.themes.individualizeTheme.allIssues"}
      </h1>
      {if $prevPage || $nextPage}
        <div class="issue-archive-showing">
          {translate
            key="plugins.themes.individualizeTheme.pagination.issueArchive"
            start=$showingStart
            end=$showingEnd
            total=$total
            journal=$currentContext->getLocalizedName()
          }
        </div>
        {include
          file="frontend/components/pagination.tpl"
          pages=$pages
          currentPage=$currentPage
          urlPattern=$urlPattern
        }
      {/if}
    </div>
    {if !$issues|count}
      <div class="issue-archive-empty">
        {translate key="current.noCurrentIssueDesc"}
      </div>
    {else}
      <h2 class="sr-only">
        {translate key="issue.issues"}
      </h2>
      <ul class="issue-archive-list">
			  {foreach from=$issues item="issue"}
          <li>
            {if $activeTheme->getOption('issueArchives') === 'covers'}
              {include file="frontend/components/issue-cover-summary.tpl"}
            {else}
              {include
                file="frontend/components/issue-summary.tpl"
                hideCover=$activeTheme->getOption('issueArchives') === 'no-covers'
              }
            {/if}
          {/foreach}
        </li>
      </ul>
    {/if}
    {if $prevPage || $nextPage}
      {include
        file="frontend/components/pagination.tpl"
        pages=$pages
        currentPage=$currentPage
        urlPattern=$urlPattern
      }
    {/if}
  </div>

{/block}
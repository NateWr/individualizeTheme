{assign var="pageTitleTranslated" value={translate key="common.search"}}
{if $query}
  {assign var="pageTitleTranslated" value={translate key="plugins.themes.individualizeTheme.search.pageTitle" phrase=$query|escape}}
{/if}


{extends file="frontend/layout.tpl"}

{block name="content"}

  <main class="search" id="skip-to-main">
    <div class="search-header">
      <h1 class="search-title">
        {if !$query}
          {translate key="common.search"}
        {else}
          {translate key="search.searchResults"}
        {/if}
      </h1>
      <div class="search-desc">
        {if !$query}
          <div>
            {translate
              key="plugins.themes.individualizeTheme.search.description"
              url={url page="issue" op="archive"}
            }
          </div>
        {else}
          <div class="search-desc-results">
            {translate
              key="plugins.themes.individualizeTheme.search.countResults"
              number=$results->count
            }
          </div>
          <div>
            {if $results->getCount()}
              {assign var="start" value=(($results->getPage() - 1) * $results->itemsPerPage) + 1}
              {assign var="end" value=(min($results->getCount(), ($results->getPage() * $results->itemsPerPage)))}
              {translate
                key="plugins.themes.individualizeTheme.search.showing"
                start=$start
                end=$end
                phrase=$query|escape
              }
            {else}
              {translate
                key="plugins.themes.individualizeTheme.search.noMatches"
                phrase=$query|escape
              }
            {/if}
          </div>
        {/if}
      </div>
      {if $results->getPageCount() > 1}
        {capture assign="queryParam"}&searchPage={$results->getPage()}{/capture}
        {assign var="urlPattern" value=$smarty.server.REQUEST_URI|replace:{$queryParam}:''|concat:'&searchPage=__page__'}
        {th_pagination
          assignPages="pages"
          assignCurrent="currentPage"
          perPage=$results->itemsPerPage
          total=$results->getCount()
          start=(($results->getPage() - 1) * $results->itemsPerPage) + 1
        }
        {include
          file="frontend/components/pagination.tpl"
          pages=$pages
          currentPage=$currentPage
          urlPattern=$urlPattern
        }
      {/if}
    </div>

    <div class="
      search-body
      {if $results->getCount()}
        search-body-has-results
      {/if}
    ">

      {* Results *}
      {if $results->getCount()}
        <h2 class="sr-only">
          {translate key="search.searchResults"}
        </h2>
        <ul class="search-results">
      		{iterate from="results" item="result"}
            <li>
              {include
                file="frontend/components/article-summary.tpl"
                article=$result.publishedSubmission
                context=$currentContext
                heading="h3"
                galleys=true
                abstract=true
              }
            </li>
          {/iterate}
        </ul>
      {/if}

      {* Search form *}
      {include
        file="frontend/components/search-form.tpl"
        query=$query
      }
    </div>

    {if $results->getPageCount()> 1}
      {include
        file="frontend/components/pagination.tpl"
        pages=$pages
        currentPage=$currentPage
        urlPattern=$urlPattern
      }
    {/if}
  </main>

{/block}
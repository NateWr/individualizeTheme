{assign var="pageTitleTranslated" value={$category->getLocalizedTitle()|escape}}

{if $prevPage || $nextPage}
  {capture assign="urlPattern"}{url page="catalog" op="category" path=$category->getPath()|to_array:'__page__'}{/capture}
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
    category
    {if $subcategories|@count}
      category-has-subcategories
    {/if}
  ">
    {if $parentCategory}
      <div class="breadcrumb">
        <a
          class="breadcrumb-item tab-focus"
          href="{url page="catalog" op="category" path=$parentCategory->getPath()}"
        >
          {include file="frontend/icons/arrow-left.svg"}
          {$parentCategory->getLocalizedTitle()|escape}
        </a>
      </div>
    {/if}

    <div class="category-header">
      <h1 class="category-title">
        {$category->getLocalizedTitle()|escape}
      </h1>
      {if $category->getLocalizedDescription()}
        <div class="category-desc html-text">
          {$category->getLocalizedDescription()|strip_unsafe_html}
        </div>
      {/if}
    </div>

    <div class="category-inner">
      {if $subcategories|@count}
        <nav class="category-subcategories">
          <h2 class="category-subcategories-title">
            {translate key="catalog.category.subcategories"}
          </h2>
          <ul>
            {foreach from=$subcategories item=subcategory}
              <li>
                <a
                  class="tab-focus"
                  href="{url op="category" path=$subcategory->getPath()}"
                >
                  {$subcategory->getLocalizedTitle()|escape}
                </a>
              </li>
            {/foreach}
          </ul>
        </nav>
      {/if}
      <div class="category-items">
        <h2 class="sr-only">
          {translate key="article.articles"}
        </h2>
        {if !$publishedSubmissions|@count}
          <div class="notice">
            <div class="notice-content">
              {translate key="catalog.category.noItems"}
            </div>
          </div>
        {else}
          <ul class="category-items-list">
            {foreach from=$publishedSubmissions item="article"}
              <li>
                {include
                  file="frontend/components/article-summary.tpl"
                  article=$article
                  heading="h3"
                  galleys=true
                }
              </li>
            {/foreach}
          </ul>
          {if $prevPage || $nextPage}
            {include
              file="frontend/components/pagination.tpl"
              pages=$pages
              currentPage=$currentPage
              urlPattern=$urlPattern
            }
          {/if}
        {/if}
      </div>
    </div>
  </div>

{/block}
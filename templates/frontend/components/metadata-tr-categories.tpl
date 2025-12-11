{if $categories}
  <div class="article-metadata-table-row">
    <h3 class="article-metadata-table-heading">
      {translate key="category.category"}
    </h3>
    <div class="html-text">
      {foreach from=$categories item="category" name="categories"}
        <a href="{url page="catalog" op="category" path=$category->getPath()|escape}">
          {$category->getLocalizedTitle()|escape}</a>{if !$smarty.foreach.categories.last},{/if}
      {/foreach}
    </div>
  </div>
{/if}
{if !$keywords}
  <div class="article-metadata-table-row">
    <h3 class="article-metadata-table-heading">
      {translate key="common.keywords"}
    </h3>
    <div class="html-text">
      {foreach name="keywords" from=$keywords item="keyword"}
        {$keyword|escape}{if !$smarty.foreach.keywords.last}{translate key="common.commaListSeparator"}{/if}
      {/foreach}
    </div>
  </div>
{/if}
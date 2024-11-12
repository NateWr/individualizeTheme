{capture name="searchFormUrl"}{url escape=false}{/capture}
{assign var=formUrlParameters value=[]}{* Prevent Smarty warning *}
{$smarty.capture.searchFormUrl|parse_url:$smarty.const.PHP_URL_QUERY|default:""|parse_str:$formUrlParameters}
<form
  class="search-form"
  method="get"
  action="{$smarty.capture.searchFormUrl|strtok:"?"|escape}"
  role="form"
>
  {foreach from=$formUrlParameters key="paramKey" item="paramValue"}
    <input type="hidden" name="{$paramKey|escape}" value="{$paramValue|escape}"/>
  {/foreach}
  {include
    file="frontend/components/search-input.tpl"
    id="search-form-input"
    name="query"
    placeholder={translate key="plugins.themes.slubTheme.search.placeholder"}
    value=$query
  }
  <fieldset class="search-form-filters-fieldset">
    <legend class="search-form-filters-legend">
      {translate key="search.advancedFilters"}
    </legend>
    <div class="search-form-filters">
      <div class="search-form-filters-date">
        {capture assign="dateFromTo"}{translate key="search.dateFrom"}{/capture}
        {html_select_date_a11y legend=$dateFromTo prefix="dateFrom" time=$dateFrom start_year=$yearStart end_year=$yearEnd}
      </div>
      <div class="search-form-filters-date">
        {capture assign="dateFromTo"}{translate key="search.dateTo"}{/capture}
        {html_select_date_a11y legend=$dateFromTo prefix="dateTo" time=$dateTo start_year=$yearStart end_year=$yearEnd}
      </div>
      <div class="input-wrapper">
        <label for="authors" class="input-label">
          {translate key="search.author"}
        </label>
        <input
          class="input"
          id="authors"
          name="authors"
          type="text"
          value="{$authors|escape}"
        >
      </div>
      {call_hook name="Templates::Search::SearchResults::AdditionalFilters"}
    </div>
  </fieldset>
  <div class="search-form-footer">
    <button class="button button-highlight" type="submit">
      {include file="frontend/icons/search.svg"}
      {if $query}
        {translate key="search.searchAgain"}
      {else}
        {translate key="common.search"}
      {/if}
    </button>
  </div>
</form>
{if !$heading}
  {assign var="heading" value="h3"}
{/if}
<div class="category-summary">
  <div class="category-summary-header">
    <{$heading} class="category-summary-title">
      {$category->getLocalizedTitle()|escape}
    </{$heading}>
    <div class="category-summary-description html-text">
      {$category->getLocalizedDescription()|strip_unsafe_html}
    </div>
  </div>
  <a
    class="arrow-link tab-focus"
    href="{url page="catalog" op="category" path=$category->getPath()|escape}"
  >
    {translate key="plugins.themes.individualizeTheme.viewCategory"}
    {include file="frontend/icons/arrow-right.svg"}
  </a>
</div>

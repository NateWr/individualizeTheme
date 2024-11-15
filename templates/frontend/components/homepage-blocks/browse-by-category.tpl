{if $categories && count($categories)}
  <section class="homepage-block homepage-block-categories">
    <div class="homepage-block-categories-header">
      <h2 class="homepage-block-categories-title">
        {$activeTheme->getLocalizedOption('categoriesTitle')|escape}
      </h2>
      <div class="homepage-block-categories-desc">
        {capture assign="url"}{url page="issue" op="archive"}{/capture}
        {$activeTheme->getLocalizedOption('categoriesDescription')|replace:'{$url}':$url|strip_unsafe_html}
      </div>
    </div>
    <ul class="homepage-block-categories-list">
      {foreach from=$categories item="category"}
        <li>
          {include
            file="frontend/components/category-summary.tpl"
            category=$category
            heading="h3"
          }
        </li>
      {/foreach}
    </ul>
  </section>
{/if}

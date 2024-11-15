{if $slubLatestArticles|count}
  <section class="homepage-block homepage-block-latest-articles">
    <div class="homepage-block-latest-articles-header">
      <h2 class="homepage-block-latest-articles-title">
        {$activeTheme->getLocalizedOption('latestArticlesTitle')|escape}
      </h2>
      <div class="homepage-block-latest-articles-description">
        {assign var="url" value={url page="issue" op="archive"}}
        {$activeTheme->getLocalizedOption('latestArticlesDescription')|replace:'{$url}':$url|strip_unsafe_html}
      </div>
    </div>
    <ul class="homepage-block-latest-articles-list">
      {foreach from=$slubLatestArticles item="article"}
        <li>
          {include
            file="frontend/components/article-summary.tpl"
            article=$article
            context=$currentContext
            cover=false
            galleys=true
            heading="h3"
          }
        </li>
      {/foreach}
    </ul>
  </section>
{/if}
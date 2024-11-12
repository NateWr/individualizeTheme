{if $slubLatestArticles|count}
  <section class="homepage-block homepage-block-latest-articles">
    <div class="homepage-block-latest-articles-header">
      <h2 class="homepage-block-latest-articles-title">
        {translate key="plugins.themes.slubTheme.latestArticles"}
      </h2>
      <div class="homepage-block-latest-articles-description">
        {translate
          key="plugins.themes.slubTheme.latestArticles.description"
          url={url page="issue" op="archive"}
        }
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
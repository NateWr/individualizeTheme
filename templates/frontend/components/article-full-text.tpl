{**
 * Render full-text from a HTML galley with a table of contents
 *
 * This template works with the theme's built-in option for displaying
 * the full text of HTML galleys in the article landing page.
 *
 * @see IndividualizeTheme::addArticleFullText()
 *}
{if $fullTextHtml}
  <section class="article-section article-section-full-text" individualize-full-text>
    {if $fullTextTableOfContents|@count}
      {capture assign="tableOfContents"}{strip}
        <div class="article-table-of-contents">
          {assign var="level" value=0}
          {foreach from=$fullTextTableOfContents item=$heading}
            {if $heading.level > $level}
              <ul>
            {elseif $heading.level < $level}
                </li>
              </ul>
            {else}
              </li>
            {/if}
            <li>
              <a href="#{$heading.id|escape}">
                {$heading.content}
              </a>
            {assign var="level" value=$heading.level}
          {/foreach}
          </ul>
        </div>
      {/strip}{/capture}
      <h2 class="article-section-title">
        {translate key="issue.toc"}
      </h2>
      {$tableOfContents}
      <div class="article-table-of-contents-panel" data-show="false" individualize-table-of-contents>
        <button
          class="article-table-of-contents-button tab-focus"
          aria-controls="article-table-of-contents-panel"
          aria-expanded="false"
        >
          {include file="frontend/icons/list-alt.svg"}
          <span class="article-table-of-contents-label">
            {translate key="issue.toc"}
          </span>
        </button>
        <div
          class="article-table-of-contents-panel-inner"
          id="article-table-of-contents-panel"
        >
          {$tableOfContents}
        </div>
      </div>
    {else}
      <h2 class="article-section-title">
        {translate key="search.fullText"}
      </h2>
    {/if}
    <div class="html-text">
      {$fullTextHtml}
    </div>

    {if $fullTextReferences}
      <h3 class="article-section-title" id="individualize-references">
        {translate key="submission.citations"}
      </h3>
      <div class="html-text">
        {$fullTextReferences}
      </div>
    {/if}
  </section>
{/if}
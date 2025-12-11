{assign var="pageTitleTranslated" value={translate key="announcement.announcements"}}

{extends file="frontend/layout-basic.tpl"}

{block name="content"}

  <main class="page announcements" id="skip-to-main">
    <div class="page-heading">
      <h1 class="page-title">
        {translate key="announcement.announcements"}
      </h1>
      {if $announcementsIntroduction}
        <div class="page-description">
          {$announcementsIntroduction|strip_unsafe_html}
        </div>
      {/if}
    </div>
    {if !$announcements|@count}
      <div class="page-content">
        {translate key="plugins.themes.individualizeTheme.announcements.none"}
      </div>
    {else}
      <ol class="announcements-list" start="1">
        {foreach from=$announcements item="announcement"}
          {capture assign="url"}{url page="announcement" op="view" path=$announcement->getId()}{/capture}
          <article class="announcements-summary">
            <a href="{$url}" class="tab-focus">
              <h2 class="announcements-summary-title">
                {$announcement->getLocalizedTitle()|escape}
              </h2>
            </a>
            <div class="announcements-summary-date">
              {$announcement->getDatePosted()|date_format:$dateFormatLong}
            </div>
            <div class="announcements-summary-desc html-text">
              {$announcement->getLocalizedDescriptionShort()|strip_unsafe_html}
            </div>
            <a
              class="arrow-link tab-focus"
              href="{$url}"
              aria-label="{translate
                key="common.readMoreWithTitle"
                title=$announcement->getLocalizedTitle()|escape
              }"
            >
              {translate key="common.readMore"}
              {include file="frontend/icons/arrow-right.svg"}
            </a>
          </article>
        {/foreach}
      </ol>
    {/if}
  </main>

{/block}
{if $announcements && $announcements|@count}
  <section class="homepage-block homepage-block-announcement">
    <h2 class="sr-only">{translate key="announcement.announcements"}</h2>

    {foreach from=$announcements item="announcement"}
      {capture assign="actions"}{strip}
        <a
          class="button"
          href="{url page="announcement" op="view" path=$announcement->getId()}"
          aria-label="{translate
            key="common.readMoreWithTitle"
            title=$announcement->getLocalizedTitle()|escape
          }"
        >
          {translate key="common.readMore"}
        </a>
      {/strip}{/capture}
      {include
        file="frontend/components/notice.tpl"
        title="<h3>{$announcement->getDatePosted()|date_format:$dateFormatLong}</h3>"
        content=$announcement->getLocalizedTitle()|escape
        actions=$actions
      }
    {/foreach}

  </section>
{/if}
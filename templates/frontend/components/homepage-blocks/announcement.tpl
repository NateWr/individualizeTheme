{if $announcements && $announcements|@count}
  <section class="homepage-block homepage-block-announcement">

    {foreach from=$announcements item="announcement"}
      {capture assign="actions"}{strip}
        <a class="button" href="{url page="announcement" op="view" path=$announcement->getId()}">
          {translate key="common.readMore"}
        </a>
      {/strip}{/capture}
      {include
        file="frontend/components/notice.tpl"
        title="<h2>{$announcement->getDatePosted()|date_format:$dateFormatLong}</h2>"
        content=$announcement->getLocalizedTitle()|escape
        actions=$actions
      }
    {/foreach}

  </section>
{/if}
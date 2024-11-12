{if $announcements|@count}
  {assign var="announcement" value=$announcements[0]}

  {capture assign="actions"}{strip}
    <a class="button" href="{url page="announcement" op="view" path=$announcement->getId()}">
      {translate key="common.readMore"}
    </a>
  {/strip}{/capture}

  <section class="homepage-block homepage-block-announcement">
    {include
      file="frontend/components/notice.tpl"
      title="<h2>{$announcement->getDatePosted()|date_format:$dateFormatLong}</h2>"
      content=$announcement->getLocalizedTitle()|escape
      actions=$actions
    }
  </section>
{/if}
{if $announcements && $announcements|@count}
  {assign var="announcement" value=$announcements->first()}

  {capture assign="actions"}{strip}
    <a class="button" href="{url page="announcement" op="view" path=$announcement->id}">
      {translate key="common.readMore"}
    </a>
  {/strip}{/capture}

  <section class="homepage-block homepage-block-announcement">
    {assign var="datePosted" value=$announcement->datePosted}
    {include
      file="frontend/components/notice.tpl"
      title="<h2>{$announcement->datePosted->format($dateFormatLong)}</h2>"
      content=$announcement->getLocalizedData('title')|escape
      actions=$actions
    }
  </section>
{/if}
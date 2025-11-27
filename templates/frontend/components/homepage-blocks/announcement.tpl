{if $announcements && $announcements|@count}
  <section class="homepage-block homepage-block-announcement">
    <h2 class="sr-only">{translate key="announcement.announcements"}</h2>

    {foreach from=$announcements item="announcement"}

      {capture assign="actions"}{strip}
        <a
          class="button"
          href="{url page="announcement" op="view" path=$announcement->id}"
          aria-label="{$announcement->getLocalizedData('title')|escape}"
        >
          {translate key="common.readMore"}
        </a>
      {/strip}{/capture}

      {assign var="datePosted" value=$announcement->datePosted}
      {include
        file="frontend/components/notice.tpl"
        title="<h3>{$announcement->datePosted->format($dateFormatLong)}</h3>"
        content=$announcement->getLocalizedData('title')|escape
        actions=$actions
      }
    {/foreach}
  </section>
{/if}
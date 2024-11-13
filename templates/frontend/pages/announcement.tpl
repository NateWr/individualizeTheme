{assign var="pageTitleTranslated" value={$announcement->getLocalizedTitle()|escape}}
{assign var="title" value={$announcement->getLocalizedTitle()|escape}}
{assign var="description" value={$announcement->getDatePosted()|date_format:$dateFormatLong}}
{capture assign="breadcrumb"}
  <div class="breadcrumb">
    {include file="frontend/icons/arrow-left.svg"}
    <a
      class="breadcrumb-item tab-focus"
      href="{url page="announcement"}"
    >
      {translate key="announcement.announcements"}
    </a>
  </div>
{/capture}
{capture assign="html"}
  {if $announcement->getLocalizedDescription()}
    {$announcement->getLocalizedDescription()|strip_unsafe_html}
  {else}
    {$announcement->getLocalizedDescriptionShort()|strip_unsafe_html}
  {/if}
{/capture}

{extends file="frontend/layout-basic.tpl"}
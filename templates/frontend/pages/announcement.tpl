{assign var="pageTitleTranslated" value={$announcement->getLocalizedTitle()|escape}}
{assign var="title" value={$announcement->getLocalizedTitle()|escape}}
{assign var="description" value={$announcement->getDatePosted()|date_format:$dateFormatLong}}
{capture assign="breadcrumb"}
  <div class="breadcrumb">
    <a
      class="breadcrumb-item tab-focus"
      href="{url page="announcement"}"
    >
      {include file="frontend/icons/arrow-left.svg"}
      {translate key="announcement.announcements"}
    </a>
  </div>
{/capture}
{capture assign="html"}
  {if $announcement->getLocalizedDescription()}
    {$announcement->getLocalizedDescription()}
  {else}
    {$announcement->getLocalizedDescriptionShort()}
  {/if}
{/capture}

{extends file="frontend/layout-basic.tpl"}
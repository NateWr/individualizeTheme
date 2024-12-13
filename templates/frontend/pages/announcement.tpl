{assign var="pageTitleTranslated" value={$announcement->getLocalizedData('title')|escape}}
{assign var="title" value={$announcement->getLocalizedData('title')|escape}}
{assign var="description" value={$announcement->datePosted->format($dateFormatLong)}}
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
  {if $announcement->getLocalizedData('description')}
    {$announcement->getLocalizedData('description')}
  {else}
    {$announcement->getLocalizedData('descriptionShort')}
  {/if}
{/capture}

{extends file="frontend/layout-basic.tpl"}
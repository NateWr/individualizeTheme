{assign var="pageTitleTranslated" value={translate key=$pageTitle}}
{assign var="title" value=$pageTitleTranslated}
{capture assign="html"}
  {if $messageTranslated}
    {$messageTranslated}
  {else}
    {translate key=$message}
  {/if}

  {if $backLink}
    <p>
      <a href="{$backLink}">{translate key=$backLinkLabel}</a>
    </p>
  {/if}
{/capture}

{extends file="frontend/layout-basic.tpl"}
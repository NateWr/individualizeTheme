{assign var="pageTitleTranslated" value={translate key=$pageTitle}}
{assign var="title" value=$pageTitleTranslated}
{capture assign="html"}
  {translate key=$errorMsg params=$errorParams}

  {if $backLink}
    <p>
      <a href="{$backLink}">{translate key=$backLinkLabel}</a>
    </p>
  {/if}
{/capture}

{extends file="frontend/layout-basic.tpl"}
{assign var="pageTitleTranslated" value=$title|escape}
{assign var="title" value=$pageTitleTranslated}
{capture assign="html"}
  {$content}
{/capture}

{extends file="frontend/layout-basic.tpl"}
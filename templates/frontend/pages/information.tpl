{assign var="pageTitleTranslated" value={translate key=$pageTitle}}
{assign var="title" value=$pageTitleTranslated}
{capture assign="html"}
  {$content}
{/capture}

{extends file="frontend/layout-basic.tpl"}

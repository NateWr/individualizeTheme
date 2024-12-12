{assign var="pageTitleTranslated" value={translate key="common.editorialHistory"}}
{assign var="title" value=$pageTitleTranslated}
{capture assign="html"}
  {$currentContext->getLocalizedData('editorialHistory')}
{/capture}

{extends file="frontend/layout-basic.tpl"}
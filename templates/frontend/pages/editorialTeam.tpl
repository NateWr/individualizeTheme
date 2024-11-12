{assign var="pageTitleTranslated" value={translate key="about.editorialTeam"}}
{assign var="title" value=$pageTitleTranslated}
{capture assign="html"}
  {$currentContext->getLocalizedData('editorialTeam')}
{/capture}

{extends file="frontend/layout-basic.tpl"}
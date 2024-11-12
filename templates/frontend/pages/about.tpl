{assign var="pageTitleTranslated" value={translate key="about.aboutContext"}}
{assign var="title" value={translate key="about.aboutContext"}}
{capture assign="html"}
  {$currentContext->getLocalizedData('about')}
{/capture}

{extends file="frontend/layout-basic.tpl"}
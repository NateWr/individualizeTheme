{assign var="pageTitleTranslated" value={translate key="about.privacyStatement"}}
{assign var="title" value=$pageTitleTranslated}
{capture assign="html"}
  {$currentContext->getLocalizedData('privacyStatement')}
{/capture}

{extends file="frontend/layout-basic.tpl"}
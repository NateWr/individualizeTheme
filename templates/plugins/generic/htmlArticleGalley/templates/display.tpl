{assign var="parentUrl" value={url page="article" op="view" path=$article->getBestId()}}
{capture assign="galleyTitle"}{translate key="submission.representationOfTitle" representation=$galley->getLabel() title=$publication->getLocalizedFullTitle()|escape}{/capture}
{assign var="title" value=$article->getLocalizedTitle()}

{capture assign="downloadUrl"}{strip}
  {if !$isLatestPublication}
    {url page="article" op="download" path=$article->getBestId()|to_array:'version':$galleyPublication->getId():$galley->getBestGalleyId() inline=true}
  {else}
    {url page="article" op="download" path=$article->getBestId()|to_array:$galley->getBestGalleyId() inline=true}
  {/if}
{/strip}{/capture}

{capture assign="datePublished"}{strip}
  {if !$isLatestPublication}
    {translate key="submission.outdatedVersion" datePublished=$galleyPublication->getData('datePublished')|date_format:$dateFormatLong urlRecentVersion=$parentUrl}
  {/if}
{/strip}{/capture}

{extends file="frontend/layout-galley.tpl"}

{block name="content"}

  <iframe
    name="htmlFrame"
    src="{$downloadUrl}"
    title="{translate key="submission.representationOfTitle" representation=$galley->getLabel() title=$galleyPublication->getLocalizedFullTitle()|escape}"
    class="layout-galley-iframe"
    allowfullscreen
    webkitallowfullscreen
  ></iframe>

{/block}
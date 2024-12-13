{assign var="pageTitleTranslated" value={translate key="common.editorialMasthead"}}
{assign var="title" value=$pageTitleTranslated}
{capture assign="html"}
	{include file="frontend/components/masthead.tpl"}
	<p>
    {capture assign="editorialHistoryUrl"}{url page="about" op="editorialHistory"}{/capture}
    {translate key="about.editorialMasthead.linkToEditorialHistory" url=$editorialHistoryUrl}
  </p>
	{if $reviewers->count()}
		<h2>{translate key="common.editorialMasthead.peerReviewers"}</h2>
		<p>{translate key="common.editorialMasthead.peerReviewers.description" year=$previousYear}</p>
		<ul class="masthead masthead-reviewers" role="list">
      {foreach from=$reviewers item="reviewer"}
        <li class="masthead-person">
          <h3 class="masthead-name">
            {$reviewer->getFullName()|escape}
            {if $reviewer->getData('orcid') && $reviewer->getData('orcidAccessToken')}
              <span class="masthead-orcid">
                <a
                  href="{$reviewer->getData('orcid')|escape}"
                  target="_blank"
                  aria-label="{translate key="common.editorialHistory.page.orcidLink" name=$reviewer->getFullName()|escape}"
                >
                  {$orcidIcon}
                </a>
              </span>
            {/if}
          </h3>
          {if !empty($reviewer->getLocalizedData('affiliation'))}
            <div class="masthead-affiliation">
              {$reviewer->getLocalizedData('affiliation')|escape}
            </div>
          {/if}
        </li>
      {/foreach}
		</ul>
	{/if}
{/capture}

{extends file="frontend/layout-basic.tpl"}
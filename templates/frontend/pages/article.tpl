{assign var="pageTitleTranslated" value=$article->getLocalizedFullTitle()|escape}

{foreach from=$pubIdPlugins item="pubIdPlugin"}
  {if $pubIdPlugin->getPubIdType() != 'doi'}
    {continue}
  {/if}
  {if !$article->getStoredPubId($pubIdPlugin->getPubIdType())}
    {continue}
  {/if}
  {assign var="doiUrl" value=$pubIdPlugin->getResolvingURL($currentJournal->getId(), $article->getStoredPubId($pubIdPlugin->getPubIdType()))}
  {capture assign="doiLink"}{strip}
    <a class="arrow-link tab-focus" href="{$doiUrl|escape}">
      {$doiUrl|escape}
      {include file="frontend/icons/arrow-right.svg"}
    </a>
  {/capture}
  {break}
{/foreach}

{extends file="frontend/layout.tpl"}

{block name="content"}

  <div class="
    max-w-screen-xl mx-auto flex flex-col gap-8 my-8 xl:gap-16 xl:my-0
  ">
    <div class="breadcrumb">
      <a
        class="breadcrumb-item tab-focus"
        href="{url page="issue" op="archive"}"
      >
        {include file="frontend/icons/arrow-left.svg"}
        {translate key="navigation.archives"}
      </a>
      <span class="breadcrumb-separator">/</span>
      {if $section}
        <span class="breadcrumb-item{if $issue} breadcrumb-item-last{/if}">
          {$section->getLocalizedTitle()}
        </span>
        <span class="breadcrumb-separator">/</span>
      {/if}
      {if $issue}
        <a
          class="breadcrumb-item breadcrumb-item-last tab-focus"
          href="{url page="issue" op="view" path=$issue->getBestIssueId()}"
        >
          {$issue->getIssueIdentification(['showTitle' => false, 'showYear' => false])}
        </a>
      {/if}
    </div>

    <article class="article">

      {* Preview and old version notices *}
      {if $publication->getData('status') !== $smarty.const.STATUS_PUBLISHED}
        {capture assign="submissionUrl"}{url page="workflow" op="access" path=$article->getId()}{/capture}
        {capture assign="notice"}
          <p>{translate key="submission.viewingPreview" url=$submissionUrl}</p>
        {/capture}
        {include
          file="frontend/components/notice.tpl"
          title="{translate key="common.preview"}"
          content=$notice
        }
	    {elseif $currentPublication->getId() !== $publication->getId()}
  			{capture assign="latestVersionUrl"}{url page="article" op="view" path=$article->getBestId()}{/capture}
        {capture assign="notice"}{strip}
          <p>
            {translate key="submission.outdatedVersion"
              datePublished=$publication->getData('datePublished')|date_format:$dateFormatShort
              urlRecentVersion=$latestVersionUrl|escape
            }
          </p>
        {/strip}{/capture}
        {include
          file="frontend/components/notice.tpl"
          title=""
          content=$notice
        }
      {/if}

      {* Title and Authors *}
      <div class="article-header">
        <h1 class="article-title">
          {$publication->getLocalizedFullTitle()|strip_unsafe_html}
        </h1>
        {if $publication->getData('authors')}
          <h2 class="sr-only">
            {translate key="article.authors"}
          </h2>
          {assign var="hasAffiliations" value=false}
          {foreach from=$publication->getData('authors') item="author"}
            {if $author->getLocalizedData('affiliation') || $author->getData('orcid')}
              {assign var="hasAffiliations" value=true}
            {/if}
          {/foreach}
          {assign var="showAuthors" value=$activeTheme->getOption('showAuthors')}
          {if !$showAuthors}
            {if $hasAffiliations && $publication->getData('authors')|count < 5}
              {assign var="showAuthors" value="detailed"}
            {else}
              {assign var="showAuthors" value="simple"}
            {/if}
          {/if}
          {if $showAuthors === 'detailed'}
            {include file="frontend/components/author-details.tpl"}
          {else}
            <address class="article-authors">
              {if $authorUserGroups}
                {$publication->getAuthorString($authorUserGroups)|escape}
              {else}
                {$publication->getShortAuthorString()|escape}
              {/if}
              {if $hasAffiliations}
                {assign var="authorDetailsId" value="author-details"}
                <div
                  class="collapse article-author-details"
                  id="{$authorDetailsId}"
                >
                  {include file="frontend/components/author-details.tpl"}
                </div>
                <button
                  class="arrow-link article-authors-more-details"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#{$authorDetailsId}"
                  aria-expanded="false"
                  aria-controls="{$authorDetailsId}"
                >
                  <span class="article-author-details-expand" aria-hidden="true">
                    {include file="frontend/icons/plus.svg"}
                  </span>
                  <span class="article-author-details-collapse" aria-hidden="true">
                    {include file="frontend/icons/minus.svg"}
                  </span>
                  {translate key="search.authorDetails"}
                </button>
              {/if}
            </address>
          {/if}
        {/if}
      </div>

      <div class="article-details">

        {* Galleys and Cover Image *}
        <div class="article-media">
          <div class="article-galleys">
            <h2 class="sr-only">
              {translate key="submission.files"}
            </h2>
            {if $primaryGalleys|count || $supplementaryGalleys|count}
              <div class="article-galleys-list">
                {foreach from=$primaryGalleys item="galley"}
                  {include
                    file="frontend/components/galley-link.tpl"
                    galley=$galley
                    publication=$publication
                    submission=$submission
                    label=$galley->getGalleyLabel()|escape
                  }
                {/foreach}
                {foreach from=$supplementaryGalleys item="galley"}
                  {include
                    file="frontend/components/galley-link.tpl"
                    galley=$galley
                    publication=$publication
                    submission=$submission
                    label=$galley->getGalleyLabel()|escape
                    isSupplementary=true
                  }
                {/foreach}
              </div>
            {/if}
          </div>
          {if $publication->getLocalizedData('coverImage')}
            {assign var="coverImage" value=$publication->getLocalizedData('coverImage')}
            <img
              class="article-cover-image"
              src="{$publication->getLocalizedCoverImageUrl($article->getData('contextId'))|escape}"
              alt="{$coverImage.altText|escape|default:''}"
            >
          {/if}
        </div>

        {* Optional metadata table (DOI, Published Date, etc *}
        <div class="article-metadata">
          {if $activeTheme->getOption('highlightArticleMetadata')|count}
            <table class="article-metadata-table">
              <tbody>
                {foreach from=$activeTheme->getOption('highlightArticleMetadata') item="type"}
                  {if $type === 'doi' && $doiLink}
                    {include
                      file="frontend/components/metadata-tr-html.tpl"
                      title=$pubIdPlugin->getPubIdDisplayType()
                      html=$doiLink
                    }
                  {/if}
                  {if $type === 'keywords'}
                    {include
                      file="frontend/components/metadata-tr-keywords.tpl"
                      keywords=$publication->getLocalizedData('keywords')
                    }
                  {/if}
                  {if $type === 'published'}
                    {include
                      file="frontend/components/metadata-tr-published.tpl"
                      article=$article
                      publication=$publication
                      firstPublication=$firstPublication
                      currentPublication=$currentPublication
                    }
                  {/if}
                  {if $type === 'published-by'}
                    {include
                      file="frontend/components/metadata-tr-published-by.tpl"
                      issue=$issue
                    }
                  {/if}
                  {if $type === 'how-to-cite'}
                    {include
                      file="frontend/components/metadata-tr-how-to-cite.tpl"
                      id="how-to-cite-header"
                      citation=$citation
                      citationStyles=$citationStyles
                      citationDownloads=$citationDownloads
                      citationArgs=$citationArgs
                      citationArgsJson=$citationArgsJson
                    }
                  {/if}
                {/foreach}
              </tbody>
            </table>
          {/if}
        </div>

        <div class="article-sections">

          {* Abstract *}
          {if $publication->getLocalizedData('abstract')}
            <section class="article-section article-section-abstract">
              <h2 class="article-section-title">
                {translate key="article.abstract"}
              </h2>
              <div class="html-text">
                {$publication->getLocalizedData('abstract')|strip_unsafe_html}
              </div>
              <div class="article-abstract-galleys">
                {foreach from=$primaryGalleys item="galley"}
                  {include
                    file="frontend/components/galley-link.tpl"
                    galley=$galley
                    publication=$publication
                    submission=$submission
                    label=$galley->getGalleyLabel()|escape
                  }
                {/foreach}
              </div>
            </section>
          {/if}

          {**
           * Full text of the article
           *
           * Use this hook in a plugin or child theme to display full
           * article text on the landing page.
           *}
          {capture assign="individualizeFullTextHtml"}{strip}
            {call_hook name="IndividualizeTheme::FullText"}
          {/strip}{/capture}

          {if $individualizeFullTextHtml}
            {$individualizeFullTextHtml}

          {* References from publication data *}
          {elseif $parsedCitations || $publication->getData('citationsRaw')}
            <section class="article-section article-section-references">
              <h2 class="article-section-title">
                {translate key="submission.citations"}
              </h2>
              <div class="html-text" data-reveal data-height="40">
                {if $parsedCitations}
                  {foreach from=$parsedCitations item="parsedCitation"}
                    <p>{$parsedCitation->getCitationWithLinks()|strip_unsafe_html} {call_hook name="Templates::Article::Details::Reference" citation=$parsedCitation}</p>
                  {/foreach}
                {else}
                  {$publication->getData('citationsRaw')|escape|nl2br}
                {/if}
              </div>
            </section>
          {/if}

          {* Metadata table (DOI, Issue, Section, etc.) *}
          <section class="article-section">
            <h2 class="article-section-title" id="article-metadata-title">
              {translate key="article.details"}
            </h2>
            {call_hook name="Templates::Article::Details"}
            <table
              class="article-metadata-table"
              aria-labelledby="article-metadata-title"
            >
              <tbody>
                {foreach from=$pubIdPlugins item="pubIdPlugin"}
                  {if !$article->getStoredPubId($pubIdPlugin->getPubIdType())}
                    {continue}
                  {/if}
                  {assign var="pubIdUrl" value=$pubIdPlugin->getResolvingURL($currentJournal->getId(), $article->getStoredPubId($pubIdPlugin->getPubIdType()))}
                  {capture assign="pubIdLink"}{strip}
                    <a class="arrow-link tab-focus" href="{$doiUrl|escape}">
                      {$doiUrl|escape}
                      {include file="frontend/icons/arrow-right.svg"}
                    </a>
                  {/capture}
                  {include
                    file="frontend/components/metadata-tr-html.tpl"
                    title=$pubIdPlugin->getPubIdDisplayType()
                    html=$pubIdLink
                  }
                {/foreach}
                {include
                  file="frontend/components/metadata-tr-published.tpl"
                  article=$article
                  publication=$publication
                  firstPublication=$firstPublication
                  currentPublication=$currentPublication
                }
                {include
                  file="frontend/components/metadata-tr-issue.tpl"
                  issue=$issue
                }
                {if $section}
                  {include
                    file="frontend/components/metadata-tr-html.tpl"
                    title={translate key="section.section"}
                    html={$section->getLocalizedTitle()|strip_unsafe_html}
                  }
                {/if}
                {include
                  file="frontend/components/metadata-tr-categories.tpl"
                  categories=$categories
                }
                {if !empty($publication->getLocalizedData('keywords'))}
                  {include
                    file="frontend/components/metadata-tr-keywords.tpl"
                    keywords=$publication->getLocalizedData('keywords')
                  }
                {/if}
                {include
                  file="frontend/components/metadata-tr-how-to-cite.tpl"
                  id="how-to-cite"
                  citation=$citation
                  citationStyles=$citationStyles
                  citationDownloads=$citationDownloads
                  citationArgs=$citationArgs
                  citationArgsJson=$citationArgsJson
                }
                {if $publication->getData('pages')}
                  {include
                    file="frontend/components/metadata-tr-html.tpl"
                    title={translate key="editor.issues.pages"}
                    html=$publication->getData('pages')
                  }
                {/if}
                {include
                  file="frontend/components/metadata-tr-license.tpl"
                  publication=$publication
                  ccLicenseBadge=$ccLicenseBadge
                }
              </tbody>
            </table>
          </section>


          {call_hook name="Templates::Article::Footer::PageFooter"}
        </div>
      </div>
    </article>
  </div>

{/block}
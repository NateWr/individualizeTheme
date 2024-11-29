{assign var="pageTitleTranslated" value={translate key="editor.issues.currentIssue"}}
{if $issue}
  {assign var="pageTitleTranslated" value={$issue->getIssueIdentification()|escape}}
{/if}

{extends file="frontend/layout.tpl"}

{block name="content"}

  <div class="
    max-w-screen-xl mx-auto flex flex-col gap-8 my-8
    xl:gap-16 xl:my-0
  ">
    {if !$issue}
      {include
        file="frontend/components/notice.tpl"
        title="<h1>{translate key="current.noCurrentIssue"}</h2>"
        content={translate key="current.noCurrentIssueDesc"}
      }
    {else}
      <div class="breadcrumb">
        <a
          class="breadcrumb-item tab-focus"
          href="{url page="issue" op="archive"}"
        >
          {include file="frontend/icons/arrow-left.svg"}
          {translate key="navigation.archives"}
        </a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-item breadcrumb-item-last">
          {$issue->getIssueIdentification(['showTitle' => false, 'showYear' => false])}
        </span>
      </div>
      {include file="frontend/components/issue-toc.tpl"}
    {/if}
  </div>

{/block}

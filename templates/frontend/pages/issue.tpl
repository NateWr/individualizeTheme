{assign var="pageTitleTranslated" value={translate key="editor.issues.currentIssue"}}
{if $issue}
  {assign var="pageTitleTranslated" value={$issue->getIssueIdentification()|escape}}
{/if}

{extends file="frontend/layout.tpl"}

{block name="content"}

  <div class="
    max-w-screen-xl mx-auto my-8 flex flex-col gap-8
    xl:my-0 xl:gap-16
  ">
    {if !$issue}
      <main id="skip-to-main">
        {include
          file="frontend/components/notice.tpl"
          title="<h1>{translate key="current.noCurrentIssue"}</h2>"
          content={translate key="current.noCurrentIssueDesc"}
        }
      </main>
    {else}
      <div class="breadcrumb">
        <span class="sr-only">
          {translate key="navigation.breadcrumbLabel"}
        </span>
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
      <main
        id="skip-to-main"
        class="
          flex flex-col gap-8
          xl:gap-16
        "
      >
        {include file="frontend/components/issue-toc.tpl"}
      </main>
    {/if}
  </div>

{/block}

{extends file="frontend/layout.tpl"}

{block name="content"}

  <main
    id="skip-to-main"
    class="
      homepage-blocks
      flex flex-col items-center gap-32 my-16
      2xl:my-24 2xl:gap-48
    "
  >
    <h1 class="sr-only">
      {$currentContext->getLocalizedName()}
    </h1>

    {if $homepageBlocks}
      {foreach from=$homepageBlocks item="template"}
        {include file=$template}
      {/foreach}
    {/if}
  </main>

{/block}
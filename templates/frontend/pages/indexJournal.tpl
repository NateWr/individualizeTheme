{extends file="frontend/layout.tpl"}

{block name="content"}

  {if $homepageBlocks}
    <div class="
      homepage-blocks
      flex flex-col items-center gap-32 my-16
      2xl:my-24 2xl:gap-48
    ">
      {foreach from=$homepageBlocks item="template"}
        {include file=$template}
      {/foreach}
    </div>
  {/if}

{/block}
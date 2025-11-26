{capture assign="hookContent"}{strip}
  {call_hook name="Templates::Index::journal"}
{/strip}{/capture}

{if $hookContent}
  <section class="homepage-block homepage-block-plugin-hook html-text">
    {$hookContent}
  </section>
{/if}
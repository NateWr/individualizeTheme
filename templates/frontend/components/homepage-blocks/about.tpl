{if $currentContext}
  {capture assign="description"}{strip}{$currentContext->getLocalizedData('description')}{/strip}{/capture}
  {if $currentContext->getLocalizedData('additionalHomeContent')}
    {capture assign="description"}{strip}{$currentContext->getLocalizedData('additionalHomeContent')}{/strip}{/capture}
  {/if}

  <section class="homepage-block homepage-block-about">
    {if $description}
      <div class="homepage-block-about-desc html-text">
        <h2 class="sr-only">{translate key="about.aboutContext"}</h2>
        {$description}
      </div>
    {/if}
    {load_menu name="homepage" path="frontend/components/menu-homepage.tpl"}
  </section>
{/if}
{if $currentContext}
  {capture assign="actions"}{strip}
    <a class="button" href="{url page="about" op="submissions"}">
      {$activeTheme->getLocalizedOption('howToSubmitAction')|escape}
    </a>
  {/strip}{/capture}

  <section class="homepage-block homepage-block-how-to-submit">
    {include
      file="frontend/components/notice.tpl"
      title="<h2>{$activeTheme->getLocalizedOption('howToSubmitTitle')|replace:'{$journalName}':$currentContext->getLocalizedData('name')|escape}</h2>"
      content=$activeTheme->getLocalizedOption('howToSubmitText')|replace:'{$journalName}':$currentContext->getLocalizedData('name')|strip_unsafe_html
      actions=$actions
    }
  </section>
{/if}
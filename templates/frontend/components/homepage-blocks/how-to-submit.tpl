{if $currentContext}
  {capture assign="actions"}{strip}
    <a class="button" href="{url page="about" op="submissions"}">
      {$activeTheme->getOption('howToSubmitAction')|escape}
    </a>
  {/strip}{/capture}

  <section class="homepage-block homepage-block-how-to-submit">
    {include
      file="frontend/components/notice.tpl"
      title="<h2>{$activeTheme->getOption('howToSubmitTitle')|replace:'{$journalName}':$currentContext->getLocalizedData('name')|escape}</h2>"
      content=$activeTheme->getOption('howToSubmitText')|replace:'{$journalName}':$currentContext->getLocalizedData('name')|strip_unsafe_html
      actions=$actions
    }
  </section>
{/if}
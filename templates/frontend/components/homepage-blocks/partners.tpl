{if $partnerLogosHtml}
  <div class="homepage-block homepage-block-partners">
    <div class="homepage-block-partners-header">
      <h2 class="homepage-block-partners-title">
        {$activeTheme->getOption('partnersTitle')|escape}
      </h2>
      <div class="homepage-block-partners-desc">
        {$activeTheme->getOption('partnersDescription')|strip_unsafe_html}
      </div>
    </div>
    {$partnerLogosHtml}
  </div>
{/if}

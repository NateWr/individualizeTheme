{**
 * Notice / Alert Block
 *
 * Used to bring attention to a short notice, call to action, or
 * similar block of content.
 *
 * Uses the block background and text colors, which in simple mode are
 * the same as the header colors.
 *}
<div class="
  notice
  {if $actions}
    notice-with-actions
  {/if}
">
  <div class="notice-content">
    {if $title}
      <div class="notice-title">
        {$title}
      </div>
    {/if}
    {$content}
  </div>
  {if $actions}
    <div class="notice-actions">
      {$actions}
    </div>
  {/if}
</div>

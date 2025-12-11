{**
 * Layout for basic HTML content pages, like the about or
 * contact page. Also used for custom pages.
 *
 * @param string $title The title of the page
 * @param string $description (optional) A short description of the page
 * @param string $html The HTML content to display on the page
 * @param string $breadcrumb (optional) HTML for a breadcrumb navigation
 *}
{extends file="frontend/layout.tpl"}

{block name="content"}

  <div class="page">
    {$breadcrumb}
    <main class="page-wrapper" id="skip-to-main">
      <div class="page-heading">
        <h1 class="page-title">
          {$title}
        </h1>
        {if $description}
          <div class="page-description">
            {$description}
          </div>
        {/if}
      </div>
      <div class="page-content html-text">
        {$html}
      </div>
    </main>
  </div>

{/block}
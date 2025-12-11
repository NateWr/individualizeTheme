{if $citation}
  <div class="article-metadata-table-row">
    <h3 class="article-metadata-table-heading">
      {translate key="submission.howToCite"}
    </h3>
    <div class="how-to-cite" data-citation>
      <div id="{$id}" class="citation-output" role="region" aria-live="polite" data-citation-output>
        {$citation}
      </div>
      <button
        class="dropdown-toggle tab-focus"
        data-bs-toggle="dropdown"
        aria-expanded="false"
      >
        {translate key="submission.howToCite.citationFormats"}
        {include file="frontend/icons/dropdown.svg"}
      </button>
      <ul class="dropdown-menu" data-open="false">
        {foreach from=$citationStyles item="citationStyle"}
          <li>
            <button
              aria-controls="{$id}"
              class="dropdown-item tab-focus"
              data-load-citation="true"
              data-json-href="{url page="citationstylelanguage" op="get" path=$citationStyle.id params=$citationArgsJson}"
              rel="nofollow"
            >
              {$citationStyle.title|escape}
            </button>
          </li>
        {/foreach}
        {if $citationDownloads}
          <li>
            <hr class="dropdown-divider">
          </li>
          {foreach from=$citationDownloads item="citationDownload"}
            <li>
              <a class="dropdown-item tab-focus" href="{url page="citationstylelanguage" op="download" path=$citationDownload.id params=$citationArgs}">
                {$citationDownload.title|escape}
              </a>
            </li>
          {/foreach}
        {/if}
      </ul>
    </div>
  </div>
{/if}
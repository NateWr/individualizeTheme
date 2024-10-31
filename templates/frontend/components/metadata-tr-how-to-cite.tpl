{if $citation}
  <tr>
    <th>
      {translate key="submission.howToCite"}
    </th>
    <td>
      <div class="how-to-cite">
        <div id="citationOutput" class="citation-output" role="region" aria-live="polite">
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
                aria-controls="citationOutput"
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
                <a class="dropdown-item" href="{url page="citationstylelanguage" op="download" path=$citationDownload.id params=$citationArgs}">
                  {$citationDownload.title|escape}
                </a>
              </li>
            {/foreach}
          {/if}
        </ul>
      </div>
    </td>
  </tr>
{/if}
<tr>
  <th>
    {translate key="submission.status.published"}
  </th>
  <td>
    {if !$publication->getData('datePublished')}
      <div>{translate key="publication.status.unpublished"}</div>
    {else}
      <div>
        {if $firstPublication->getId() === $publication->getId()}
          {$firstPublication->getData('datePublished')|date_format:$dateFormatLong}
        {else}
          {translate
            key="submission.updatedOn"
            datePublished=$firstPublication->getData('datePublished')|date_format:$dateFormatLong
            dateUpdated=$publication->getData('datePublished')|date_format:$dateFormatLong
          }
        {/if}
      </div>
      {if $article->getPublishedPublications()|@count > 1}
        <button
          class="dropdown-toggle tab-focus"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          {translate key="admin.versionHistory"}
          {include file="frontend/icons/dropdown.svg"}
        </button>
        <ul class="dropdown-menu" data-open="false">
          {foreach from=$article->getPublishedPublications() item="version"}
            {capture assign="versionName"}{translate key="publication.version" version=$version->getData('version')}{/capture}
            <li class="dropdown-item">
              {if $version->getId() === $publication->getId()}
                {$versionName}
              {else}
                {capture assign="versionUrl"}{strip}
                  {if $version->getId() === $currentPublication->getId()}
                    {url page="article" op="view" path=$article->getBestId()}
                  {else}
                    {url page="article" op="view" path=$article->getBestId()|to_array:"version":$version->getId()}
                  {/if}
                {/strip}{/capture}
                <a href="{$versionUrl}" class="link">
                  {$versionName}
                </a>
                {translate key="plugins.themes.individualizeTheme.parenthesis" text=$version->getData('datePublished')|date_format:$dateFormatLong}
              {/if}
            </li>
          {/foreach}
        </ul>
      {/if}
    {/if}
  </td>
</tr>
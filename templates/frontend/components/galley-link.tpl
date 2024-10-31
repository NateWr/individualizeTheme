{**
 * A single galley link
 *
 * @param Galley $galley
 * @param Publication $publication
 * @param Submission $submission
 * @param string $label The text for the button
 * @param bool $isSupplementary Is this a supplementary file?
 *}
<a
  class="button {if $isSupplementary}button-text{/if}"
  {* Handle URLs to old versions *}
  {if $publication->getId() !== $article->getData('currentPublicationId')}
    href="{url page="article" op="view" path=$article->getBestId()|to_array:"version":$publication->getId():$galley->getBestGalleyId()}"
  {else} href="
    {url page="article" op="view" path=$article->getBestId()|to_array:$galley->getBestGalleyId()}"
  {/if}>
  {if $galley->isPdfGalley()}
    {include file="frontend/icons/download.svg"}
  {/if}
  <span class="truncate-text">
    {$label|strip_unsafe_html}
  </span>
</a>
{**
 * Search input field with a hidden label and a search icon
 *
 * @var string $label default: Search
 * @var string $id
 * @var string $name
 * @var string $placeholder default: Search
 * @var string $value
 *}

{if !$placeholder}
  {assign var="placeholder" value={translate key="common.search"}}
{/if}

{if !$label}
  {assign var="label" value={translate key="common.search"}}
{/if}

<div class="search-input">
  <label for="{$id}" class="sr-only">
    {$label}
  </label>
  {include file="frontend/icons/search.svg"}
  <input
    type="search"
    name="{$name|escape}"
    id="{$id|escape}"
    placeholder="{$placeholder|escape}"
    value="{$value|escape}"
  >
</div>

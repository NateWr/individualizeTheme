{if $categories}
  <tr>
    <th>
      {translate key="category.category"}
    </th>
    <td>
      <div>
        {foreach from=$categories item="category" name="categories"}
          <a class="link" href="{url page="catalog" op="category" path=$category->getPath()|escape}">
            {$category->getLocalizedTitle()|escape}</a>{if !$smarty.foreach.categories.last},{/if}
        {/foreach}
      </div>
    </td>
  </tr>
{/if}
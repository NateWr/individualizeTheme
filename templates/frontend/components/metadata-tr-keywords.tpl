{if !empty($publication->getLocalizedData('keywords'))}
  <tr>
    <th>
      {translate key="common.keywords"}
    </th>
    <td>
      {foreach name="keywords" from=$publication->getLocalizedData('keywords') item="keyword"}
        {$keyword|escape}{if !$smarty.foreach.keywords.last}{translate key="common.commaListSeparator"}{/if}
      {/foreach}
    </td>
  </tr>
{/if}
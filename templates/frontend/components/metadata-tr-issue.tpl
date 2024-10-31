{if $issue}
  <tr>
    <th>
      {translate key="issue.issue"}
    </th>
    <td>
      <div class="html-text">
        <a class="arrow-link tab-focus" href="{url page="issue" op="view" path=$issue->getBestIssueId()}">
          {$issue->getIssueIdentification()}
          {include file="frontend/icons/arrow-right.svg"}
        </a>
      </div>
    </td>
  </tr>
{/if}
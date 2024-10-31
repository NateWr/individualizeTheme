{if $currentContext->getLocalizedData('licenseTerms') || $publication->getData('licenseUrl')}
  <tr>
    <th>
      {translate key="submission.license"}
    </th>
    <td>
      <div class="html-text">
        {if $publication->getData('licenseUrl')}
          {if $ccLicenseBadge}
            {if $publication->getLocalizedData('copyrightHolder')}
              <p>{translate key="submission.copyrightStatement" copyrightHolder=$publication->getLocalizedData('copyrightHolder') copyrightYear=$publication->getData('copyrightYear')}</p>
            {/if}
            {$ccLicenseBadge}
          {else}
            <a href="{$publication->getData('licenseUrl')|escape}" class="copyright">
              {if $publication->getLocalizedData('copyrightHolder')}
                {translate key="submission.copyrightStatement" copyrightHolder=$publication->getLocalizedData('copyrightHolder') copyrightYear=$publication->getData('copyrightYear')}
              {else}
                {translate key="submission.license"}
              {/if}
            </a>
          {/if}
        {/if}
        {$currentContext->getLocalizedData('licenseTerms')|strip_unsafe_html}
      </div>
    </td>
  </tr>
{/if}
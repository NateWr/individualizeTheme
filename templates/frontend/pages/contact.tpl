{assign var="pageTitleTranslated" value={translate key="about.contact"}}
{assign var="title" value=$pageTitleTranslated}
{capture assign="html"}

  {if $mailingAddress}
    <h2>{translate key="common.mailingAddress"}</h2>
    <p>
      {$mailingAddress|nl2br|strip_unsafe_html}
    </p>
  {/if}

  {* Primary Contact *}
  {if $contactTitle || $contactName || $contactAffiliation || $contactPhone || $contactEmail}
    <h2>{translate key="about.contact.principalContact"}</h2>
    <p>
      {if $contactName}
        {$contactName|escape}<br>
      {/if}
      {if $contactTitle}
        {$contactTitle|escape}<br>
      {/if}
      {if $contactAffiliation}
        {$contactAffiliation|strip_unsafe_html}<br>
      {/if}
      {if $contactPhone}
        {$contactPhone|escape}<br>
      {/if}
      {if $contactEmail}
        {mailto address=$contactEmail encode='javascript'}
      {/if}
    </p>
  {/if}

  {* Technical contact *}
  {if $supportName || $supportPhone || $supportEmail}
    <h2>{translate key="about.contact.supportContact"}</h2>
    <p>
      {if $supportName}
        {$supportName|escape}<br>
      {/if}
      {if $supportPhone}
        {$supportPhone|escape}<br>
      {/if}
      {if $supportEmail}
        {mailto address=$supportEmail encode='javascript'}
      {/if}
    </p>
  {/if}

{/capture}

{extends file="frontend/layout-basic.tpl"}
{assign var="pageTitleTranslated" value={translate key="about.submissions"}}
{assign var="title" value=$pageTitleTranslated}
{capture assign="html"}
  {if $sections|@count == 0 || $currentContext->getData('disableSubmissions')}
    <p>
      {translate key="author.submit.notAccepting"}
    </p>
  {else}
    {capture assign="submissionNotice"}
      <p>
        {if $isUserLoggedIn}
          {capture assign="newSubmission"}<a href="{url page="submission"}">{translate key="about.onlineSubmissions.newSubmission"}</a>{/capture}
          {capture assign="viewSubmissions"}<a href="{url page="submissions"}">{translate key="about.onlineSubmissions.viewSubmissions"}</a>{/capture}
          {translate key="about.onlineSubmissions.submissionActions" newSubmission=$newSubmission viewSubmissions=$viewSubmissions}
        {else}
          {capture assign="login"}<a href="{url page="login"}">{translate key="about.onlineSubmissions.login"}</a>{/capture}
          {capture assign="register"}<a href="{url page="user" op="register"}">{translate key="about.onlineSubmissions.register"}</a>{/capture}
          {translate key="about.onlineSubmissions.registrationRequired" login=$login register=$register}
        {/if}
      </p>
    {/capture}
    <div class="notice">
      <div class="notice-content">
        {$submissionNotice|strip_unsafe_html}
      </div>
    </div>
    {if $submissionChecklist}
      <h2>{translate key="about.submissionPreparationChecklist"}</h2>
      {$submissionChecklist}
    {/if}
    {if $currentContext->getLocalizedData('authorGuidelines')}
      <h2>{translate key="about.authorGuidelines"}</h2>
      {$currentContext->getLocalizedData('authorGuidelines')}
    {/if}
    {if $currentContext->getLocalizedData('copyrightNotice')}
      <h2>{translate key="about.copyrightNotice"}</h2>
      {$currentContext->getLocalizedData('copyrightNotice')}
    {/if}
  {/if}
{/capture}

{extends file="frontend/layout-basic.tpl"}
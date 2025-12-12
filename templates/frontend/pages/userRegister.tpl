{assign var="pageTitleTranslated" value={translate key="user.register"}}

{extends file="frontend/layout.tpl"}

{block name="content"}

  <main class="register" id="skip-to-main">
    <h1 class="register-title">
      {translate key="user.register"}
    </h1>
    {if $isError}
      <div class="register-errors html-text" role="alert">
        <p>
          <strong>{translate key="form.errorsOccurred"}</strong>
        </p>
        <ul>
          {foreach from=$errors key="field" item="message"}
            <li>
              <a href="#{$field|escape}">
                {$message}
              </a>
            </li>
          {/foreach}
        </ul>
      </div>
    {/if}

    <form
      action="{url op="register"}"
      class="register-form"
      method="post"
    >
      {csrf}
      <input type="hidden" name="source" value="{$source|default:""|escape}" />

      {* Profile *}
      <fieldset class="input-fieldset register-profile">
        <legend class="input-legend">
          {translate key="user.profile"}
        </legend>
        <div class="input-wrapper">
          <label for="givenName" class="input-label">
            {translate key="user.givenName"}
            <sup class="required" aria-hidden="true">*</sup>
            <span class="sr-only">{translate key="common.required"}</span>
          </label>
          <input
            class="input"
            type="text"
            autocomplete="given-name"
            name="givenName"
            id="givenName"
            value="{$givenName|default:""|escape}"
            required
            aria-required="true"
          >
        </div>
        <div class="input-wrapper">
          <label for="familyName" class="input-label">
            {translate key="user.familyName"}
          </label>
          <input
            class="input"
            type="text"
            autocomplete="family-name"
            name="familyName"
            id="familyName"
            value="{$familyName|default:""|escape}"
          >
        </div>
        <div class="input-wrapper">
          <label for="affiliation" class="input-label">
            {translate key="user.affiliation"}
            <sup class="required" aria-hidden="true">*</sup>
            <span class="sr-only">{translate key="common.required"}</span>
          </label>
          <input
            class="input"
            type="text"
            name="affiliation"
            id="affiliation"
            value="{$affiliation|default:""|escape}"
            required
            aria-required="true"
          >
        </div>
        <div class="input-wrapper">
          <label for="country" class="input-label">
            {translate key="common.country"}
            <sup class="required" aria-hidden="true">*</sup>
            <span class="sr-only">{translate key="common.required"}</span>
          </label>
          <select
            class="input"
            name="country"
            id="country"
            required
            aria-required="true"
          >
            <option></option>
            {html_options options=$countries selected=$country}
          </select>
        </div>
      </fieldset>

      {* Login *}
      <fieldset class="input-fieldset register-login">
        <legend class="input-legend">
          {translate key="user.login"}
        </legend>
        <div class="input-wrapper">
          <label for="email" class="input-label">
            {translate key="user.email"}
            <sup class="required" aria-hidden="true">*</sup>
            <span class="sr-only">{translate key="common.required"}</span>
          </label>
          <input
            class="input"
            type="text"
            name="email"
            id="email"
            value="{$email|default:""|escape}"
            required
            aria-required="true"
          >
        </div>
        <div class="input-wrapper">
          <label for="username" class="input-label">
            {translate key="user.username"}
            <sup class="required" aria-hidden="true">*</sup>
            <span class="sr-only">{translate key="common.required"}</span>
          </label>
          <input
            class="input"
            type="text"
            name="username"
            id="username"
            value="{$username|default:""|escape}"
            required
            aria-required="true"
          >
        </div>
        <div class="input-wrapper">
          <label for="password" class="input-label">
            {translate key="user.password"}
            <sup class="required" aria-hidden="true">*</sup>
            <span class="sr-only">{translate key="common.required"}</span>
          </label>
          <input
            class="input"
            type="password"
            name="password"
            id="password"
            maxlength="255"
            required
            aria-required="true"
          >
        </div>
        <div class="input-wrapper">
          <label for="password2" class="input-label">
            {translate key="user.repeatPassword"}
            <sup class="required" aria-hidden="true">*</sup>
            <span class="sr-only">{translate key="common.required"}</span>
          </label>
          <input
            class="input"
            type="password"
            name="password2"
            id="password2"
            maxlength="255"
            required
            aria-required="true"
          >
        </div>
      </fieldset>

      {* Confirmation / Consent fields *}
      {if $currentContext}
        <fieldset class="input-fieldset register-confirm">
          <legend class="sr-only">
            {translate key="common.confirm"}
          </legend>
          {if $currentContext->getData('privacyStatement')}
            <label class="input-checkbox-wrapper">
              <input
                class="input-checkbox"
                type="checkbox"
                name="privacyConsent"
                id="privacyConsent"
                value="1"
                {if $privacyConsent}
                  checked="checked"
                {/if}
              >
              <span class="input-label html-text">
                {translate
                  key="user.register.form.privacyConsent"
                  privacyUrl=$privacyUrl
                }
              </span>
            </label>
          {/if}
          <label class="input-checkbox-wrapper">
            <input
              class="input-checkbox"
              type="checkbox"
              name="emailConsent"
              id="emailConsent"
              value="1"
              {if $emailConsent}
                checked="checked"
              {/if}
            >
            <span class="input-label">
              {translate key="user.register.form.emailConsent"}
            </span>
          </label>
          {foreach from=$reviewerUserGroups[$currentContext->getId()] item="userGroup"}
            {if !$userGroup->permitSelfRegistration}
                {continue}
            {/if}
            <label class="input-checkbox-wrapper">
              <input
                class="input-checkbox"
                type="checkbox"
                name="reviewerGroup[{$userGroup->id}]"
                id="reviewerInterests"
                value="1"
                {if in_array($userGroup->id, $userGroupIds)}
                  checked="checked"
                {/if}
              >
              <span class="input-label">
                {translate key="user.reviewerPrompt.optin"}
              </span>
            </label>
          {/foreach}
          <div
            id="reviewerInterestsInput"
            class="hidden"
          >
            <div class="input-wrapper">
              <label for="interests" class="input-label">
                {translate key="user.interests"}
              </label>
              <input
                class="input"
                type="text"
                name="interests"
                id="interests"
                value="{$interests|default:""|escape}"
              >
            </div>
          </div>

          {* recaptcha spam blocker *}
          {if $reCaptchaHtml}
            <div class="input-wrapper">
              {$reCaptchaHtml}
            </div>
          {/if}
        </fieldset>
      {/if}

      <div class="register-footer">
        <button class="button" type="submit">
          {translate key="user.register"}
        </button>
        <a
          class="link"
          href="{url page="login"}"
        >
          {translate key="user.login"}
        </a>
      </div>
    </form>
  </main>

{/block}
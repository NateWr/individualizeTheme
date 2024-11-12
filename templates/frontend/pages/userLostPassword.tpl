{extends file="frontend/layout.tpl"}

{block name="content"}

  <form
    action="{url page="login" op="requestResetPassword"}"
    class="login"
    method="post"
  >
    {csrf}

    <div class="input-fieldset">
      <h1 class="login-title">
        {translate key="user.login.resetPassword"}
      </h1>
      <div class="login-msg">
        {translate key="user.login.resetPasswordInstructions"}
      </div>
      {if $error}
        <div class="login-msg input-error">
          {translate key=$error reason=$reason}
        </div>
      {/if}

      <div class="input-wrapper">
        <label for="email" class="input-label">
          {translate key="user.login.registeredEmail"}
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
    </div>

    <button class="button" type="submit">
      {translate key="user.login.resetPassword"}
    </button>

    <div class="login-footer">
      {if !$disableUserReg}
        <a
          class="link"
          href="{url page="user" op="register" source=$source}"
        >
          {translate key="user.login.registerNewAccount"}
        </a>
        <span class="login-footer-separator">
          {translate key="navigation.breadcrumbSeparator"}
        </span>
      {/if}
      <a class="link" href="{url page="login"}">
        {translate key="user.login"}
      </a>
    </div>
  </form>

{/block}
{extends file="frontend/layout.tpl"}

{block name="content"}

  <form
    action="{$loginUrl}"
    class="login"
    method="post"
  >
    {csrf}
    <input type="hidden" name="source" value="{$source|default:""|escape}" />

    <div class="input-fieldset">
      <h1 class="login-title">
        {translate key="user.login"}
      </h1>
      {if $loginMessage}
        <div class="login-msg">
          {translate key=$loginMessage}
        </div>
      {/if}
      {if $error}
        <div class="login-msg input-error">
          {translate key=$error reason=$reason}
        </div>
      {/if}

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
          type="text"
          name="password"
          id="password"
          value="{$password|default:""|escape}"
          password="true"
          maxlength="255"
          required
          aria-required="true"
        >
      </div>

      <div class="input-checkbox-wrapper">
        <label>
          <input
            class="input-checkbox"
            type="checkbox"
            name="remember"
            id="remember"
            value="1"
            checked="{$remember}"
          >
          <span class="input-label">
            {translate key="user.login.rememberUsernameAndPassword"}
          </span>
        </label>
      </div>
    </div>

    <button class="button" type="submit">
      {translate key="user.login"}
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
      <a class="link" href="{url page="login" op="lostPassword"}">
        {translate key="user.login.forgotPassword"}
      </a>
    </div>
  </form>

{/block}
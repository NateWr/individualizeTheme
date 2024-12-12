{**
 * Small component that shows who the user is logged in as
 * and provides a logout link.
 *}
<div class="logged-in-as">
  {translate key="plugins.themes.individualizeTheme.loggedInAs" username=$currentUser->getData('userName')}
  <a
    {if $isUserLoggedInAs}
      href="{url page="login" op="signOutAsUser"}"
    {else}
      href="{url page="login" op="signOut"}"
    {/if}
    class="link"
  >
    {translate key="user.logOut"}
  </a>
</div>
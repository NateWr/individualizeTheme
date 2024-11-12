{assign var="pageTitleTranslated" value={translate key=$pageTitle}}
{assign var="title" value=$pageTitleTranslated}
{capture assign="html"}
	<p>
		{translate key="user.login.registrationComplete.instructions"}
	</p>
	<ul>
		{if array_intersect(array(ROLE_ID_MANAGER, ROLE_ID_SUB_EDITOR, ROLE_ID_ASSISTANT, ROLE_ID_REVIEWER), (array)$userRoles)}
			<li>
				<a href="{url page="submissions"}">
					{translate key="user.login.registrationComplete.manageSubmissions"}
				</a>
			</li>
		{/if}
		{if $currentContext}
			<li>
				<a href="{url page="submission"}">
					{translate key="user.login.registrationComplete.newSubmission"}
				</a>
			</li>
		{/if}
		<li>
			<a href="{url page="user" op="profile"}">
				{translate key="user.editMyProfile"}
			</a>
		</li>
		<li>
			<a href="{url page="index"}">
				{translate key="user.login.registrationComplete.continueBrowsing"}
			</a>
		</li>
	</ul>
{/capture}

{extends file="frontend/layout-basic.tpl"}
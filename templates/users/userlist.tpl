{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<table class="table table-hover">
			<thead>
				<tr><th>{message name="{$pageslug}-text-realname"}</th><th>{message name="{$pageslug}-text-usermail"}</th><th>{message name="{$pageslug}-text-edituser"}</th><th>{message name="{$pageslug}-text-deleteuser"}</th></tr>
			</thead>
			<tbody>
				{foreach from="$userlist" item="user" key="userid" }
				<tr><th>{$user->getFullName()}</th><td>{$user->getEmail()}</td><td><a href="{$cScriptPath}/{$pageslug}/edit/{$userid}" class="btn btn-small btn-warning">{message name="{$pageslug}-button-edit"}</a></td><td><a href="{$cScriptPath}/{$pageslug}/delete/{$userid}" class="btn btn-small btn-danger">{message name="{$pageslug}-button-delete"}</a></td></tr>
				{/foreach}
			</tbody>
		</table>
{/block}
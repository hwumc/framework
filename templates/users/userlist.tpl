{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<table class="table table-hover">
			<thead>
				<tr><th>{message name="{$pageslug}-text-realname"}</th><th>{message name="{$pageslug}-text-username"}</th><th>{message name="{$pageslug}-text-usermail"}</th>{if $allowEdit == "true"}<th>{message name="{$pageslug}-text-edituser"}</th>{/if}{if $allowDelete == "true"}<th>{message name="{$pageslug}-text-deleteuser"}</th>{/if}</tr>
			</thead>
			<tbody>
				{foreach from="$userlist" item="user" key="userid" }
				<tr>
					<th>{$user->getFullName()|escape}</th>
					<td>{$user->getUsername()|escape}</td>
					<td>{$user->getEmail()|escape}</td>
					{if $allowEdit == "true"}<td><a href="{$cScriptPath}/{$pageslug}/edit/{$userid}" class="btn btn-small btn-warning">{message name="{$pageslug}-button-edit"}</a></td>{/if}
					{if $allowDelete == "true"}<td>{if $user->isGod()}{else}<a href="{if $group->canDelete()}{$cScriptPath}{$cScriptPath}/{$pageslug}/delete/{$userid}{else}#{/if}" class="btn btn-small btn-danger {if !$group->canDelete()}disabled{/if}">{message name="{$pageslug}-button-delete"}</a>{/if}</td>{/if}</tr>
				{/foreach}
			</tbody>
		</table>
{/block}
{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
{if $allowEdit == "true"}
	<p><a href="{$cScriptPath}/{$pageslug}/profilereview" class="btn btn-warning">{message name="{$pageslug}-button-profilereview"}</a></p>
{/if}

<form class="form-inline" method="post">
	{foreach from="$grouplist" item="g" key="id"}
		<label class="checkbox"><input type="checkbox" name="showgroup-{$id}" {if $g.selected}checked="checked"{/if}/>{$g.name|escape}</label>
	{/foreach}
	<label><input type="checkbox" name="shownogroup" {if $showNoGroup}checked="checked"{/if}/>(not in a group)</label>
	<button type="submit" class="btn btn-small btn-primary"><i class="icon-white icon-search"></i>&nbsp;Search</button>
</form>

<table class="table table-hover">
			<thead>
				<tr><th>{message name="{$pageslug}-text-realname"}</th><th>{message name="{$pageslug}-text-username"}</th><th>{message name="{$pageslug}-text-usermail"}</th>{if $allowEdit == "true"}<th>{message name="{$pageslug}-text-edituser"}</th>{/if}{if $allowDelete == "true"}<th>{message name="{$pageslug}-text-deleteuser"}</th>{/if}</tr>
			</thead>
			<tbody>
				{foreach from="$userlist" item="user" key="userid" }
				<tr>
					<th>{include file="userdisplay.tpl"}</th>
					<td>{$user->getUsername()|escape}</td>
					<td>{$user->getEmail()|escape}</td>
					{if $allowEdit == "true"}<td><a href="{$cScriptPath}/{$pageslug}/edit/{$userid}" class="btn btn-small btn-warning">{message name="{$pageslug}-button-edit"}</a></td>{/if}
					{if $allowDelete == "true"}<td>{if $user->isGod()}{else}<a href="{if $user->canDelete()}{$cScriptPath}/{$pageslug}/delete/{$userid}{else}#{/if}" class="btn btn-small btn-danger {if !$user->canDelete()}disabled{/if}">{message name="{$pageslug}-button-delete"}</a>{/if}</td>{/if}</tr>
				{/foreach}
			</tbody>
		</table>
{/block}
{extends file="base.tpl"}
{block name="body"}
	<p><a href="{$cScriptPath}/ManageGroups/create" class="btn btn-success">{message name="ManageGroups-button-creategroup"}</a></p>
	<p>
	{if $grouplist|@count gt 0}
		<table class="table table-hover">
			<thead>
				<tr><th>{message name="ManageGroups-text-groupname"}</th><th>{message name="ManageGroups-text-editgroup"}</th><th>{message name="ManageGroups-text-deletegroup"}</th></tr>
			</thead>
			<tbody>
				{foreach from="$grouplist" item="group" key="groupid" }
				<tr><th>{$group->getName()|escape}</th><td><a href="{$cScriptPath}/ManageGroups/edit/{$groupid}" class="btn btn-small btn-warning">{message name="ManageGroups-button-editgroup"}</a></td><td><a href="{$cScriptPath}/ManageGroups/delete/{$groupid}" class="btn btn-small btn-danger">{message name="ManageGroups-button-deletegroup"}</a></td></tr>
				{/foreach}
			</tbody>
		</table>
	{else}
		<div class="alert">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>{message name="warning"}</strong> {message name="ManageGroups-error-nogroups"}
		</div>
	{/if}
	</p>
{/block}
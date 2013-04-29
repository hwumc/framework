{extends file="base.tpl"}
{block name="body"}
{if $allowCreate == "true"}
	<p><a href="{$cScriptPath}/ManageGroups/create" class="btn btn-success">{message name="ManageGroups-button-creategroup"}</a></p>
{/if}
	<p>
		<table class="table table-hover">
			<thead>
				<tr><th>{message name="ManageGroups-text-groupname"}</th><th>{message name="ManageGroups-text-editgroup"}</th>{if $allowDelete == "true"}<th>{message name="ManageGroups-text-deletegroup"}</th>{/if}</tr>
			</thead>
			<tbody>
				{foreach from="$grouplist" item="group" key="groupid" }
				<tr><th>{$group->getName()|escape}</th><td><a href="{$cScriptPath}/ManageGroups/edit/{$groupid}" class="btn btn-small {if $group->isManager($currentUser)}btn-warning{/if}">{if $group->isManager($currentUser)}{message name="ManageGroups-button-editgroup"}{else}{message name="ManageGroups-button-viewgroup"}{/if}</a></td>{if $allowDelete == "true"}<td><a href="{$cScriptPath}/ManageGroups/delete/{$groupid}" class="btn btn-small btn-danger">{message name="ManageGroups-button-deletegroup"}</a></td>{/if}</tr>
				{/foreach}
			</tbody>
		</table>
	</p>
{/block}
{if $allowDelete == "true"}{/if}
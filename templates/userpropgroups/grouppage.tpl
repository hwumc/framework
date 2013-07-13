{extends file="base.tpl"}
{block name="body"}
	<p><a href="{$cScriptPath}/ManageUserPropGroups/create" class="btn btn-success">{message name="ManageUserPropGroups-button-creategroup"}</a></p>
	<p>
		<table class="table table-hover">
			<thead>
				<tr><th>{message name="ManageUserPropGroups-text-groupname"}</th><th>{message name="ManageUserPropGroups-text-deletegroup"}</th></tr>
			</thead>
			<tbody>
				{foreach from="$grouplist" item="group" key="groupid" }
				<tr><th>{message name="userpropgroup-{$group->getName()}-description"}</th><td><a href="{$cScriptPath}/ManageUserPropGroups/delete/{$groupid}" class="btn btn-small btn-danger">{message name="ManageUserPropGroups-button-deletegroup"}</a></td></tr>
				{/foreach}
			</tbody>
		</table>
	</p>
{/block}
{if $allowDelete == "true"}{/if}
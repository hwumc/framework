{extends file="base.tpl"}
{block name="body"}
{if $allowCreate == "true"}
	<p><a href="{$cScriptPath}/{$pageslug}/create" class="btn btn-success">{message name="{$pageslug}-button-creategroup"}</a></p>
{/if}
	<p>
		<table class="table table-hover">
			<thead>
				<tr><th>{message name="{$pageslug}-text-groupname"}</th><th>{message name="{$pageslug}-text-editgroup"}</th>{if $allowDelete == "true"}<th>{message name="{$pageslug}-text-deletegroup"}</th>{/if}</tr>
			</thead>
			<tbody>
				{foreach from="$grouplist" item="group" key="groupid" }
					<tr>
						<th>{$group->getName()|escape}</th>
						<td><a href="{$cScriptPath}/{$pageslug}/edit/{$groupid}" class="btn btn-small {if $group->isManager($currentUser)}btn-warning{/if}">{if $group->isManager($currentUser)}{message name="{$pageslug}-button-editgroup"}{else}{message name="{$pageslug}-button-viewgroup"}{/if}</a></td>
						{if $allowDelete == "true"}<td><a href="{$cScriptPath}/{$pageslug}/delete/{$groupid}" class="btn btn-small btn-danger">{message name="{$pageslug}-button-deletegroup"}</a></td>{/if}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</p>
{/block}
{if $allowDelete == "true"}{/if}
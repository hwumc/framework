{extends file="base.tpl"}
{block name="body"}
{if $allowCreate == "true"}
	<p><a href="{$cScriptPath}/{$pageslug}/create" class="btn btn-success">{message name="{$pageslug}-button-create"}</a></p>
{/if}
	<p>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>{message name="{$pageslug}-text-slug"}</th>
					<th>{message name="{$pageslug}-text-displayname"}</th>
					<th>{message name="{$pageslug}-text-edit"}</th>
					{if $allowDelete == "true"}<th>{message name="{$pageslug}-text-delete"}</th>{/if}
				</tr>
			</thead>
			<tbody>
				{foreach from="$grouplist" item="group" key="groupid" }
					<tr>
						<th>{$group->getSlug()|escape}</th>
						<th>{$group->getDisplayName()|escape}</th>
						<td><a href="{$cScriptPath}/{$pageslug}/edit/{$groupid}" class="btn btn-small btn-warning">{message name="{$pageslug}-button-edit"}</a></td>
						{if $allowDelete == "true"}<td><a href="{$cScriptPath}/{$pageslug}/delete/{$groupid}" class="btn btn-small btn-danger">{message name="{$pageslug}-button-delete"}</a></td>{/if}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</p>
{/block}
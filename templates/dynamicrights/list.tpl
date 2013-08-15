{extends file="base.tpl"}
{block name="body"}
{if $allowCreate == "true"}
	<p><a href="{$cScriptPath}/{$pageslug}/create" class="btn btn-success">{message name="{$pageslug}-button-create"}</a></p>
{/if}
	<p>
		<table class="table table-hover">
			<thead>
				<tr><th>{message name="{$pageslug}-text-name"}</th>{if $allowDelete == "true"}<th>{message name="{$pageslug}-text-delete"}</th>{/if}</tr>
			</thead>
			<tbody>
				{foreach from="$grouplist" item="group" key="groupid" }
				<tr><th>{$group->getRight()|escape}</th>{if $allowDelete == "true"}<td><a href="{$cScriptPath}/{$pageslug}/delete/{$groupid}" class="btn btn-small btn-danger">{message name="{$pageslug}-button-delete"}</a></td>{/if}</tr>
				{/foreach}
			</tbody>
		</table>
	</p>
{/block}
{if $allowDelete == "true"}{/if}
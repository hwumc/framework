{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
{if $allowTruncate == "true"}
	<div class="well">
	<h3>{message name="MessageEditor-clear-header"}</h3>
	<p>{message name="MessageEditor-clear-info"}</p>
	<div class="btn-group"><a href="{$cScriptPath}/MessageEditor/clear" class="btn btn-danger">{message name="MessageEditor-button-clear"}</a><a href="{$cScriptPath}/MessageEditor/rmunset" class="btn btn-warning">{message name="MessageEditor-button-rmunset"}</a></div></div>
{/if}
{include file="messageeditor/pager.tpl"}

{if $showtable == 1}
<form method="post">
<table class="table table-hover">
	<tr>
		{foreach from="$languages" item="lang"}
			<th>
				{$lang}
			</th>
		{/foreach}
		<th />
	</tr>
	{foreach from="$languagetable" item="messagerow"}
		<tr>
			{foreach from="$messagerow" item="message"}
			<td>
				{if $message@key eq 'zxx'}
					{$message.content|escape}
				{else}
					<textarea {$readonly} name="lang{$message.id}msg" class="input-xxlarge" rows="3" xml:space="preserve">{$message.content}</textarea>
				{/if}
			</td>
			{/foreach}
			
			<td>
			{if $allowDelete == "true"}
				<button class="btn btn-danger" type="submit" name="delete-{$messagerow@key}">{message name="{$pageslug}-button-delete"}</button>
			{/if}
			</td>
		</tr>
	{/foreach}
</table>
<button class="btn btn-primary" {$readonly} id="submitbuttonLang" type="submit" >{message name="{$pageslug}-save"}</button>
</form>
{else}
{message name="{$pageslug}-noprefix"}
{/if}
{/block}
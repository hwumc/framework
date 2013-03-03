{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
{if $allowTruncate == "true"}
	<div class="well">
	<h3>{message name="MessageEditor-clear-header"}</h3>
	<p>{message name="MessageEditor-clear-info"}</p>
	<p><a href="{$cScriptPath}/MessageEditor/clear" class="btn btn-danger">{message name="MessageEditor-button-clear"}</a></p></div>
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
	</tr>
	{foreach from="$languagetable" item="messagerow"}
		<tr>
			{foreach from="$messagerow" item="message"}
			<td>
				{if $message@key eq 'zxx'}
					{$message.content}
				{else}
					<textarea {$readonly} name="lang{$message.id}msg" class="input-xxlarge" rows="3" xml:space="preserve">{$message.content}</textarea>
				{/if}
			</td>
			{/foreach}
		</tr>
	{/foreach}
</table>
<button class="btn btn-primary" {$readonly} id="submitbuttonLang" type="submit" >{message name="{$pageslug}-save"}</button>
</form>
{else}
{message name="{$pageslug}-noprefix"}
{/if}
{/block}
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



{include file="messageeditor/pager.tpl"}
{/block}
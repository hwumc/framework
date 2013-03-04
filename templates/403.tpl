{extends file="base.tpl"}
{block name="body"}
	{message name="AccessDenied-message"}
{/block}
{block name="pagedescription"}{/block}
{block name="pageheader"}
	<div class="page-header"><h1>{message name="AccessDenied-header"} <small>{message name="AccessDenied-header-subtext"}</small></h1></div>
{/block}
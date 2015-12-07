{extends file="base.tpl"}
{block name="body"}
{if $allowUpload == "true"}
	<p><a href="{$cScriptPath}/{$pageslug}/upload" class="btn btn-success">{message name="{$pageslug}-button-upload"}</a></p>
{/if}

{include file="filegrid.tpl" includebuttons="true" includecheck="false" includedetails="true" includefilename="true"}
{/block}
{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-create-header"}</legend>

	<div class="control-group">
		<label class="control-label" for="name">{message name="{$pageslug}-create-name"}</label>
		<div class="controls">
			<input type="text" id="name" name="name" placeholder="{message name="{$pageslug}-create-name-placeholder"}" required="true" value="{$name}" {if $allowEdit == "false"}disabled="true" {/if}/>
			<span class="help-inline">{message name="{$pageslug}-create-name-help"}</span>
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<div class="btn-group"><button type="submit" class="btn btn-primary">{message name="save"}</button><a href="{$cScriptPath}/{$pageslug}" class="btn">{message name="getmeoutofhere"}</a></div>
		</div>
	</div>
</form>
{/block}
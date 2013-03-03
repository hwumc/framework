{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-rmunset-header"}</legend>
	
	<p>{message name="{$pageslug}-rmunset-areyousure"}</p>

	<div class="control-group">
		<div class="controls">
			<label class="checkbox">
				<input type="checkbox" name="confirm" value="confirmed" required="true" />
				{message name="{$pageslug}-rmunset-delete"}
			</label>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<div class="btn-group">
				<button type="submit" class="btn btn-warning">{message name="delete"}</button><a href="{$cScriptPath}/{$pageslug}" class="btn">{message name="getmeoutofhere"}</a>
			</div>
		</div>
	</div>
</form>

{/block}
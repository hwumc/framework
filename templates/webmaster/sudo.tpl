{extends file="base.tpl"}
{block name="body"}
<form class="form-horizontal" method="post">
	<fieldset>
		<legend>{message name="{$pageslug}-header-user"}</legend>

		<div class="control-group">
			<label class="control-label" for="username">{message name="{$pageslug}-username-label"}</label>
			<div class="controls">
				<input type="text" id="username" name="username" placeholder="{message name="{$pageslug}-username-placeholder"}" required="true" data-provide="typeahead" data-items="4" data-source='{$jsuserlist}' />
			</div>
		</div>
	</fieldset>

	<div class="form-actions">
		<div class="controls">
			<div class="btn-group"><button type="submit" class="btn btn-primary">{message name="{$pageslug}-save"}</button></div>
		</div>
	</div>
</form>
{/block}
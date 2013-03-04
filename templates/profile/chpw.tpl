{extends file="base.tpl"}
{block name="body"}
<form class="form-horizontal" method="post">

	<fieldset>

		<div class="control-group">
			<label class="control-label" for="old">{message name="{$pageslug}-old-label"}</label>
			<div class="controls">
				<input type="password" id="old" class="input-xlarge" name="old" placeholder="{message name="{$pageslug}-old-placeholder"}" required="true" />
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label" for="new">{message name="{$pageslug}-new-label"}</label>
			<div class="controls">
				<input type="password" id="new" class="input-xlarge" name="new" placeholder="{message name="{$pageslug}-new-placeholder"}" required="true" />
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label" for="confirm">{message name="{$pageslug}-confirm-label"}</label>
			<div class="controls">
				<input type="password" id="confirm" class="input-xlarge" name="confirm" placeholder="{message name="{$pageslug}-confirm-placeholder"}" required="true" />
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
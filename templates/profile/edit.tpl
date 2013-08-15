{extends file="base.tpl"}
{block name="body"}
<form class="form-horizontal" method="post">

	<fieldset>
		<legend>{message name="{$pageslug}-header-user"}</legend>

		<div class="control-group">
			<label class="control-label" for="email">{message name="{$pageslug}-email-label"}</label>
			<div class="controls">
				<input type="email" id="email" class="input-xlarge" name="email" placeholder="{message name="{$pageslug}-email-placeholder"}" required="true" value="{$email}" />
			</div>
		</div>	
	</fieldset>

	<fieldset>
		<legend>{message name="{$pageslug}-header-you"}</legend>

		<div class="control-group">
			<label class="control-label" for="realname">{message name="{$pageslug}-realname-label"}</label>
			<div class="controls">
				<input type="text" id="realname" class="input-xlarge" name="realname" placeholder="{message name="{$pageslug}-realname-placeholder"}" required="true" value="{$realname}" />
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
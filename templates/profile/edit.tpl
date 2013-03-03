{extends file="base.tpl"}
{block name="body"}
<form class="form-horizontal" method="post">

	<fieldset>
		<legend>{message name="{$pageslug}-header-user"}</legend>

		<div class="control-group">
			<label class="control-label" for="email">{message name="{$pageslug}-email-label"}</label>
			<div class="controls">
				<input type="email" id="email" class="input-xlarge" name="email" placeholder="{message name="{$pageslug}-email-placeholder"}" required="true" />
			</div>
		</div>	
	</fieldset>

	<fieldset>
		<legend>{message name="{$pageslug}-header-you"}</legend>

		<div class="control-group">
			<label class="control-label" for="realname">{message name="{$pageslug}-realname-label"}</label>
			<div class="controls">
				<input type="text" id="realname" class="input-xlarge" name="realname" placeholder="{message name="{$pageslug}-realname-placeholder"}" required="true" />
			</div>
		</div>		
		
		<div class="control-group">
			<label class="control-label" for="mobile">{message name="{$pageslug}-mobile-label"}</label>
			<div class="controls">
				<input type="text" id="mobile" class="input-medium" name="mobile" placeholder="{message name="{$pageslug}-mobile-placeholder"}" required="true" />
			</div>
		</div>		
		
		<div class="control-group">
			<label class="control-label" for="experience">{message name="{$pageslug}-experience-label"}</label>
			<div class="controls">
				<textarea rows="3" class="input-xxlarge" id="experience" name="experience" placeholder="{message name="{$pageslug}-experience-placeholder"}" ></textarea>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="medical">{message name="{$pageslug}-medical-label"}</label>
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" name="medicalcheck" />
					{message name="{$pageslug}-medicalcheck-label"}
				</label>
				<textarea class="input-xxlarge" rows="3" id="medical" name="medical" placeholder="{message name="{$pageslug}-medical-placeholder"}"></textarea>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>{message name="{$pageslug}-header-emerg"}</legend>
		
		<div class="control-group">
			<label class="control-label" for="contactname">{message name="{$pageslug}-contactname-label"}</label>
			<div class="controls">
				<input type="text" id="contactname" class="input-xlarge" name="contactname" placeholder="{message name="{$pageslug}-contactname-placeholder"}" />
			</div>
		</div>		
		<div class="control-group">
			<label class="control-label" for="contactphone">{message name="{$pageslug}-contactphone-label"}</label>
			<div class="controls">
				<input type="text" id="contactphone" class="input-medium" name="contactphone" placeholder="{message name="{$pageslug}-contactphone-placeholder"}" />
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
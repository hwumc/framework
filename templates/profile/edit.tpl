{extends file="base.tpl"}
{block name="body"}

<script src="{$cWebPath}/scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="{$cWebPath}/scripts/bootstrap-datepicker.js" type="text/javascript"></script>

{if $review != ""}
	<div class="alert alert-block alert-info">
	  <h4>{message name="{$pageslug}-review-header"}</h4>
	  {message name="{$pageslug}-review-info"}
	</div>
{/if}
<form class="form-horizontal" method="post">

	<fieldset>
		<legend>{message name="{$pageslug}-header-user"}</legend>

		<div class="control-group">
			<label class="control-label" for="email">{message name="{$pageslug}-email-label"}</label>
			<div class="controls">
				<input type="email" id="email" class="input-xlarge" name="email" placeholder="{message name="{$pageslug}-email-placeholder"}" required="true" value="{$email}" />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="email">{message name="{$pageslug}-avatar-label"}</label>
			<div class="controls">
				<img src="https://secure.gravatar.com/avatar/{$gravatar}?s=100&default=identicon&r=pg" />
				<span class="help-block">{message name="{$pageslug}-avatar-help"}</span>
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
		
		<div class="control-group">
			<label class="control-label" for="mobile">{message name="{$pageslug}-mobile-label"}</label>
			<div class="controls">
				<input type="text" id="mobile" class="input-medium" name="mobile" placeholder="{message name="{$pageslug}-mobile-placeholder"}" required="true" value="{$mobile}" />
			</div>
		</div>		
		
		<div class="control-group">
			<label class="control-label" for="experience">{message name="{$pageslug}-experience-label"}</label>
			<div class="controls">
				<textarea rows="3" class="input-xxlarge" id="experience" name="experience" placeholder="{message name="{$pageslug}-experience-placeholder"}" required="true" >{$experience}</textarea>
			</div>
		</div>		
	</fieldset>

	<fieldset>
		<legend>{message name="{$pageslug}-header-driving"}</legend>
		
		<div class="control-group">
			<div class="controls">
				<label class="checkbox" for="isdriver">
					<input type="checkbox" id="isdriver" name="isdriver" {if $isdriver}checked="true"{/if} /> {message name="{$pageslug}-isdriver"}
				</label>
			</div>
		</div>
		
		<div class="control-group" id="driverExpiryGroup" {if !$isdriver}style="display:none"{/if}>
			<label class="control-label" for="driverexpiry">{message name="{$pageslug}-driverexpiry"}</label>
			<div class="controls">
				<input class="input-medium" type="text" id="driverexpiry" placeholder="{message name="{$pageslug}-driverexpiry-placeholder"}" data-date-format="dd/mm/yyyy" name="driverexpiry" value="{$driverexpiry}"/>
				<span class="help-inline">{message name="{$pageslug}-driverexpiry-help"}</span>
			</div>
		</div>	
	</fieldset>

	<fieldset>
		<legend>{message name="{$pageslug}-header-emerg"}</legend>
		
		<div class="control-group">
			<label class="control-label" for="medical">{message name="{$pageslug}-medical-label"}</label>
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" name="medicalcheck" {$medicalcheck}/>
					{message name="{$pageslug}-medicalcheck-label"}
				</label>
				<textarea class="input-xxlarge" rows="3" id="medical" name="medical" placeholder="{message name="{$pageslug}-medical-placeholder"}">{$medical}</textarea>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="contactname">{message name="{$pageslug}-contactname-label"}</label>
			<div class="controls">
				<input type="text" id="contactname" class="input-xlarge" name="contactname" placeholder="{message name="{$pageslug}-contactname-placeholder"}" value="{$contactname}" required="true" />
			</div>
		</div>		
		<div class="control-group">
			<label class="control-label" for="contactphone">{message name="{$pageslug}-contactphone-label"}</label>
			<div class="controls">
				<input type="text" id="contactphone" class="input-medium" name="contactphone" placeholder="{message name="{$pageslug}-contactphone-placeholder"}" value="{$contactphone}" required="true" />
			</div>
		</div>
	</fieldset>


	<div class="form-actions">
		<div class="controls">
			<div class="btn-group"><button type="submit" class="btn btn-primary">{message name="{$pageslug}-save"}</button></div>
		</div>
	</div>
</form>
<script>
$(function(){
	window.prettyPrint && prettyPrint();
	$('#driverexpiry').datepicker();
});

$('#isdriver').click(function() {
    if( $(this).is(':checked')) {
        $("#driverExpiryGroup").show(400, function(){});
    } else {
        $("#driverExpiryGroup").hide(400, function(){});
    }
}); 
</script>
{/block}
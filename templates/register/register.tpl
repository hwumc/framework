{extends file="base.tpl"}
{block name="body"}
<form class="form-horizontal" method="post">
	<div class="container-fluid">
		<div class="row">
			<div class="span6">
				<fieldset>
					<legend>{message name="{$pageslug}-header-user"}</legend>

					<div class="control-group">
						<label class="control-label" for="username">{message name="{$pageslug}-username-label"}</label>
						<div class="controls">
							<input type="text" id="username" name="username" placeholder="{message name="{$pageslug}-username-placeholder"}" required="true" value="{$regusername}"/>
						</div>
					</div>		
				
					<div class="control-group">
						<label class="control-label" for="password">{message name="{$pageslug}-password-label"}</label>
						<div class="controls">
							<input type="password" id="password" name="password" placeholder="{message name="{$pageslug}-password-placeholder"}" required="true" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="confirmpassword">{message name="{$pageslug}-confirmpassword-label"}</label>
						<div class="controls">
							<input type="password" id="confirmpassword" name="confirmpassword" placeholder="{message name="{$pageslug}-confirmpassword-placeholder"}" required="true" />
						</div>
					</div>	
					<div class="control-group">
						<label class="control-label" for="email">{message name="{$pageslug}-email-label"}</label>
						<div class="controls">
							<input type="email" id="email" name="email" placeholder="{message name="{$pageslug}-email-placeholder"}" required="true" value="{$regemail}" />
						</div>
					</div>	
				</fieldset>
			</div>
			<div class="span6">
				<fieldset>
					<legend>{message name="{$pageslug}-header-you"}</legend>

					<div class="control-group">
						<label class="control-label" for="realname">{message name="{$pageslug}-realname-label"}</label>
						<div class="controls">
							<input type="text" id="realname" name="realname" placeholder="{message name="{$pageslug}-realname-placeholder"}" required="true" value="{$regrealname}"/>
						</div>
					</div>		
				
					<div class="control-group">
						<label class="control-label" for="mobile">{message name="{$pageslug}-mobile-label"}</label>
						<div class="controls">
							<input type="text" id="mobile" name="mobile" placeholder="{message name="{$pageslug}-mobile-placeholder"}" required="true" value="{$regmobile}"/>
						</div>
					</div>

				
					<div class="control-group">
						<label class="control-label" for="experience">{message name="{$pageslug}-experience-label"}</label>
						<div class="controls">
							<textarea rows="3" id="experience" name="experience" placeholder="{message name="{$pageslug}-experience-placeholder"}" required="true">{$regexperience}</textarea>
						</div>
					</div>
		
				
				</fieldset>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<fieldset>
					<legend>{message name="{$pageslug}-header-emerg"}</legend>
					<div class="control-group">
						<label class="control-label" for="medical">{message name="{$pageslug}-medical-label"}</label>
						<div class="controls">
							<label class="checkbox">
								<input type="checkbox" name="medicalcheck" value="{$regmedicalcheck}"/>
								{message name="{$pageslug}-medicalcheck-label"}
							</label>
							<textarea rows="3" class="input-xxlarge" id="medical" name="medical" placeholder="{message name="{$pageslug}-medical-placeholder"}">{$regmedical}</textarea>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="contactname">{message name="{$pageslug}-contactname-label"}</label>
						<div class="controls">
							<input type="text" id="contactname" class="input-xlarge" name="contactname" placeholder="{message name="{$pageslug}-contactname-placeholder"}" required="true" value="{$regcontactname}"/>
						</div>
					</div>		
					<div class="control-group">
						<label class="control-label" for="contactphone">{message name="{$pageslug}-contactphone-label"}</label>
						<div class="controls">
							<input type="text" id="contactphone" class="input-medium" name="contactphone" placeholder="{message name="{$pageslug}-contactphone-placeholder"}" required="true" value="{$regcontactphone}"/>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<div class="form-actions">
					<div class="controls">
						<div class="btn-group"><button type="submit" class="btn btn-primary">{message name="{$pageslug}-save"}</button></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
{/block}
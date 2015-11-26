{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<fieldset>
		<legend>{message name="{$pageslug}-edit-userheader"}</legend>
	
		<div class="control-group">
			<label class="control-label" for="username">{message name="{$pageslug}-edit-username"}</label>
			<div class="controls">
				<input type="text" id="username" class="input-medium" name="username" placeholder="{message name="{$pageslug}-edit-username-placeholder"}" required="true" value="{$user->getUsername()}" />
			</div>
		</div>		
		<div class="control-group">
			<label class="control-label" for="realname">{message name="{$pageslug}-edit-realname"}</label>
			<div class="controls">
				<input type="text" id="realname" class="input-xlarge" name="realname" placeholder="{message name="{$pageslug}-edit-realname-placeholder"}" required="true" value="{$user->getFullName()}" />
			</div>
		</div>		
		<div class="control-group">
			<label class="control-label" for="email">{message name="{$pageslug}-edit-email"}</label>
			<div class="controls">
				<input type="email" id="email" class="input-xlarge" name="email" placeholder="{message name="{$pageslug}-edit-email-placeholder"}" required="true" value="{$user->getEmail()}" />
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label" for="profilereview">{message name="{$pageslug}-edit-profilereview"}</label>
			<div class="controls">
				<input type="checkbox" id="profilereview" class="input-xlarge" name="profilereview" {if $user->getProfileReview()}checked="checked"{/if} />
			</div>
		</div>	
		<div class="control-group">
			<label class="control-label" for="passwordreset">{message name="{$pageslug}-edit-passwordreset"}</label>
			<div class="controls">
				<input type="checkbox" id="passwordreset" class="input-xlarge" name="passwordreset" {if $user->getPasswordReset()}checked="checked"{/if} />
			</div>
		</div>	
	
	</fieldset>
	
	<fieldset>
		<legend>{message name="{$pageslug}-edit-groupsheader"}</legend>
		<div class="control-group">
			<div class="controls">
				{if $user->isGod() }
					<label class="checkbox">
						<input type="checkbox" checked="true" disabled="true"/>
						<strong>{message name="GodMode"}</strong>: {message name="GodMode-description"}
					</label>
					<hr />
				{/if}
				{foreach from="$grouplist" key="id" item="group"}
					<label class="checkbox">
						<input type="checkbox" name="group-{$id}" {if $group.assigned == "true"}checked="true" {/if} {if $group.editable == "false" }disabled="true"{/if}  />
						<strong>{$group.name|escape}</strong>: {$group.description|escape}
					</label>
					{if $group.editable == "false" && $group.assigned == "true"}
						{* Send a value for this group anyway. This is protected on the server too, but it throws a nasty error which probably doesn't signal the user's intent. *}
						<input type="hidden" name="group-{$id}" value="on" />
					{/if}
				{/foreach}
			</div>
		</div>
	</fieldset>
	
	<div class="control-group">
		<div class="controls">
			<div class="btn-group">
				<button type="submit" class="btn btn-primary">{message name="save"}</button>
				<a href="{$cScriptPath}/{$pageslug}" class="btn">{message name="getmeoutofhere"}</a>
			</div>
		</div>
	</div>
</form>
{/block}
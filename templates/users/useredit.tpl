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
				<input type="text" id="email" class="input-xlarge" name="email" placeholder="{message name="{$pageslug}-edit-email-placeholder"}" required="true" value="{$user->getEmail()}" />
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
				{else}
					{foreach from="$grouplist" key="id" item="group"}
						<label class="checkbox">
							<input type="checkbox" name="group-{$id}" {if $group.assigned == "true"}checked="true" {/if} />
							<strong>{$group.name|escape}</strong>: {$group.description|escape}
						</label>
					{/foreach}
				{/if}
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
{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-create-header"}</legend>

	<div class="control-group">
		<label class="control-label" for="groupname">{message name="{$pageslug}-create-groupname"}</label>
		<div class="controls">
			<input type="text" id="groupname" name="groupname" placeholder="{message name="{$pageslug}-create-groupname-placeholder"}" required="true" value="{$groupname}" {if $allowEdit == "false"}disabled="true" {/if}/>
			<span class="help-inline">{message name="{$pageslug}-create-groupname-help"}</span>
		</div>
	</div>	
	<div class="control-group">
		<label class="control-label" for="description">{message name="{$pageslug}-create-description"}</label>
		<div class="controls">
			<input class="input-xxlarge" type="text" id="description" name="description" placeholder="{message name="{$pageslug}-create-description-placeholder"}" required="true" value="{$description}" {if $allowEdit == "false"}disabled="true" {/if}/>
			<span class="help-inline">{message name="{$pageslug}-create-description-help"}</span>
		</div>
	</div>	
	<div class="control-group">
		<label class="control-label" for="parent">{message name="{$pageslug}-create-parent"}</label>
		<div class="controls">
			<input type="text" id="parent" name="parent" placeholder="{message name="{$pageslug}-create-parent-placeholder"}" value="{$parent}" {if $allowEdit == "false" || $lockparent == "true" }disabled="true" {/if} data-provide="typeahead" data-items="4" data-source='{$jsgrouplist}'  />
			<span class="help-inline">{message name="{$pageslug}-create-parent-help"}</span>
		</div>
	</div>
	
	<fieldset>
		<legend>{message name="{$pageslug}-create-rightsheader"}</legend>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" value="" checked="true" disabled="true" />
					<tt>public</tt>: {message name="accessright-public"}
				</label>				
				<label class="checkbox">
					<input type="checkbox" value="" checked="true" disabled="true" />
					<tt>user</tt>: {message name="accessright-user"}
				</label>
				
				{foreach from="$rightslist" key="rightname" item="check"}
					<label class="checkbox">
						<input type="checkbox" name="right-{$rightname}" {if $check == "true"}checked="true" {/if} {if $allowEdit == "false"}disabled="true" {/if}/>
						<tt>{$rightname}</tt>: {message name="accessright-{$rightname}"}
					</label>
				{/foreach}
			

			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend>{message name="{$pageslug}-create-usersheader"}</legend>
		<div class="control-group">
			<div class="controls">				
				{foreach from="$userslist" key="username" item="check"}
					<label class="checkbox">
						<input type="checkbox" name="user-{$username}" {if $check == "true"}checked="true" {/if} disabled="true"/>
						<tt>{$username}</tt>
					</label>
				{/foreach}
			</div>
		</div>
	</fieldset>
	<div class="control-group">
		<div class="controls">
			<div class="btn-group">{if $allowEdit == "true"}<button type="submit" class="btn btn-primary">{message name="save"}</button>{/if}<a href="{$cScriptPath}/ManageGroups" class="btn">{message name="getmeoutofhere"}</a></div>
		</div>
	</div>
</form>
{/block}
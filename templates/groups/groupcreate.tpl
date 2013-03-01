{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-create-header"}</legend>

	<div class="control-group">
		<label class="control-label" for="groupname">{message name="{$pageslug}-create-groupname"}</label>
		<div class="controls">
			<input type="text" id="groupname" name="groupname" placeholder="{message name="{$pageslug}-create-groupname-placeholder"}" required="true" value="{$groupname}" />
		</div>
	</div>	
	<div class="control-group">
		<label class="control-label" for="description">{message name="{$pageslug}-create-description"}</label>
		<div class="controls">
			<input class="input-xxlarge" type="text" id="description" name="description" placeholder="{message name="{$pageslug}-create-description-placeholder"}" required="true" value="{$description}" />
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
						<input type="checkbox" name="right-{$rightname}" {if $check == "true"}checked="true" {/if} />
						<tt>{$rightname}</tt>: {message name="accessright-{$rightname}"}
					</label>
				{/foreach}
			

			</div>
		</div>
	</fieldset>
	<div class="control-group">
		<div class="controls">
			<div class="btn-group"><button type="submit" class="btn btn-primary">{message name="save"}</button><a href="{$cScriptPath}/ManageGroups" class="btn">{message name="getmeoutofhere"}</a></div>
		</div>
	</div>
</form>
{/block}
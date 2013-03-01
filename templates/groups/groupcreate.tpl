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
	
	<fieldset>
		<legend>{message name="{$pageslug}-create-rightsheader"}</legend>
		<div class="control-group">
			<div class="controls">
				<label class="checkbox">
					<input type="checkbox" value="" checked="true" disabled="true" />
					<tt>public</tt>: {message name="accessright-public"}
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
			<button type="submit" class="btn btn-primary">{message name="save"}</button>
		</div>
	</div>
</form>
{/block}
{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal">
	<legend>{message name="{$pageslug}-create-header"}</legend>

	<div class="control-group">
		<label class="control-label" for="groupname">{message name="{$pageslug}-create-groupname"}</label>
		<div class="controls">
			<input type="text" id="groupname" placeholder="{message name="{$pageslug}-create-groupname-placeholder"}">
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
				
				{foreach from="$rightslist" item="rightname"}
					<label class="checkbox">
						<input type="checkbox" value="" />
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
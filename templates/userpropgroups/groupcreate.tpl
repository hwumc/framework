{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-create-header"}</legend>

	<div class="control-group">
		<label class="control-label" for="groupname">{message name="{$pageslug}-create-groupname"}</label>
		<div class="controls">
			<input type="text" id="groupname" name="groupname" placeholder="{message name="{$pageslug}-create-groupname-placeholder"}" required="true"/>
			<span class="help-inline">{message name="{$pageslug}-create-groupname-help"}</span>
		</div>
	</div>	
	<div class="control-group">
		<label class="control-label" for="groupdesc">{message name="{$pageslug}-create-groupdesc"}</label>
		<div class="controls">
			<input type="text" id="groupdesc" name="groupdesc" placeholder="{message name="{$pageslug}-create-groupdesc-placeholder"}" required="true"/>
			<span class="help-inline">{message name="{$pageslug}-create-groupdesc-help"}</span>
		</div>
	</div>	
	
	<div class="control-group">
		<div class="controls">
			<div class="btn-group"><button type="submit" class="btn btn-primary">{message name="save"}</button><a href="{$cScriptPath}/ManageUserPropGroups" class="btn">{message name="getmeoutofhere"}</a></div>
		</div>
	</div>
</form>
{/block}
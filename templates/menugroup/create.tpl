{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-create-header"}</legend>

	<div class="control-group">
		<label class="control-label" for="slug">{message name="{$pageslug}-create-slug"}</label>
		<div class="controls">
			<input type="text" id="slug" name="slug" placeholder="{message name="{$pageslug}-create-slug-placeholder"}" required="true" value="{$slug}" {if $allowEdit == "false"}disabled="true" {/if}/>
			<span class="help-inline">{message name="{$pageslug}-create-slug-help"}</span>
		</div>
	</div>	

	<div class="control-group">
		<label class="control-label" for="displayname">{message name="{$pageslug}-create-displayname"}</label>
		<div class="controls">
			<input class="input-xxlarge" type="text" id="displayname" name="displayname" placeholder="{message name="{$pageslug}-create-displayname-placeholder"}" required="true" value="{$displayname}" {if $allowEdit == "false"}disabled="true" {/if}/>
			<span class="help-inline">{message name="{$pageslug}-create-displayname-help"}</span>
		</div>
	</div>	

	<div class="control-group">
		<div class="controls">
			<label for="issecondary" class="checkbox">
				<input type="checkbox" id="issecondary" name="issecondary" {$issecondary} {if $allowEdit =="false" }disabled="true" {/if}/> {message name="{$pageslug}-create-issecondary"}
			</label>
		</div>
	</div>	

	<div class="control-group">
		<label class="control-label" for="priority">{message name="{$pageslug}-create-priority"}</label>
		<div class="controls">
			<input class="input-small" type="text" id="priority" name="priority" placeholder="{message name="{$pageslug}-create-priority-placeholder"}" required="true" value="{$priority}" {if $allowEdit =="false" }disabled="true" {/if}/>
			<span class="help-inline">{message name="{$pageslug}-create-priority-help"}</span>
		</div>
	</div>	

	<div class="control-group">
		<div class="controls">
			<div class="btn-group">{if $allowEdit == "true"}<button type="submit" class="btn btn-primary">{message name="save"}</button>{/if}<a href="{$cScriptPath}/{$pageslug}" class="btn">{message name="getmeoutofhere"}</a></div>
		</div>
	</div>
</form>
{/block}
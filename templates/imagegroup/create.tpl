{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
    <fieldset>
        <legend>{message name="{$pageslug}-create-header"}</legend>

        <div class="control-group">
            <label class="control-label" for="name">{message name="{$pageslug}-create-name"}</label>
            <div class="controls">
                <input class="input-xxlarge" type="text" id="name" name="name" placeholder="{message name=" {$pageslug}-create-name-placeholder"}" required="true" value="{$name}" {if $allowEdit=="false" }disabled="true" {/if}/>
                <span class="help-inline">{message name="{$pageslug}-create-name-help"}</span>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>{message name="{$pageslug}-create-files-header"}</legend>
        {include file="filegrid.tpl" includebuttons="false" includecheck="true" includedetails="false" filelist=$imagelist fileselections=$imageselections}
    </fieldset>

    <div class="control-group">
        <div class="controls">
            <div class="btn-group">{if $allowEdit == "true"}<button type="submit" class="btn btn-primary">{message name="save"}</button>{/if}<a href="{$cScriptPath}/{$pageslug}" class="btn">{message name="getmeoutofhere"}</a>
            </div>
        </div>
    </div>
</form>
{/block}
{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
<form class="form-horizontal" method="post">
	<legend>{message name="{$pageslug}-delete-header"}</legend>
	
	<p>{message name="{$pageslug}-delete-areyousure"}</p>

    <div class="row-fluid">
        <div class="span4 offset4 thumbnail">
            <img src="{$file->getDownloadPath()}" alt="">
            <h4>{$file->getName()|escape}</h4>
            <table>
                <tr><th>Type</th><td>{$file->getMime()|escape}</td></tr>
                <tr><th>Size</th><td>{$file->getHumanSize()}</td></tr>
            </table>
        </div>
    </div>
    

    <div class="control-group">
		<div class="controls">
			<label class="checkbox">
				<input type="checkbox" name="confirm" value="confirmed" required="true" />
				{message name="{$pageslug}-confirm-delete"}
			</label>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<div class="btn-group">
				<button type="submit" class="btn btn-danger">{message name="delete"}</button><a href="{$cScriptPath}/{$pageslug}" class="btn">{message name="getmeoutofhere"}</a>
			</div>
		</div>
	</div>
</form>

{/block}
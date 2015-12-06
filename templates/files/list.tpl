{extends file="base.tpl"}
{block name="body"}
{if $allowUpload == "true"}
	<p><a href="{$cScriptPath}/{$pageslug}/upload" class="btn btn-success">{message name="{$pageslug}-button-upload"}</a></p>
{/if}

	<p>
        <ul class="thumbnails">
            {foreach from=$filelist item="file" name="thumbloop"}
                {if $smarty.foreach.thumbloop.index % 4 == 0 && $smarty.foreach.thumbloop.index > 1}
        </ul><ul class="thumbnails">
	            {/if}

            <li class="span3">
                <div class="thumbnail">
                    {if $file->isImage()}
                        <img src="{$file->getDownloadPath()}" alt="{$file->getName()|escape}">
                    {else}
                        <img src="{$cWebPath}/img/file-misc.png" alt="{$file->getName()|escape}">
                    {/if}
                    <div class="btn-group pull-right">
                        <a href="{$cScriptPath}/{$pageslug}/delete/{$file->getId()}" class="btn btn-danger btn-small"><i class="icon-white icon-trash"></i></a>
                        <a href="{$file->getDownloadPath()}" class="btn btn-primary btn-small"><i class="icon-white icon-download"></i></a>
                    </div>
                    <h4>{$file->getName()|escape}</h4>
                    <table>
                        <tr><th>Type</th><td><tt>{$file->getMime()|escape}</tt></td></tr>
                        <tr><th>Size</th><td>{$file->getHumanSize()}</td></tr>
                    </table>
                </div>
            </li>

            {/foreach}
        </ul>
	</p>
{/block}
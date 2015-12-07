<ul class="thumbnails">
    {foreach from=$filelist item="file" name="thumbloop"}
    {if $smarty.foreach.thumbloop.index % 4 == 0 && $smarty.foreach.thumbloop.index > 1}
</ul>
<ul class="thumbnails">
    {/if}

    <li class="span3">
        <div class="thumbnail">
            {if $file->isImage()}
            <img src="{$file->getDownloadPath()}" alt="{$file->getName()|escape}">
            {else}
            <img src="{$cWebPath}/img/file-misc.png" alt="{$file->getName()|escape}">
            {/if}
            {if $includebuttons == "true"}
            <div class="btn-group pull-right">
                <a href="{$cScriptPath}/{$pageslug}/delete/{$file->getId()}" class="btn btn-danger btn-small"><i class="icon-white icon-trash"></i></a>
                <a href="{$file->getDownloadPath()}" class="btn btn-primary btn-small"><i class="icon-white icon-download"></i></a>
            </div>
            {/if}
            {if $includecheck == "true"}
                <h4><label for="file{$file->getId()}" class="fileheader"><input type="checkbox" name="file{$file->getId()}" id="file{$file->getId()}" {if $fileselections[$file->getId()]}checked="checked"{/if}/> {$file->getName()|escape}</label></h4>
            {else}
                {if $includefilename == "true"}
                    <h4>{$file->getName()|escape}</h4>
                {/if}
            {/if}
            {if $includedetails == "true"}
            <table>
                <tr><th>Type</th><td><tt>{$file->getMime()|escape}</tt></td></tr>
                <tr><th>Size</th><td>{$file->getHumanSize()}</td></tr>
            </table>
            {/if}
        </div>
    </li>

    {/foreach}
</ul>

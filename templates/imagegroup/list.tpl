{extends file="base.tpl"}
{block name="body"}
{if $allowCreate == "true"}
    <p><a href="{$cScriptPath}/{$pageslug}/create" class="btn btn-success">{message name="{$pageslug}-button-create"}</a></p>
{/if}
    <p>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{message name="{$pageslug}-text-name"}</th>
                    <th>{message name="{$pageslug}-text-edit"}</th>
                    {if $allowDelete == "true"}<th>{message name="{$pageslug}-text-delete"}</th>{/if}
                </tr>
            </thead>
            <tbody>
                {foreach from="$grouplist" item="group" key="groupid" }
                    <tr>
                        <th>{$group->getName()|escape}</th>
                        <td><a href="{$cScriptPath}/{$pageslug}/edit/{$groupid}" class="btn btn-small btn-warning">{message name="{$pageslug}-button-edit"}</a></td>
                        {if $allowDelete == "true"}<td><a href="{if $group->canDelete()}{$cScriptPath}/{$pageslug}/delete/{$groupid}{else}#{/if}" class="btn btn-small btn-danger {if !$group->canDelete()}disabled{/if}">{message name="{$pageslug}-button-delete"}</a></td>{/if}
                    </tr>
                    <tr>
                        <td colspan="3">
                            {include file="filegrid.tpl" filelist=$group->getFiles()}
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </p>
{/block}
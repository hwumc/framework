<div class="span3">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
            {foreach from="$mainmenu" item="menuitem" name="mainmenuloop"}
            {if isset($menuitem.items)}{assign "submenu" "{$menuitem.items}"}
            <li class="nav-header">{$menuitem.displayname|escape}{if isset($menuitem.data)}{$menuitem.data|escape}{/if}</li>
            {foreach from="$submenu" item="subitem" }
            <li>
                <a href="{$cScriptPath}{$subitem.link}" {if isset($subitem.current)}class="active" {/if}>
                {if isset($subitem.displayname)}
                {$subitem.displayname|escape}
                {else}
                {message name={$subitem.title}}
                {/if}
                {if isset($subitem.data)}{$subitem.data|escape}{/if}
                </a>
            </li>
            {/foreach}
            {if ! $smarty.foreach.mainmenuloop.last}
            <li class="divider"></li>
            {/if}
            {else}
            <li>
                <a href="{$cScriptPath}{$menuitem.link}" {if isset($menuitem.current)}class="active" {/if}>{$menuitem.displayname|escape}{if isset($menuitem.data)}{$menuitem.data|escape}{/if}</a>
                {/if}
            </li>
            {/foreach}
        </ul>
    </div>
</div><!--/span-->
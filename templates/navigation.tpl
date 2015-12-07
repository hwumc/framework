<a class="brand" href="#">{message name="sitename"}</a>
{if $loggedin neq ""}
    <ul class="nav pull-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">{include file="userdisplay.tpl" user=$currentUser}&nbsp;<b class="caret"></b></a>
            <ul class="dropdown-menu">
                {foreach from=$personalmenu item="section" key="sectionheader"}
                    <li class="nav-header">{message name="personalmenu-{$sectionheader}"}</li>
                    {foreach from=$section item="menuitem"}
                        <li><a href="{$menuitem.link}"><i class="{$menuitem.icon}"></i>&nbsp;{message name="{$menuitem.displayname}"}</a></li>
                    {/foreach}
                    <li class="divider"></li>
                {/foreach}
                <li><a href="{$cScriptPath}/Logout"><i class="icon-off"></i> {message name="logout"} {$currentUser->getUsername()|escape}</a></li>
            </ul>
        </li>
    </ul>
{/if}
<div class="nav-collapse collapse">
    {if $loggedin eq ""}
        <ul class="nav pull-right">
            <li><a href="{$cScriptPath}/Register" class="navbar-link">{message name="register"}</a></li>
            <li><a href="{$cScriptPath}/Login" class="navbar-link">{message name="login"}</a></li>
        </ul>
    {/if}
    {foreach from=$navbarsecondaryitems item="menu" key="menuheader"}
        <ul class="nav pull-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">{message name="navbar-{$menuheader}"}&nbsp;<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    {foreach from=$menu item="section" key="sectionheader" name="dropdownsection"}
                        <li class="nav-header">{message name="navbarmenu-{$sectionheader}"}</li>
                        {foreach from=$section item="menuitem"}
                            <li><a href="{$menuitem.link}"><i class="{$menuitem.icon}"></i>&nbsp;{message name="{$menuitem.displayname}"}</a></li>
                        {/foreach}
                        {if !$smarty.foreach.dropdownsection.last}
                            <li class="divider"></li>
                        {/if}
                    {/foreach}
                </ul>
            </li>
        </ul>
    {/foreach}
    {block name="primarynavbar"}{/block}
</div><!--/.nav-collapse -->
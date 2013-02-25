<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>{block name="pagetitle"}{message name={$pagetitle}}{/block}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- styles -->
	{foreach from="$styles" item="thisstyle"}
		<link rel="stylesheet" type="text/css" href="{$thisstyle}" />
	{/foreach}
	<style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
	
	<!-- scripts -->
	{foreach from="$scripts" item="thisscript"}
		<script src="{$thisscript}" type="text/javascript"></script>
	{/foreach}

</head>
<body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">{message name="sitename"}</a>
          <div class="nav-collapse collapse">
			{if $loggedin eq ""}
				<p class="navbar-text pull-right">
					<a href="{$cScriptPath}/Login" class="navbar-link">Log in</a>
				</p>
			{else}
				<ul class="nav pull-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">{$userfullname} <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="nav-header">Account</li>
							<li><a href="{$cScriptPath}/ChangePassword">Change Password</a></li>
							<li><a href="{$cScriptPath}/EditProfile">Edit Profile</a></li>
							<li class="divider"></li>						
							<li><a href="{$cScriptPath}/Logout">Log out {$loggedin}</a></li>
						</ul>
					</li>
				</ul>
			{/if}
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          	<div class="well sidebar-nav">
		<ul class="nav nav-list">
			{foreach from="$mainmenu" item="menuitem" }
				
				{if isset($menuitem.items)}{assign "submenu" "{$menuitem.items}"}
				<li class="nav-header">{message name={$menuitem.title}}{if isset($menuitem.data)}{$menuitem.data}{/if}</li>
						{foreach from="$submenu" item="subitem" }
							<li><a href="{$cScriptPath}{$subitem.link}" {if isset($subitem.current)}class="active"{/if}>{message name={$subitem.title}}{if isset($subitem.data)}{$subitem.data}{/if}</a></li>
						{/foreach}
						<li class="divider"></li>
				{else}
				<li><a href="{$cScriptPath}{$menuitem.link}" {if isset($menuitem.current)}class="active"{/if}>{message name={$menuitem.title}}{if isset($menuitem.data)}{$menuitem.data}{/if}</a>
				{/if}
				</li>
			{/foreach}
		</ul>
	</div>
        </div><!--/span-->
        <div class="span9">
          
				{if $showError == "yes"}{include file="errorbar.tpl"}{/if}
				{block name="body"}{$content|default:"<p>Nothing to see here!</p>"}{/block}
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>{message name="footer-copyright"}</p>
      </footer>

    </div><!--/.fluid-container-->	
	
</body>
</html>

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
           	{if $loginoverride eq ""}
						<p class="navbar-text pull-right">
              <a href="#" class="navbar-link">Log in</a>
            </p>
		{else}
					<p class="navbar-text pull-right">
              Logged in as <a href="#" class="navbar-link">Username</a> | Go to Members Area
            </p>
	{/if}
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	
		{block name="nav"}{include file="nav.tpl"}{/block}
		<div id="contentwrapper">
			{block name="subnav"}{* For the management system, default empty *}{/block}
			<div id="content">
				{if $showError == "yes"}{include file="errorbar.tpl"}{/if}
				{block name="body"}{$content|default:"<p>Nothing to see here!</p>"}{/block}
			</div>
		</div>
		{block name="footer"}{include file="footer.tpl"}{/block}
	
	
	
	
</body>
</html>

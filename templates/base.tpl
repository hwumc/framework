<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{block name="pagetitle"}{message name={$pagetitle}}{/block}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- styles -->
    <link rel="stylesheet" type="text/css" href="{$cWebPath}/style/bootstrap.min.css" />
    {block name="styleoverride"}
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
    {/block}
    <link rel="stylesheet" type="text/css" href="{$cWebPath}/style/bootstrap-responsive.min.css" />    
    {foreach from="$styles" item="thisstyle"}
        <link rel="stylesheet" type="text/css" href="{$thisstyle}" />
    {/foreach}
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="lib/bootstrap-2.3.1/js/html5shiv.js"></script>
    <![endif]-->

    {block name="head"}
    {/block}
</head>
<body>
{block name="navbar"}
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {include file="navigation.tpl"}
        </div>
    </div>
</div><!-- /.navbar -->
{/block}
{block name="precontent"}{/block}
<div class="container-fluid">
    {block name="maincontent"}
        <div class="row-fluid">
            {block name="sidebar"}{include file="mainsidebar.tpl"}{/block}
            {block name="rowinit"}
            <div class="span9">{/block}
                {include file="sessionerrors.tpl"}
                {block name="pageheader"}
                <div class="page-header">
                    <h1>{message name="{$pageslug}-header"} <small>{message name="{$pageslug}-header-subtext"}</small>
                    </h1>
                </div>
                {/block}
                {block name="pagedescription"}
                <p>{message name="{$pageslug}-description"}</p>{/block}
                {if $showError == "yes"}{include file="errorbar.tpl"}{/if}
                {block name="body"}{$content|default:"
                <p>Nothing to see here!</p>"}{/block}
            </div><!--/span-->
        </div><!--/row-->
    {/block}

    <hr>

    <footer>
        <p>{message name="footer-copyright"}</p>
    </footer>
</div><!--/.fluid-container-->	

<!-- scripts -->
{foreach from="$scripts" item="thisscript"}
    <script src="{$thisscript}" type="text/javascript"></script>
{/foreach}
{block name="scriptfooter"}{/block}
</body>
</html>

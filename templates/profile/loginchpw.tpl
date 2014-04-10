<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{block name="pagetitle"}{message name={$pagetitle}}{/block}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
	<link rel="stylesheet" type="text/css" href="{$cWebPath}/style/bootstrap.min.css" />
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
	  
    </style>
	<link rel="stylesheet" type="text/css" href="{$cWebPath}/style/bootstrap-responsive.min.css" />
	{foreach from="$styles" item="thisstyle"}
		<link rel="stylesheet" type="text/css" href="{$thisstyle}" />
	{/foreach}

	<!-- scripts -->
	{foreach from="$scripts" item="thisscript"}
		<script src="{$thisscript}" type="text/javascript"></script>
	{/foreach}
  </head>

  <body>

    <div class="container">
	{include file="sessionerrors.tpl"}
		<div class="alert alert-block alert-error">
		  <h4>{message name="forced-password-message-header"}</h4>
		  {message name="forced-password-message-message"}
		</div>
      <form class="form-signin" method="post">
        <h2 class="form-signin-heading">{message name="{$pageslug}-header"}</h2>
        <input type="password" id="old" class="input-block-level" name="old" placeholder="{message name="{$pageslug}-old-placeholder"}" required="true" />
		<input type="password" id="new" class="input-block-level" name="new" placeholder="{message name="{$pageslug}-new-placeholder"}" required="true" />
		<input type="password" id="confirm" class="input-block-level" name="confirm" placeholder="{message name="{$pageslug}-confirm-placeholder"}" required="true" />
        <button class="btn btn-large btn-primary" type="submit">{message name="{$pageslug}-save"}</button>
      </form>

    </div> <!-- /container -->
	<!-- scripts -->
	{foreach from="$scripts" item="thisscript"}
		<script src="{$thisscript}" type="text/javascript"></script>
	{/foreach}
  </body>
</html>
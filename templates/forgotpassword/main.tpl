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
	{foreach from="$styles" item="thisstyle"}
		<link rel="stylesheet" type="text/css" href="{$thisstyle}" />
	{/foreach}
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
	  
	  .form-signin .forgotpassword {
		margin-left: 1em;
	  }

    </style>
	
	<!-- scripts -->
	{foreach from="$scripts" item="thisscript"}
		<script src="{$thisscript}" type="text/javascript"></script>
	{/foreach}
  </head>

  <body>

    <div class="container">

      <form class="form-signin" method="post">
        <h2 class="form-signin-heading">{message name="forgotpassword-title"}</h2>
		<label for="lgUser">{message name="forgotpassword-text"}</label>
        <input type="text" name="lgUser" class="input-block-level" placeholder="{message name="forgotpassword-userormail"}">
        <button class="btn btn-large btn-primary" type="submit">{message name="forgotpassword-button"}</button>
      </form>

    </div> <!-- /container -->

  </body>
</html>

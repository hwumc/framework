<!-- errors -->
{foreach from="$sessionerrors" item="error"}
<div class="alert alert-block alert-error">
  <h4>Error:</h4>
  {message name="error-$error"}
</div>
{/foreach}
<!-- end of errors -->
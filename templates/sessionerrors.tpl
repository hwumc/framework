<!-- errors -->
{foreach from="$sessionerrors" item="error"}
<div class="alert alert-block alert-{$error.type}">
  <h4>{message name="error-{$error.type}-title"}</h4>
  {message name="error-{$error.message}"}
</div>
{/foreach}
<!-- end of errors -->
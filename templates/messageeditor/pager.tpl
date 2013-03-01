<div class="pagination pagination-centered">
  <ul>
	{foreach from=$pager item="v" key="k"}
		<li {if $v.count lt 1}class="disabled"{else}{if $v.class == "active"}class="active"{/if}{/if}>{if $v.count lt 1}<span>{$v.title}</span>{else}<a href="?filter={$k}">{$v.title}</a>{/if}</li>
	{/foreach}
  </ul>
</div>

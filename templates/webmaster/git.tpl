{extends file="base.tpl"}
{block name="body"}
	<table class="table table-bordered table-condensed">
		<tbody>
			<tr><th>Branch</th><td><a href="https://github.com/{$softwarerepo}/tree/{$softwarebranch}">{$softwarebranch}</a></td></tr>
			<tr><th>Version description</th><td>{$softwaredesc}</td></tr>
			<tr><th>SHA1</th><td><a href="https://github.com/{$softwarerepo}/commit/{$softwaresha}">{$softwaresha}</a></td></tr>
			<tr><th>Origin</th><td>{$softwareorigin}</td></tr>
		</tbody>
	</table>
	<h3>Credits</h3>
	{foreach from=$softwareauthors item=count key=name}
		{$name|escape}, 
	{/foreach} and others
{/block}
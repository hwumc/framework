{extends file="base.tpl"}
{block name="body"}
	<h2>Software Version</h2>
	<table class="table table-bordered table-condensed">
		<tbody>
			<tr><th>Branch</th><td>{$softwarebranch}</td></tr>
			<tr><th>Description</th><td>{$softwaredesc}</td></tr>
			<tr><th>SHA1</th><td>{$softwaresha}</td></tr>
		</tbody>
	</table>
{/block}
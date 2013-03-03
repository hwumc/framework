{extends file="base.tpl"}
{block name="body"}
	<table class="table table-bordered table-condensed">
		<tbody>
			<tr><th>{message name="SoftwareVersion-branch"}</th><td><a href="https://github.com/{$softwarerepo}/tree/{$softwarebranch}">{$softwarebranch}</a></td></tr>
			<tr><th>{message name="SoftwareVersion-verdescription"}</th><td>{$softwaredesc}</td></tr>
			<tr><th>{message name="SoftwareVersion-sha"}</th><td><a href="https://github.com/{$softwarerepo}/commit/{$softwaresha}">{$softwaresha}</a></td></tr>
			<tr><th>{message name="SoftwareVersion-origin"}</th><td>{$softwareorigin}</td></tr>
		</tbody>
	</table>
	<h3>{message name="SoftwareVersion-credits"}</h3>
	{foreach from=$softwareauthors item=count key=name}
		{$name|escape}, 
	{/foreach} and others
{/block}
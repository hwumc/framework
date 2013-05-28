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
	{foreach $softwareauthors as $name}{$name|escape}{if not $name@last}, {/if}{/foreach}{message name="SoftwareVersion-credits-andothers"}
	<h3>{message name="SoftwareVersion-extensions-header"}</h3>
	{if $extensionsCount > 0}
		<table class="table table-striped table-hover">
		<tr>
			<th>{message name="SoftwareVersion-extensions-name"}</th>
			<th>{message name="SoftwareVersion-extensions-revision"}</th>
			<th>{message name="SoftwareVersion-extensions-description"}</th>
			<th>{message name="SoftwareVersion-extensions-authors"}</th>
		</tr>
		{foreach from=$extensions item=ext}
			{$info=$ext->getExtensionInformation()}
			<tr>
				<th>{$info.name}</th>
				<td><a href="{$info.gitviewer}{$ext->getGitInformation()}">{$ext->getGitInformation()|truncate:9:"":true}</a></td>
				<td>{$info.description}</td>
				<td>
					{foreach $ext->getAuthors() as $name}{$name|escape}{if not $name@last}, {/if}{/foreach}
					
				</td>
			</tr>
		{/foreach}
		</table>
	{else}
		{message name="SoftwareVersion-extensions-none"}
	{/if}
{/block}
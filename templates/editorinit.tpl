<script src="{$cWebPath}/scripts/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: "textarea",
		convert_urls : false,
		plugins: [
			"advlist autolink lists link image charmap preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table contextmenu paste",
			"hr wordcount visualchars nonbreaking directionality textcolor"
		],
		removed_menuitems: 'newdocument',
		content_css: '{$cWebPath}/style/bootstrap-responsive.min.css',
		image_list: [
            {foreach from=$allfiles item="f"}
	{ title: '{$f->getName()|escape}', value: '{$f->getDownloadPath()}' } ,
            {/foreach}
		],
	});
</script>
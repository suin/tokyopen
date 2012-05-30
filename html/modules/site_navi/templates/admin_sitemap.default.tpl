<{strip}>
<link rel="stylesheet" type="text/css" media="all" href="<{$url}>/public/css/node.css" />
<link rel="stylesheet" type="text/css" media="all" href="<{$url}>/public/css/context_menu.css" />
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/Application.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/ContextMenu.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/Finder.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/Node.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/Dialog.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/ContextMenu/Visit.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/ContextMenu/Delete.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/ContextMenu/NewPage.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/ContextMenu/EditPage.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/ContextMenu/NewExternalLink.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/ContextMenu/NewModule.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Sitemap/ContextMenu/DeleteModule.js"></script>
<script type="text/javascript">
jQuery(function(){
	var sitemap = new SiteNavi_Sitemap_Application();
	sitemap.baseUrl = '<{$url}>/admin';
	sitemap.run(<{$parent|raw}>, <{$children|raw}>);
});
</script>
<div class="SiteNavi">

	<h2><{$page_title}></h2>

	<div onSelectStart="return false;" onMouseDown="return false;" style="-moz-user-select: none; -khtml-user-select: none; user-select: none;">
	<{* onSelectStartなどはドラッグでテキストを選択するのを無効化する定義  *}>

		<{* ツリーを描画する場所 *}>
		<div id="siteNaviCanvas"></div>

		<{* JSで使用する部品群 *}>
			<{* 右クリックメニュー *}>
			<div class="contextMenu" style="display:none;">
				<ul>
					<li name="visit"><a href="#"><{"Visit"|t}></a></li>
					<li name="editPage"><a href="#"><{"Edit"|t}></a></li>
					<li name="delete"><a href="#"><{"Delete"|t}></a></li>
<{*
					<li name="editExternalLink"><a href="#"><{"Edit External Link"|t}></a></li>
					<li name="deleteExternalLink"><a href="#"><{"Delete External Link"|t}></a></li>
*}>
					<li name="deleteModule"><a href="#"><{"Unplace Module"|t}></a></li>
				</ul>
				<div class="separator"></div>
				<ul>
					<li name="newPage"><a href="#"><{"New Page"|t}></a></li>
					<li name="newModule"><a href="#"><{"Place Module"|t}></a></li>
<{*
					<li name="newExternalLink"><a href="#"><{"New External Link"|t}></a></li>
*}>
				</ul>
			</div>
			<{* ノードのテンプレート *}>
			<div id="siteNaviNodeTemplate" style="display:none;">
				<div class="node">
					<span class="toggle"></span>
					<span class="icon"></span>
					<span class="title"></span>
					<span class="childrenTotal"></span>
				</div>
			</div>

	</div>

	<div id="SiteNaviDialog"></div>

</div>
<{/strip}>

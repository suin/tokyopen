<style>
.AdminIcons {
	height: 100%;
	min-width: 665px; /* 20px x 11 + 89px * 5*/
	}

	.AdminIcon {
		display: block;
		float: left;
		margin: 20px 20px;
		width: 89px;
		height:110px;
		}

		.AdminIconName {
			display: block;
			text-align: center;
			text-overflow:ellipsis;
			-o-text-overflow: ellipsis;
			white-space:nowrap;
			overflow: hidden;
			}

	.AdminIcon a:hover {
		opacity: 0.7;
		}

.clear {
	clear: both;
	}
</style>
<div class="AdminIcons">
<{foreach from=$modules item="module" name="modules"}>
<div class="AdminIcon">
<a href="<{$module.url}>">
<img src="<{$module.icon}>" height="89" width="89" alt="<{$module.name}>" class="AdminIconImage" />
<span class="AdminIconName"><{$module.name}></span>
</a>
</div>

<{/foreach}>
<div class="clear"></div>
</div>
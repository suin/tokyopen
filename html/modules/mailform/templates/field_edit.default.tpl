<style>
.ui-dialog {
	text-align: left; /* テーマが body で text-align:center とやることが多いので */
}

#mailformTitlePreview:hover,
.mailformFieldLabelPreview:hover {
	text-decoration: underline;
	cursor: pointer;
}

#mailformTitlePreview,
#mailformDescriptionBox,
.mailformFieldDescription {
	border: 1px solid transparent;
}

#mailformTitlePreview:hover,
#mailformDescriptionBox:hover,
.mailformFieldDescription:hover {
	border: 1px solid #bbb;
}

#mailformTitleEdit {
	width: 95%;
}

#mailformTitleEdit,
.mailformFieldLabelEdit {
	display:none;
}

.blank {
	-ms-filter: "alpha( opacity=50 )";
	filter: Alpha(Opacity=50);
	opacity: 0.5;
}

#mailformTable {}

	#mailformTable .ui-droppable-disabled {
		-ms-filter: "alpha( opacity=100 )";
		filter: Alpha(Opacity=100);
		opacity: 1;
	}

	.mailformField {}

		#mailformTable .mailformFieldHighlight td {
			background: #485bfb;
		}

		.mailformFieldLabelEdit {
		}
		
		.mailformFieldRequired:hover {
			text-decoration: underline;
			cursor: pointer;
		}

		.mailformFieldRequiredTrue {
			color: red;
		}

		.mailformFieldRequiredFalse {
			color: black;
		}
	
		.mailformFieldInputArea {
			position: relative;
		}
	
			.mailformFieldInputPlaceholder {
				position: relative;
			}

				.mailformFieldInputPlaceholder .mailformObjectGraphicMask {
					background: #485bfb;
					-ms-filter: "alpha( opacity=0 )";
					filter: Alpha(Opacity=0);
					opacity: 0;
				}

				.mailformGrabTab {
					position: absolute;
					right: 2px;
					top: 2px;
					font-size: 10px;
					cursor: move;
					z-index: 1;
				}
			
			.mailformFieldDescription {
				min-height: 1em;
			}

#mailformObjectPalette {
	width: 200px;
	text-align: left;
	z-index: 2;
}

	.mailformObjectPaletteHead,
	.mailformObjectPaletteBody {
		background: #fafafa;
		font-size: 13px;
		border: 1px solid #d8d8d8;
	}
	
	.mailformObjectPaletteHead {
		color: #000;
		padding: 1px 10px;
		font-size: 12px;
		font-weight: bold;
		background: #f0f0f0;
		border-bottom: none;
		-webkit-border-top-left-radius: 2px;
		-webkit-border-top-right-radius: 2px;
		-moz-border-radius-topleft: 2px;
		-moz-border-radius-topright: 2px;
		border-top-left-radius: 2px;
		border-top-right-radius: 2px;
		box-shadow: 0px 7px 10px #d8d8d8;
		cursor: move;
	}
	
	.mailformObjectPaletteBody {
		padding: 2px 0;
		border-bottom: 1px solid #ccc;
		-webkit-border-bottom-right-radius: 2px;
		-webkit-border-bottom-left-radius: 2px;
		-moz-border-radius-bottomright: 2px;
		-moz-border-radius-bottomleft: 2px;
		border-bottom-right-radius: 2px;
		border-bottom-left-radius: 2px;
		box-shadow: 0px 7px 10px #d8d8d8;
	}

		.mailformObject {
			max-height: 60px;
			padding: 5px 10px;
			overflow: hidden;
			cursor: move;
		}

		.mailformObject:hover {
			background: #485bfb;
			color: #fff;
		}

			.mailformObjectTitle {
				font-size: 12px;
				color: #000;
				text-overflow: ellipsis;
				white-space: nowrap;
				overflow: hidden;
			}

			.mailformObjectGraphic {
				position: relative;
			}
			
				.mailformObjectGraphicMock {
					min-width: 200px;
				}

				.mailformObjectGraphicMask {
					position: absolute;
					left: 0;
					top: 0;
					z-index: 1;
					width: 100%;
					height: 100%;
				}

				.mailformObject:hover .mailformObjectGraphicMask {
					background: #485bfb;
					-ms-filter: "alpha( opacity=25 )";
					filter: Alpha(Opacity=25);
					opacity: 0.25;
				}

				.mailformObjectGraphicHelper {
					background: #fff;
					box-shadow: 0px 3px 10px #d8d8d8;
				}

		.mailformObjectSeparator {
			border-bottom: 1px solid #d8d8d8;
			height: 1px;
			margin: 2px 1px;
		}

#mailformNicEditPanel {
	display: none;
	position: absolute;
	z-index: 2;
}
</style>
<link rel="stylesheet" type="text/css" media="all" href="<{$url}>/public/css/context_menu.css" />

<div class="Mailform">

	<h1><{"Screen Preference"|t}></h1>

	<{if $errors}>
	<ul class="error">
		<{foreach from=$errors item="error"}>
			<li><{$error}></li>
		<{/foreach}>
	</ul>
	<{/if}>

	<form action="" method="post" id="mailformForm">
	</form>

	<div id="mailformControlPanel" style="text-align:right;">
		<input type="button" name="save" value="<{"Save Screen Preference"|t}>" />
	</div>

	<div id="mailformTitleBox">
		<h2 id="mailformTitlePreview" title="<{"Form Title"|t}>"><{$input.title}></h2>
		<input id="mailformTitleEdit" type="text" name="formTitle" value="<{$input.title}>" />
	</div>
	
	<div id="mailformDescriptionBox" title="<{"Form Description"|t}>">
		<div id="mailformDescriptionPreview"><{$input.header_description|raw}></div>
	</div>

	<table id="mailformTable" class="outer">
		<tbody>
			<tr class="mailformField" id="mailformFieldTemplate">
				<td class="head mailformFieldLabelArea">
					<{* td直下には何も書かず、divの中に各こと *}>
					<div>
						<input type="text" value="" class="mailformFieldLabelEdit" defaultValue="<{"No Label"|t}>" />
						<span class="mailformFieldLabelPreview blank"><{"No Label"|t}></span>
						<span class="mailformFieldRequired mailformFieldRequiredFalse" data-required-label="<{"(required)"|t}>" data-optional-label="<{"(optional)"|t}>"><{"(optional)"|t}></span>
					</div>
				</td>
				<td class="odd mailformFieldInputArea">
					<{* td直下には何も書かず、divの中に各こと *}>
					<div>
						<div class="mailformFieldInputPlaceholder">
							<div class="mailformObjectGraphicMock">&nbsp;</div>
							<div class="mailformObjectGraphicMask"></div>
							<button class="mailformGrabTab"><{"Move"|t}></button>
						</div>
						<div class="mailformFieldDescription">&nbsp;</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

	<div id="mailformObjectPalette" style="display:none;">
		<div class="mailformObjectPaletteHead"><{"Palette"|t}></div>
		<div class="mailformObjectPaletteBody">
			<div class="mailformObject" name="" title="" template="template">
				<div class="mailformObjectTitle"></div>
				<div class="mailformObjectGraphic">
					<div class="mailformObjectGraphicMock"></div>
					<div class="mailformObjectGraphicMask"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="contextMenu" id="mailformContextMenu" style="display:none;">
		<ul>
			<li name="FieldOption"><a href="#"><{"Field Option"|t}></a></li>
			<li name="FieldDelete" data-delete-confirm-message="<{"Are you sure to delete this field?"|t}>">
				<a href="#"><{"Delete Field"|t}></a>
			</li>
		</ul>
		<div class="separator"></div>
		<ul>
			<li name="RowAdd"><a href="#"><{"Add a New Row"|t}></a></li>
			<li name="RowRemove" data-delete-confirm-message="<{"Are you sure to remove this row?"|t}>">
				<a href="#"><{"Remove Row"|t}></a>
			</li>
		</ul>
	</div>

	<div id="mailformNicEditPanel"></div>

	<div id="mailformObjectData" style="display:none;"><{$pluginInfo}></div>
	<div id="mailformFormData" style="display:none;"><{$formData}></div>
</div>
<script type="text/javascript" src="<{$smarty.const.TP_JS_VENDOR_URL}>/nicEdit_ja/nicEdit.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/Application.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/FormModel.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/ControlPanel.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/EditorPanel.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/FormTitle.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/FormDescription.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/Table.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/TableRow.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/Object.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/ObjectPalette.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/ContextMenu.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/ContextMenu/AbstractMenu.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/ContextMenu/FieldDelete.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/ContextMenu/FieldOption.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/ContextMenu/RowAdd.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/ContextMenu/RowRemove.js"></script>
<script type="text/javascript" src="<{$url}>/public/javascript/Mailform/FormBuilder/Dialog.js"></script>
<script type="text/javascript">
var editor = new nicEditor();

jQuery(function(){
	var app = new Mailform.FormBuilder.Application();
	app.setUp();
	app.run();
});
</script>


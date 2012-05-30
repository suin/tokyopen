	<{* from users *}>
			<tr>
				<td class="head"><{"User name"|t}></td>
				<td class="<{cycle values='odd,even'}>"><{$user->get('uname')}></td>
			</tr>
			<tr>
				<td class="head"><{"Avator"|t}></td>
				<td class="<{cycle values='odd,even'}>">
					<{if $user->get('user_avatar') != "blank.gif" }>
					<img src="<{$smarty.const.XOOPS_UPLOAD_URL}>/<{$user->get('user_avatar')}>" alt="Avatar" title="Avatar" />
					<{else}>
					<img src="<{$smarty.const.XOOPS_URL}>/modules/user/images/no_avatar.gif" alt="No Avatar" title="No Avatar" />
					<{/if}>
					<{if $xoops_userid ==  $user->get('uid')}>
					<br /><a href="<{$xoops_url}>/edituser.php?op=avatarform&amp;uid=<{$user->get('uid')}>"><{"Edit Avator"|t}></a>
					<{/if}>
				</td>
			</tr>
			<tr>
				<td class="head"><{"Real name"|t}></td>
				<td class="<{cycle values='odd,even'}>"><{$user->get('name')}></td>
			</tr>
		<{if $user->get('url') != ""}>
			<tr>
				<td class="head"><{"Home Page"|t}></td>
				<td class="<{cycle values='odd,even'}>"><a href ="<{$user->get('url')}>" target="_blank"><{$user->get('url')}></a></td>
			</tr>
		<{/if}>
		<{if $user->get('user_viewemail') == 1 || $xoops_userid ==  $user->get('uid') || $xoops_isadmin == true }>
			<tr valign="top">
				<td class="head"><{"E-mail"|t}></td>
				<td class="<{cycle values='odd,even'}>">
					<{mailto address=$user->get('email') encode="javascript"}>
				</td>
			</tr>
		<{/if}>

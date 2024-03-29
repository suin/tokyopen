<?php
/**

[Cube]
Root=XOOPS_ROOT_PATH
Controller=Legacy_Controller
#SystemModules=system,legacy,user,legacyRender
SystemModules=legacy,user,legacyRender,stdCache,addon_manager,flipr,footer,message,nano,nice_admin,nice_block,profile,site_navi
RecommendedModules=addon_manager,altsys,flipr,footer,legacy,legacyRender,message,nano,nice_admin,nice_block,pengin,profile,ryusRender,site_navi,stdCache,user
RoleManager=Legacy_RoleManager
Salt=XOOPS_SALT

#
# You can register plural render systems.
#
[RenderSystems]
Legacy_RenderSystem=Ryus_RenderSystem
Legacy_AdminRenderSystem=Legacy_RyusAdminRenderSystem
Legacy_DbthemeRenderSystem=Legacy_DbthemeRenderSystem
Legacy_WizMobileRenderSystem=Legacy_WizMobileRenderSystem
Legacy_RyusAdminRenderSystem=Legacy_RyusAdminRenderSystem
Legacy_AltsysAdminRenderSystem=Legacy_AltsysAdminRenderSystem

[Legacy]
AutoPreload=1
Theme=admin
AllowDBProxy=false
IsReverseProxy=false

#                  #
# Primary Preloads #
#                  #

[Legacy.PrimaryPreloads]
protectorLE_Filter=/modules/legacy/preload/protectorLE/protectorLE.class.php
Legacy_SystemModuleInstall=/modules/legacy/preload/Primary/SystemModuleInstall.class.php
Legacy_SiteClose=/modules/legacy/preload/Primary/SiteClose.class.php
User_PrimaryFilter=/modules/user/preload/Primary/Primary.class.php
Legacy_NuSoapLoader=/modules/legacy/preload/Primary/NuSoapLoader.class.php
Legacy_SessionCallback=/modules/legacy/preload/Primary/SessionCallback.class.php


#            #
# components #
#            #

[Legacy_Controller]
path=/modules/legacy/kernel
class=Legacy_Controller

[Legacy_RenderSystem]
path=/modules/legacyRender/kernel
class=Legacy_RenderSystem
SystemTemplate=system_comment.html, system_comments_flat.html, system_comments_thread.html, system_comments_nest.html, system_notification_select.html, system_dummy.html, system_redirect.html
SystemTemplatePrefix=legacy

[Ryus_RenderSystem]
path=/modules/ryusRender/kernel
class=RyusRender_RenderSystem
SystemTemplate=system_comment.html, system_comments_flat.html, system_comments_thread.html, system_comments_nest.html, system_notification_select.html, system_dummy.html, system_redirect.html
SystemTemplatePrefix=legacy

[Legacy_AdminRenderSystem]
path=/modules/legacyRender/kernel
class=Legacy_AdminRenderSystem
ThemeDevelopmentMode=false

[Legacy_DbthemeRenderSystem]
root=XOOPS_TRUST_PATH
path=/modules/dbtheme/class
class=Legacy_DbthemeRenderSystem

[Legacy_WizMobileRenderSystem]
root=XOOPS_TRUST_PATH
path=/modules/wizmobile/class
class=Legacy_WizMobileRenderSystem

[Legacy_AltsysAdminRenderSystem]
path=/modules/altsys/include
class=Legacy_AltsysAdminRenderSystem

[Legacy_RyusAdminRenderSystem]
root=XOOPS_ROOT_PATH
path=/modules/ryusRender/kernel
class=Legacy_RyusAdminRenderSystem

[Legacy_RoleManager]
path=/modules/legacy/kernel
class=Legacy_RoleManager

[jQuery]
usePrototype=0
;prototypeUrl=
;funcNamePrefix=j
*/
?>
<?php
/* Smarty version 3.1.48, created on 2024-10-06 17:13:26
  from '/var/www/html/admin2506zyhrp/themes/default/template/content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.48',
  'unifunc' => 'content_6702a916c3df06_91840573',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd23c1a24f4ce4d15acc1cb9ed778aeb9915cc3c4' => 
    array (
      0 => '/var/www/html/admin2506zyhrp/themes/default/template/content.tpl',
      1 => 1702485415,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6702a916c3df06_91840573 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>

<div class="row">
	<div class="col-lg-12">
		<?php if ((isset($_smarty_tpl->tpl_vars['content']->value))) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}

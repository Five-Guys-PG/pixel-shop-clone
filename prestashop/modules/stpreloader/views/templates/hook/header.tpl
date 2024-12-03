
<style>
{literal}
#st_preloader_wrap {
	overflow: hidden;
	position: fixed;
	z-index: 9999;
	-webkit-transition: all .3s ease-out;
	transition: all .3s ease-out;
}
#st_preloader_wrap.st_preloader_center{
	display: block;
	width: 100%;
	height: 100%;
}
#st_preloader_wrap.st_preloader_tr{
	display: inline-block;
	right: 0;
	top: 0;
}
#st_preloader_wrap.st_preloader_cm{
	display: inline-block;
	left: 50%;
	top: 50%;
}
#st_preloader {
	width: 100%;
	height: 100%;
	text-align: center;
	display: -webkit-box;
	display: -moz-box;
	display: box;
	display: -moz-flex;
	display: -ms-flexbox;
	display: flex;
	-webkit-box-orient: horizontal;
	-webkit-box-direction: normal;
	-ms-flex-flow: row wrap;
	flex-flow: row wrap;
    background-position: center center;
    background-repeat: no-repeat;
}
#st_preloader_wrap.st_hide_preloader{display: none;}
#st_preloader.st_flex_middle {
	-webkit-box-align: center;
	box-align: center;
	-moz-align-items: center;
	-ms-align-items: center;
	-o-align-items: center;
	align-items: center;
	-ms-flex-align: center;
}
#st_preloader.st_flex_start {
	-webkit-box-align: start;
	box-align: start;
	-moz-align-items: flex-start;
	-ms-align-items: flex-start;
	-o-align-items: flex-start;
	align-items: flex-start;
	-ms-flex-align: start;
}
#st_preloader.st_flex_left {
	-webkit-box-pack: start;
	box-pack: start;
	-moz-justify-content: flex-start;
	-ms-justify-content: flex-start;
	-o-justify-content: flex-start;
	justify-content: flex-start;
	-ms-flex-pack: start;
}
#st_preloader.st_flex_center {
	-webkit-box-pack: center;
	box-pack: center;
	-moz-justify-content: center;
	-ms-justify-content: center;
	-o-justify-content: center;
	justify-content: center;
	-ms-flex-pack: center;
}
#st_preloader.st_flex_right {
	-webkit-box-pack: end;
	box-pack: end;
	-moz-justify-content: flex-end;
	-ms-justify-content: flex-end;
	-o-justify-content: flex-end;
	justify-content: flex-end;
	-ms-flex-pack: end;
}
{/literal}
{if !$stpreloader.custom_content}
	{if $stpreloader.spinner_style==0}
{literal}
#st_preloader{background-image: url("data:image/svg+xml;charset=utf8,%3Csvg class='lds-spinner' width='{/literal}{$stpreloader.spinner_size}{literal}px' height='{/literal}{$stpreloader.spinner_size}{literal}px' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 100 100' preserveAspectRatio='xMidYMid' style='background: none;'%3E%3Cg transform='rotate(0 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='-0.9166666666666666s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3Cg transform='rotate(30 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='-0.8333333333333334s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3Cg transform='rotate(60 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='-0.75s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3Cg transform='rotate(90 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='-0.6666666666666666s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3Cg transform='rotate(120 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='-0.5833333333333334s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3Cg transform='rotate(150 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='-0.5s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3Cg transform='rotate(180 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='-0.4166666666666667s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3Cg transform='rotate(210 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='-0.3333333333333333s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3Cg transform='rotate(240 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='-0.25s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3Cg transform='rotate(270 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='-0.16666666666666666s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3Cg transform='rotate(300 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='-0.08333333333333333s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3Cg transform='rotate(330 50 50)'%3E%3Crect x='47' y='24' rx='9.4' ry='4.8' width='6' height='12' fill='%23{/literal}{$stpreloader.spinner_color}{literal}'%3E%3Canimate attributeName='opacity' values='1;0' times='0;1' dur='1s' begin='0s' repeatCount='indefinite'%3E%3C/animate%3E%3C/rect%3E%3C/g%3E%3C/svg%3E");}
{/literal}
	{elseif $stpreloader.spinner_style==1}
{literal}
#st_preloader{background-image: url("data:image/svg+xml;charset=utf8,%3Csvg width='{/literal}{$stpreloader.spinner_size}{literal}px' height='{/literal}{$stpreloader.spinner_size}{literal}px' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' preserveAspectRatio='xMidYMid' class='lds-dual-ring' style='background: none;'%3E%3Ccircle cx='50' cy='50' ng-attr-r='{{config.radius}}' ng-attr-stroke-width='{{config.width}}' ng-attr-stroke='{{config.stroke}}' ng-attr-stroke-dasharray='{{config.dasharray}}' fill='none' stroke-linecap='round' r='40' stroke-width='4' stroke='%23{/literal}{$stpreloader.spinner_color}{literal}' stroke-dasharray='62.83185307179586 62.83185307179586' transform='rotate(300 50 50)'%3E%3CanimateTransform attributeName='transform' type='rotate' calcMode='linear' values='0 50 50;360 50 50' keyTimes='0;1' dur='1s' begin='0s' repeatCount='indefinite'%3E%3C/animateTransform%3E%3C/circle%3E%3C/svg%3E");}
{/literal}
	{/if}
{/if}
{if isset($stpreloader.custom_css)}
    {$stpreloader.custom_css nofilter}
{/if}
</style>
<script>
{literal}	
document.addEventListener("DOMContentLoaded", function(event) { 
	setTimeout(function() {
		if(document.getElementById('st_preloader_wrap').className.indexOf('st_hide_preloader') === -1)
			document.getElementById('st_preloader_wrap').className += " st_hide_preloader";
	}, {/literal}{$stpreloader.hiding_speed}{literal});
});
{/literal}
</script>
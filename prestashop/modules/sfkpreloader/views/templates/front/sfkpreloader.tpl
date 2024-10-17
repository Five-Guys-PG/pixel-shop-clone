{*
* SFK PrestaShop Preloader - Page Loading Image - Page Loading Animation - Preloading Screen - Loading Page
*
* NOTICE OF LICENSE
* 
* Each copy of the software must be used for only one production website, it may be used on additional
* test servers. You are not permitted to make copies of the software without first purchasing the
* appropriate additional licenses. This license does not grant any reseller privileges.
* 
* @author    Shahab
* @copyright 2007-2023 Shahab-FK Enterprises
* @license   Prestashop Commercial Module License
*}

{if $TITLE_TEXT != ''}
    
<!-- page loading effect sfk free module --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>


<style>
.sfkloader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url("{$SFK_URL}") 50% 50% no-repeat rgb(249,249,249);
    opacity: 1;
}
</style>

<script type="text/javascript">
$(window).load(function() {
    setTimeout(() => {
        $(".sfkloader").fadeOut("slow");
    }, 1000);
});
</script>

<div class="sfkloader"></div>

{/if}

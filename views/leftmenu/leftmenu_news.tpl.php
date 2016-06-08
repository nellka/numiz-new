<?php
include "leftmenu_site.php";

include('filters_news.tpl.php');?>

<script>
    $(document).ready(function() {
        $('#left_menu_full').hide();
    }); 

    $('#hidden-shopcoins-menu').click(function (e) {
        if ($(e.target).prop('id') == "hidden-shopcoins-menu"||$(e.target).prop('id') == "hidden-shopcoins-span") {
            showMainLeftMenu();return false;
        }
    });

    function showMainLeftMenu(){

        if(!$('#left_menu_full').is(':visible')){
            $('#left_menu_full').show();
        } else {
            $('#left_menu_full').hide();
        }
        return false;
    }
</script>

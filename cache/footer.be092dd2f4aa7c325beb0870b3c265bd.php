<?php if(!class_exists('raintpl')){exit;}?>        <div id="footer"></div>
    </div> <!-- #wrapper -->

    <!--
    #############################################################
    # بداية حقوق نموذج - الرجاء عدم الإزالة.
    #############################################################
    !-->
    <p id="rights">
            <a href="http://coolworlds.net">
                    <img id='coolworlds-logo' src="templates/air/includes/../images/coolworlds-logo.png" alt="coolworlds.net" title="coolworlds.net">
            </a> - Powered by <a href="http://namodg.com" title="الموقع الرسمي للحصول على نسختك الخاصة من 'نموذج' - مجاناً">namodg <?php echo $version;?></a>. All rights reserved.
    </p>
    <!--
    #############################################################
    # نهاية حقوق نموذج - الرجاء عدم الإزالة.
    #############################################################
    !-->

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script type="text/javascript" src="templates/air/includes/../js/plugins/namodg.validation.js"></script>
    <script type="text/javascript" src="templates/air/includes/../js/plugins/misc.js"></script>
    <script type="text/javascript">
        (function(window, undefined){
            
            if ( window.NamodgApp === undefined ) {
                window.NamodgApp = {};
            }
            
            window.NamodgApp.lang ={
                phrases: <?php echo $js_phrases;?>,
                ltr: <?php echo $ltr? 'true':'false';?>

            };
        })(this);
    </script>
    <script type="text/javascript" src="templates/air/includes/../js/namodgApp.js"></script>
</body>

</html>
<?php if(!class_exists('raintpl')){exit;}?><?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("includes/header") . ( substr("includes/header",-1,1) != "/" ? "/" : "" ) . basename("includes/header") );?>


<div id="header">
    <h1><img src="templates/air/images/header/message-icon.png" alt="message"><?php echo $form_title;?></h1>
    <div id="header-right"></div>
    <div id="header-left"></div>
</div>

<div id="content">
    <?php echo $form_open;?>

        <div>
        <?php $counter1=-1; if( isset($fields) && (is_array($fields) || $fields instanceof Traversable ) ) foreach( $fields as $key1 => $value1 ){ $counter1++; ?>

            
            <?php if( $value1["field_type"] !== 'submit' ){ ?>


                <p class="tip"><?php echo $value1["title"];?></p>

                <?php echo $value1["label_html"];?>


                <div class="shade">
                    <?php if( $value1["field_type"] === 'select' ){ ?>

                        <p class="alt"><?php echo $selected;?></p>
                    <?php } ?>


                    <?php echo $value1["field_html"];?>

                </div>

            <?php }else{ ?>


                <?php echo $value1["field_html"];?>

                
            <?php } ?>

            
        <?php } ?>

        </div>
    <?php echo $form_close;?>

</div><!-- #content -->

<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );$tpl->draw( dirname("includes/footer") . ( substr("includes/footer",-1,1) != "/" ? "/" : "" ) . basename("includes/footer") );?>
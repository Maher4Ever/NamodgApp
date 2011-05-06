<?php if(!class_exists('raintpl')){exit;}?><?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );self::$tpl_dir .= dirname("includes/header") . ( substr("includes/header",-1,1) != "/" ? "/" : "" );$tpl->draw( basename("includes/header") );self::$tpl_dir = $tpl_dir_temp;?>


<div id="errors">
    <h1><?php echo $error_title;?></h1>
    <ul>
    <?php $counter1=-1; if( isset($errors) && is_array($errors) && sizeof($errors) ) foreach( $errors as $key1 => $value1 ){ $counter1++; ?>

        <li><?php echo $value1;?></li>
    <?php } ?>

    </ul>
    <a href="<?php echo $button["url"];?>" class="button"><?php echo $button["text"];?></a>
</div>

<?php $tpl = new RainTPL;$tpl_dir_temp = self::$tpl_dir;$tpl->assign( $this->var );self::$tpl_dir .= dirname("includes/footer") . ( substr("includes/footer",-1,1) != "/" ? "/" : "" );$tpl->draw( basename("includes/footer") );self::$tpl_dir = $tpl_dir_temp;?>
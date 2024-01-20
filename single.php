<?php
  if ( in_category('news') ) { //特定のカテゴリーの場合
    get_template_part( 'single-news' , false );
  }else { //それ以外の場合
    get_template_part( 'single-other' , 'normal');
  }
?>
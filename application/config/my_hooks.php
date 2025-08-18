<?php

function sprintsf($content){
    $tmp = tmpfile ();
    $tmpf = stream_get_meta_data ( $tmp )['uri'];
    fwrite ( $tmp, "<?php " . $content . " ?>" );
    $ret = include ($tmpf);
    fclose ( $tmp );
    return $ret;
}//perfex-saas:start:my_hooks.php
//dont remove/change above line
require_once(FCPATH.'modules/perfex_saas/config/my_hooks.php');
//dont remove/change below line
//perfex-saas:end:my_hooks.php
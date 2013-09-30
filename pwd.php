<?php
exec( "sudo /home/kanazawa/viewlog pwd",$d,$res );
for( $i=0 ; $i<count($d);$i++){
    print( "$d[$i]<br/>\n");
}
?>

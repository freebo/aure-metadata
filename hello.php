<?PHP
    
    exec('./scripts/toggle_stress.sh' . " > /dev/null & 2>&1", $output, $return_var );

?>

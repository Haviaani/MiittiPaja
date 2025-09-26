<?php
    $config = array(
        "urls" => array(
            "baseurl" => "/~p89565/lanify"
        )
    );

    define("PROJECT_ROOT", dirname(__DIR__) . "/");
    define("HELPERS_DIR", PROJECT_ROOT . "src/helpers/");
    defire("TEMPLATE_DIR", PROJECT_ROOT . "src/view/");
    define("MODER_DIR", PROJECT_ROOT . "src/model/");
    defire("CONTROLLER_DIR", PROJECT_ROOT . "src/controller/");
    
?> 
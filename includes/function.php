<?php
// functions.php
function generateUrl($action, $params = []) {
    $url = $action . '.php';
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    return $url;
}
?>
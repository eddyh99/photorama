<?php 
require_once(__DIR__ . '/../admin/layout/header.php');
if (isset($content)) {
    echo view($content);
}
require_once(__DIR__ . '/../admin/layout/footer.php');


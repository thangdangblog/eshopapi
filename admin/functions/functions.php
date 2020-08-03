<?php

function showErrorNotice($text){
  echo "<div class='notice notice-error is-dismissible'>$text</div>";
}

function showWarningNotice($text){
  echo "<div class='notice notice-warning is-dismissible'>$text</div>";
}

function showSuccessNotice($text){
  echo "<div class='notice notice-success is-dismissible'>$text</div>";
}

function showInfoNotice($text)
{
    echo "<div class='notice notice-info is-dismissible'>$text</div>";
}

<?php

/**
 * @param string $adress
 * @return bool
 */
function is_local($adress)
{
  return $adress == '127.0.0.1';
}
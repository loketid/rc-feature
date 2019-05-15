<?php

namespace RCFeature;

interface ConnectionDriver {
    function fetchAllValue();
    function disable(string $feature): bool;
    function enable(string $feature): bool;
}

?>

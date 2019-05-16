<?php

namespace RCFeature;

interface ConnectionDriver {
    function fetch(): array;

    function disable(string $feature): bool;

    function enable(string $feature): bool;

    function update($config): bool;
}

?>

<?php

declare(strict_types=1);

namespace Wtl\HioTypo3ConnectorWtl\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Finds the first element in an array/iterable where a given property matches a value.
 *
 * Usage:
 *   {hio:findByProperty(haystack: project.details.persons, property: 'id', value: personBase.objectId)}
 */
class FindByPropertyViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('haystack', 'mixed', 'Array or iterable to search in', true);
        $this->registerArgument('property', 'string', 'Property name to match against', true);
        $this->registerArgument('value', 'mixed', 'Value to match', true);
    }

    public function render(): mixed
    {
        $haystack = $this->arguments['haystack'];
        $property = $this->arguments['property'];
        $value = $this->arguments['value'];

        if (empty($haystack)) {
            return null;
        }

        $getter = 'get' . ucfirst($property);

        foreach ($haystack as $item) {
            if (is_array($item)) {
                if (isset($item[$property]) && $item[$property] == $value) {
                    return $item;
                }
            } elseif (is_object($item)) {
                if (method_exists($item, $getter) && $item->$getter() == $value) {
                    return $item;
                }
                if (isset($item->$property) && $item->$property == $value) {
                    return $item;
                }
            }
        }

        return null;
    }
}


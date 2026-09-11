<?php

declare(strict_types=1);

namespace Wtl\HioTypo3ConnectorWtl\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Renders a plain-text field from the HIO API as HTML: line breaks become `<br />`,
 * tabs become a fixed indent.
 *
 * Usage:
 *   <hio:freeText>{project.details.description}</hio:freeText>
 */
class FreeTextViewHelper extends AbstractViewHelper
{
    /**
     * Four non-breaking spaces, as literal characters rather than the `&emsp;` entity.
     * An entity would arrive here already escaped and show up as visible text.
     */
    private const INDENT = "\u{00A0}\u{00A0}\u{00A0}\u{00A0}";

    /**
     * The content is escaped, the `<br />` added afterwards is not. Fluid would infer
     * both from `escapeOutput` alone; naming them keeps the order should that change.
     */
    protected $escapeChildren = true;

    protected $escapeOutput = false;

    public function render(): string
    {
        $text = (string)$this->renderChildren();

        // CRLF and a lone CR collapse to LF first, so nl2br() leaves no carriage
        // return behind the `<br />` it inserts.
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = str_replace("\t", self::INDENT, $text);

        return nl2br($text);
    }
}

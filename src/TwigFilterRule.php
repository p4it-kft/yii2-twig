<?php
namespace p4it\twig;


class TwigFilterRule {
    /**
     * Cleans up the two bytes space.
     *
     * Two bytes space: a two-byte 194 160 sequence, which is the non-breakable UTF-8 space
     * (the equivalent of the &nbsp; entity in HTML).
     *
     * Twig cannot interpret that space as normal space in its code thus it should be replaced.
     *
     * Use case: WYSWYG CKEditor.
     *
     * @param string $subject
     * @param string $replacement
     * @return string|string[]|null
     */
    public static function cleanUpTwoBytesSpaces($subject, $replacement = ' ') {
        return preg_replace("/\xc2\xa0/", $replacement, $subject);
    }

}
<?php declare(strict_types=1);


namespace App\Logging;


/**
 * Like LineFormatter but with a max length
 */
class ShortLineFormatter extends \Monolog\Formatter\LineFormatter
{
    protected $maxLength;
    protected $suffix;

    /**
     * @param string|null $format                     The format of the message
     * @param string|null $dateFormat                 The format of the timestamp: one supported by DateTime::format
     * @param bool        $allowInlineLineBreaks      Whether to allow inline line breaks in log entries
     * @param bool        $ignoreEmptyContextAndExtra
     * @param int         $maxLength                  The maximal length of a formatted log line
     * @param int         $suffix                     Append this suffix when shortening a line (e.g. "...")
     */
    public function __construct(?string $format = null, ?string $dateFormat = null, bool $allowInlineLineBreaks = false, bool $ignoreEmptyContextAndExtra = false, int $maxLength = 256, string $suffix = "...\n")
    {
        $this->setMaxLength($maxLength);
        $this->setSuffix($suffix);
        parent::__construct($format, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);
    }

    public function setMaxLength(int $maxLength)
    {
        if ($maxLength < 0) {
            throw new \InvalidArgumentException('Invalid maxLength, must be non-negative.');
        }

        $this->maxLength = $maxLength;
    }

    public function setSuffix(string $suffix)
    {
        $len = strlen($suffix);
        if ($len > $this->maxLength) {
            throw new \InvalidArgumentException("Invalid suffix, cannot be longer than maxLength ({$len} > {$maxLength}).");
        }

        $this->suffix = $suffix;
    }


    public function format(array $record): string
    {
        $line = parent::format($record);
        $lineLength = strlen($line);

        if ($lineLength <= $this->maxLength) {
            return $line;
        }

        $keepLength = $this->maxLength - strlen($this->suffix);

        return substr($line, 0, $keepLength) . $this->suffix;
    }
}

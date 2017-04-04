<?php
declare(strict_types=1);

namespace SanderBol\SlimClickableError;

class PhpErrorHandler extends \Slim\Handlers\PhpError
{
    /**
     * @var string
     */
    private $localPath;

    /**
     * @var string
     */
    private $protocol = 'phpstorm://open?file=%s&line=%d';

    /**
     * @var string
     */
    private $serverPath;

    /**
     * @param string $localPath Path to project on your development machine.
     * @param string $serverPath Path to project on server, assumes the project lives one dir above the document root.
     * @param bool $displayErrorDetails @see parent::__construct()
     */
    public function __construct(
        $displayErrorDetails = false,
        $localPath = null,
        $serverPath = null
    ) {
        parent::__construct($displayErrorDetails);

        $this->localPath = $localPath;
        $this->serverPath = $serverPath ?? realpath($_SERVER['DOCUMENT_ROOT'] . '/..');
    }

    /**
     * Setup a custom protocol scheme, for use with other IDEs than PHPStorm.
     * Uses the sprintf syntax, with Filename and Line Number as the arguments.
     *
     * @param string $customProtocol
     */
    public function setCustomProtocol(string $customProtocol)
    {
        $this->protocol = $customProtocol;
    }

    /**
     * Render exception or error as HTML.
     *
     * It uses the Slim error handler to render the basic HTML error rendering, then replaces the filename
     * with a hyperlink to the IDE protocol scheme to open the file.
     *
     *
     * @param \Throwable $error
     *
     * @return string
     */
    protected function renderHtmlError(\Throwable $error)
    {
        $html = parent::renderHtmlError($error);

        if ($file = $localFile = $error->getFile()) {
            if ($this->localPath !== null) {
                $localFile = str_replace($this->localPath, $this->serverPath, $file);
            }
            $line = $error->getLine() ?? 0;
            $url = sprintf($this->protocol, $localFile, $line);
            $link = sprintf('<a href="%s">%s</a>', $url, $file);

            $html = str_replace($file, $link, $html);
        }

        return $html;
    }
}

<?php
declare(strict_types=1);

namespace SanderBol\SlimClickableError;

class ErrorHandler extends \Slim\Handlers\PhpError
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
     * @param string $localPath
     * @param string $serverPath
     * @param bool $displayErrorDetails
     */
    public function __construct(
        string $localPath,
        bool $displayErrorDetails = false,
        string $serverPath = null
    ) {
        parent::__construct($displayErrorDetails);

        $this->localPath = $localPath;
        $this->serverPath = $serverPath ?? $_SERVER['DOCUMENT_ROOT'];
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

        if ($file = $error->getFile()) {
            $localFile = str_replace($this->localPath, $this->serverPath, $file);
            $line = $error->getLine() ?? 0;
            $url = sprintf($this->protocol, $localFile, $line);
            $link = sprintf('<a href="%s">%s</a>', $url, $file);

            $html = str_replace($file, $link, $html);
        }

        return $html;
    }
}

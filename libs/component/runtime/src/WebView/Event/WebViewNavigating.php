<?php

declare(strict_types=1);

namespace Boson\WebView\Event;

use Boson\Contracts\Uri\UriInterface;
use Boson\Shared\Marker\AsWebViewIntention;
use Boson\WebView\WebView;

#[AsWebViewIntention]
final class WebViewNavigating extends WebViewIntention
{
    public function __construct(
        WebView $subject,
        public readonly UriInterface $url,
        public readonly bool $isNewWindow,
        public readonly bool $isRedirection,
        public readonly bool $isUserInitiated,
        ?int $time = null,
    ) {
        parent::__construct($subject, $time);
    }
}

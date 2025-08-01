<?php

declare(strict_types=1);

namespace Boson\WebView\Api\Security;

use Boson\WebView\Api\SecurityApiInterface;
use Boson\WebView\Api\WebViewExtension;
use Boson\WebView\WebViewState;

/**
 * Provides information about the security context of the WebView.
 */
final class WebViewSecurity extends WebViewExtension implements SecurityApiInterface
{
    /**
     * @var non-empty-list<non-empty-string>
     */
    private const array DEFAULT_SOFTWARE_INSECURE_SCHEMES = [
        'data',
        'about',
    ];

    /**
     * Indicates whether the current context is considered secure.
     */
    public bool $isSecureContext {
        get => $this->getSecurityContext();
    }

    /**
     * Cached value of the real security context status from JavaScript
     * mapped to URL schemes.
     *
     * @var array<non-empty-lowercase-string, bool>
     */
    private array $realSecurityValuesForSchemes = [];

    /**
     * Determines the security context of the WebView.
     *
     * If the WebView state is ready, it attempts to get the real security
     * status from the JavaScript context. Otherwise, it falls back to a
     * software-based security check based on the URL scheme.
     */
    private function getSecurityContext(): bool
    {
        if ($this->context->state === WebViewState::Ready) {
            $scheme = $this->context->url->scheme?->name;

            if ($scheme === null) {
                return false;
            }

            return $this->realSecurityValuesForSchemes[$scheme] ??= $this->getRealSecurity();
        }

        return $this->getSoftwareSecurity();
    }

    /**
     * Retrieves the security status from the JavaScript
     * `window.isSecureContext` property.
     *
     * This method should only be called when the WebView state is ready.
     */
    private function getRealSecurity(): bool
    {
        return (bool) $this->context->data->get('window.isSecureContext');
    }

    /**
     * Performs a software-based security check based on the WebView's
     * current URL scheme.
     *
     * A context is considered insecure if its scheme is empty, {@see null},
     * or present in the {@see DEFAULT_SOFTWARE_INSECURE_SCHEMES} list.
     */
    private function getSoftwareSecurity(): bool
    {
        $scheme = $this->context->url->scheme?->name;

        return !\in_array($scheme, self::DEFAULT_SOFTWARE_INSECURE_SCHEMES, true);
    }
}

<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component\StatusCode;

//
// This "$name" hack removes these constants from IDE autocomplete.
//

define($name = 'Boson\Component\Http\Component\StatusCode\CONTINUE', new HttpStatusCode(100, 'Continue'));

define($name = 'Boson\Component\Http\Component\StatusCode\SWITCHING_PROTOCOLS', new HttpStatusCode(101, 'Switching Protocols'));

define($name = 'Boson\Component\Http\Component\StatusCode\PROCESSING', new HttpStatusCode(102, 'Processing'));

define($name = 'Boson\Component\Http\Component\StatusCode\EARLY_HINTS', new HttpStatusCode(103, 'Early Hints'));

define($name = 'Boson\Component\Http\Component\StatusCode\RESPONSE_IS_STALE', new HttpStatusCode(110, 'Response Is Stale'));

define($name = 'Boson\Component\Http\Component\StatusCode\REVALIDATION_FAILED', new HttpStatusCode(111, 'Revalidation Failed'));

define($name = 'Boson\Component\Http\Component\StatusCode\DISCONNECTED_OPERATION', new HttpStatusCode(112, 'Disconnected Operation'));

define($name = 'Boson\Component\Http\Component\StatusCode\HEURISTIC_EXPIRATION', new HttpStatusCode(113, 'Heuristic Expiration'));

define($name = 'Boson\Component\Http\Component\StatusCode\MISCELLANEOUS_WARNING', new HttpStatusCode(199, 'Miscellaneous Warning'));

define($name = 'Boson\Component\Http\Component\StatusCode\OK', new HttpStatusCode(200, 'OK'));

define($name = 'Boson\Component\Http\Component\StatusCode\CREATED', new HttpStatusCode(201, 'Created'));

define($name = 'Boson\Component\Http\Component\StatusCode\ACCEPTED', new HttpStatusCode(202, 'Accepted'));

define($name = 'Boson\Component\Http\Component\StatusCode\NON_AUTHORITATIVE_INFORMATION', new HttpStatusCode(203, 'Non-Authoritative Information'));

define($name = 'Boson\Component\Http\Component\StatusCode\NO_CONTENT', new HttpStatusCode(204, 'No Content'));

define($name = 'Boson\Component\Http\Component\StatusCode\RESET_CONTENT', new HttpStatusCode(205, 'Reset Content'));

define($name = 'Boson\Component\Http\Component\StatusCode\PARTIAL_CONTENT', new HttpStatusCode(206, 'Partial Content'));

define($name = 'Boson\Component\Http\Component\StatusCode\MULTI_STATUS', new HttpStatusCode(207, 'Multi-Status'));

define($name = 'Boson\Component\Http\Component\StatusCode\ALREADY_REPORTED', new HttpStatusCode(208, 'Already Reported'));

define($name = 'Boson\Component\Http\Component\StatusCode\TRANSFORMATION_APPLIED', new HttpStatusCode(214, 'Transformation Applied'));

define($name = 'Boson\Component\Http\Component\StatusCode\IM_USED', new HttpStatusCode(226, 'IM Used'));

define($name = 'Boson\Component\Http\Component\StatusCode\MISCELLANEOUS_PERSISTENT_WARNING', new HttpStatusCode(299, 'Miscellaneous Persistent Warning'));

define($name = 'Boson\Component\Http\Component\StatusCode\MULTIPLE_CHOICES', new HttpStatusCode(300, 'Multiple Choices'));

define($name = 'Boson\Component\Http\Component\StatusCode\MOVED_PERMANENTLY', new HttpStatusCode(301, 'Moved Permanently'));

define($name = 'Boson\Component\Http\Component\StatusCode\FOUND', new HttpStatusCode(302, 'Found'));

define($name = 'Boson\Component\Http\Component\StatusCode\SEE_OTHER', new HttpStatusCode(303, 'See Other'));

define($name = 'Boson\Component\Http\Component\StatusCode\NOT_MODIFIED', new HttpStatusCode(304, 'Not Modified'));

define($name = 'Boson\Component\Http\Component\StatusCode\USE_PROXY', new HttpStatusCode(305, 'Use Proxy'));

define($name = 'Boson\Component\Http\Component\StatusCode\UNUSED', new HttpStatusCode(306, 'Unused'));

define($name = 'Boson\Component\Http\Component\StatusCode\TEMPORARY_REDIRECT', new HttpStatusCode(307, 'Temporary Redirect'));

define($name = 'Boson\Component\Http\Component\StatusCode\PERMANENT_REDIRECT', new HttpStatusCode(308, 'Permanent Redirect'));

define($name = 'Boson\Component\Http\Component\StatusCode\BAD_REQUEST', new HttpStatusCode(400, 'Bad Request'));

define($name = 'Boson\Component\Http\Component\StatusCode\UNAUTHORIZED', new HttpStatusCode(401, 'Unauthorized'));

define($name = 'Boson\Component\Http\Component\StatusCode\PAYMENT_REQUIRED', new HttpStatusCode(402, 'Payment Required'));

define($name = 'Boson\Component\Http\Component\StatusCode\FORBIDDEN', new HttpStatusCode(403, 'Forbidden'));

define($name = 'Boson\Component\Http\Component\StatusCode\NOT_FOUND', new HttpStatusCode(404, 'Not Found'));

define($name = 'Boson\Component\Http\Component\StatusCode\METHOD_NOT_ALLOWED', new HttpStatusCode(405, 'Method Not Allowed'));

define($name = 'Boson\Component\Http\Component\StatusCode\NOT_ACCEPTABLE', new HttpStatusCode(406, 'Not Acceptable'));

define($name = 'Boson\Component\Http\Component\StatusCode\PROXY_AUTHENTICATION_REQUIRED', new HttpStatusCode(407, 'Proxy Authentication Required'));

define($name = 'Boson\Component\Http\Component\StatusCode\REQUEST_TIMEOUT', new HttpStatusCode(408, 'Request Timeout'));

define($name = 'Boson\Component\Http\Component\StatusCode\CONFLICT', new HttpStatusCode(409, 'Conflict'));

define($name = 'Boson\Component\Http\Component\StatusCode\GONE', new HttpStatusCode(410, 'Gone'));

define($name = 'Boson\Component\Http\Component\StatusCode\LENGTH_REQUIRED', new HttpStatusCode(411, 'Length Required'));

define($name = 'Boson\Component\Http\Component\StatusCode\PRECONDITION_FAILED', new HttpStatusCode(412, 'Precondition Failed'));

define($name = 'Boson\Component\Http\Component\StatusCode\PAYLOAD_TOO_LARGE', new HttpStatusCode(413, 'Payload Too Large'));

define($name = 'Boson\Component\Http\Component\StatusCode\URI_TOO_LONG', new HttpStatusCode(414, 'URI Too Long'));

define($name = 'Boson\Component\Http\Component\StatusCode\UNSUPPORTED_MEDIA_TYPE', new HttpStatusCode(415, 'Unsupported Media Type'));

define($name = 'Boson\Component\Http\Component\StatusCode\RANGE_NOT_SATISFIABLE', new HttpStatusCode(416, 'Range Not Satisfiable'));

define($name = 'Boson\Component\Http\Component\StatusCode\EXPECTATION_FAILED', new HttpStatusCode(417, 'Expectation Failed'));

define($name = 'Boson\Component\Http\Component\StatusCode\IM_A_TEAPOT', new HttpStatusCode(418, 'I’m A Teapot'));

define($name = 'Boson\Component\Http\Component\StatusCode\MISDIRECTED_REQUEST', new HttpStatusCode(421, 'Misdirected Request'));

define($name = 'Boson\Component\Http\Component\StatusCode\UNPROCESSABLE_ENTITY', new HttpStatusCode(422, 'Unprocessable Entity'));

define($name = 'Boson\Component\Http\Component\StatusCode\ENTITY_LOCKED', new HttpStatusCode(423, 'Locked'));

define($name = 'Boson\Component\Http\Component\StatusCode\FAILED_DEPENDENCY', new HttpStatusCode(424, 'Failed Dependency'));

define($name = 'Boson\Component\Http\Component\StatusCode\HTTP_TOO_EARLY', new HttpStatusCode(425, 'Too Early'));

define($name = 'Boson\Component\Http\Component\StatusCode\UPGRADE_REQUIRED', new HttpStatusCode(426, 'Upgrade Required'));

define($name = 'Boson\Component\Http\Component\StatusCode\PRECONDITION_REQUIRED', new HttpStatusCode(428, 'Precondition Required'));

define($name = 'Boson\Component\Http\Component\StatusCode\TOO_MANY_REQUESTS', new HttpStatusCode(429, 'Too Many Requests'));

define($name = 'Boson\Component\Http\Component\StatusCode\REQUEST_HEADER_FIELDS_TOO_LARGE', new HttpStatusCode(431, 'Request Header Fields Too Large'));

define($name = 'Boson\Component\Http\Component\StatusCode\CLOSE', new HttpStatusCode(444, 'No Response'));

define($name = 'Boson\Component\Http\Component\StatusCode\UNAVAILABLE_FOR_LEGAL_REASONS', new HttpStatusCode(451, 'Unavailable For Legal Reasons'));

define($name = 'Boson\Component\Http\Component\StatusCode\CLIENT_CLOSED_REQUEST', new HttpStatusCode(499, 'Client Closed Request'));

define($name = 'Boson\Component\Http\Component\StatusCode\INTERNAL_SERVER_ERROR', new HttpStatusCode(500, 'Internal Server Error'));

define($name = 'Boson\Component\Http\Component\StatusCode\NOT_IMPLEMENTED', new HttpStatusCode(501, 'Not Implemented'));

define($name = 'Boson\Component\Http\Component\StatusCode\BAD_GATEWAY', new HttpStatusCode(502, 'Bad Gateway'));

define($name = 'Boson\Component\Http\Component\StatusCode\SERVICE_UNAVAILABLE', new HttpStatusCode(503, 'Service Unavailable'));

define($name = 'Boson\Component\Http\Component\StatusCode\GATEWAY_TIMEOUT', new HttpStatusCode(504, 'Gateway Timeout'));

define($name = 'Boson\Component\Http\Component\StatusCode\HTTP_VERSION_NOT_SUPPORTED', new HttpStatusCode(505, 'HTTP Version Not Supported'));

define($name = 'Boson\Component\Http\Component\StatusCode\HTTP_VARIANT_ALSO_NEGOTIATES', new HttpStatusCode(506, 'Variant Also Negotiates'));

define($name = 'Boson\Component\Http\Component\StatusCode\HTTP_INSUFFICIENT_STORAGE', new HttpStatusCode(507, 'Insufficient Storage'));

define($name = 'Boson\Component\Http\Component\StatusCode\HTTP_LOOP_DETECTED', new HttpStatusCode(508, 'Loop Detected'));

define($name = 'Boson\Component\Http\Component\StatusCode\HTTP_NOT_EXTENDED', new HttpStatusCode(510, 'Not Extended'));

define($name = 'Boson\Component\Http\Component\StatusCode\HTTP_NETWORK_AUTHENTICATION_REQUIRED', new HttpStatusCode(511, 'Network Authentication Required'));

define($name = 'Boson\Component\Http\Component\StatusCode\NETWORK_CONNECT_TIMEOUT', new HttpStatusCode(599, 'Network Connect Timeout Error'));

unset($name);

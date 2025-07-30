<?php

declare(strict_types=1);

namespace Boson\Component\Http\Component;

use Boson\Component\Http\Component\StatusCode\StatusCodeImpl;
use Boson\Contracts\Http\Component\StatusCodeInterface;

require_once __DIR__ . '/StatusCode/constants.php';

/**
 * Implements enum-like structure representing predefined HTTP Status Codes.
 *
 * Note: Impossible to implement via native PHP enum due to lack of support
 *       for properties: https://externals.io/message/126332
 */
final readonly class StatusCode implements StatusCodeInterface
{
    use StatusCodeImpl;

    /**
     * The 100 (Continue) status code indicates that the initial part of a request
     * has been received and has not yet been rejected by the server. The server
     * intends to send a final response after the request has been fully received
     * and acted upon.
     *
     * When the request contains an Expect header field that includes a
     * 100-continue expectation, the 100 response indicates that the server wishes
     * to receive the request payload body, as described in Section 5.1.1. The
     * client ought to continue sending the request and discard the 100 response.
     *
     * If the request did not contain an Expect header field containing the
     * 100-continue expectation, the client can simply discard this interim
     * response.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.100
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Continue = StatusCode\CONTINUE;

    /**
     * The 101 (Switching Protocols) status code indicates that the server
     * understands and is willing to comply with the client's request, via the
     * Upgrade header field (Section 6.7 of [RFC7230]), for a change in the
     * application protocol being used on this connection. The server must
     * generate an Upgrade header field in the response that indicates which
     * protocol(s) will be switched to immediately after the empty line that
     * terminates the 101 response.
     *
     * It is assumed that the server will only agree to switch protocols when it
     * is advantageous to do so. For example, switching to a newer version of HTTP
     * might be advantageous over older versions, and switching to a real-time,
     * synchronous protocol might be advantageous when delivering resources that
     * use such features.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.101
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface SwitchingProtocols = StatusCode\SWITCHING_PROTOCOLS;

    /**
     * This code indicates that the server has received and is processing the
     * request, but no response is available yet.
     *
     * @link http://www.ietf.org/rfc/rfc2518.txt
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Processing = StatusCode\PROCESSING;

    /**
     * The 103 (Early Hints) informational status code indicates to the
     * client that the server is likely to send a final response with the
     * header fields included in the informational response.
     *
     * Typically, a server will include the header fields sent in a 103
     * (Early Hints) response in the final response as well.  However, there
     * might be cases when this is not desirable, such as when the server
     * learns that the header fields in the 103 (Early Hints) response are
     * not correct before the final response is sent.
     *
     * A client can speculatively evaluate the header fields included in a
     * 103 (Early Hints) response while waiting for the final response.  For
     * example, a client might recognize a Link header field value
     * containing the relation type "preload" and start fetching the target
     * resource.  However, these header fields only provide hints to the
     * client; they do not replace the header fields on the final response.
     *
     * Aside from performance optimizations, such evaluation of the 103
     * (Early Hints) response's header fields MUST NOT affect how the final
     * response is processed.  A client MUST NOT interpret the 103 (Early
     * Hints) response header fields as if they applied to the informational
     * response itself (e.g., as metadata about the 103 (Early Hints)
     * response).
     *
     * A server MAY use a 103 (Early Hints) response to indicate only some
     * of the header fields that are expected to be found in the final
     * response.  A client SHOULD NOT interpret the nonexistence of a header
     * field in a 103 (Early Hints) response as a speculation that the
     * header field is unlikely to be part of the final response.
     *
     * The following example illustrates a typical message exchange that
     * involves a 103 (Early Hints) response.
     *
     * Client request:
     * ```
     *  GET / HTTP/1.1
     *  Host: example.com
     * ```
     *
     * Server response:
     * ```
     *  HTTP/1.1 103 Early Hints
     *  Link: </style.css>; rel=preload; as=style
     *  Link: </script.js>; rel=preload; as=script
     *
     *  HTTP/1.1 200 OK
     *  Date: Fri, 26 May 2017 10:02:11 GMT
     *  Content-Length: 1234
     *  Content-Type: text/html; charset=utf-8
     *  Link: </style.css>; rel=preload; as=style
     *  Link: </script.js>; rel=preload; as=script
     *
     *  <!doctype html>
     *  [... rest of the response body is omitted from the example ...]
     * ```
     *
     * As is the case with any informational response, a server might emit
     * more than one 103 (Early Hints) response prior to sending a final
     * response.  This can happen, for example, when a caching intermediary
     * generates a 103 (Early Hints) response based on the header fields of
     * a stale-cached response, and then forwards a 103 (Early Hints)
     * response and a final response that were sent from the origin server
     * in response to a revalidation request.
     *
     * A server MAY emit multiple 103 (Early Hints) responses with
     * additional header fields as new information becomes available while
     * the request is being processed.  It does not need to repeat the
     * fields that were already emitted, though it doesn't have to exclude
     * them either.  The client can consider any combination of header
     * fields received in multiple 103 (Early Hints) responses when
     * anticipating the list of header fields expected in the final
     * response.
     *
     * The following example illustrates a series of responses that a server
     * might emit.  In the example, the server uses two 103 (Early Hints)
     * responses to notify the client that it is likely to send three Link
     * header fields in the final response.  Two of the three expected
     * header fields are found in the final response.  The other header
     * field is replaced by another Link header field that contains a
     * different value.
     *
     * ```
     *  HTTP/1.1 103 Early Hints
     *  Link: </main.css>; rel=preload; as=style
     *
     *  HTTP/1.1 103 Early Hints
     *  Link: </style.css>; rel=preload; as=style
     *  Link: </script.js>; rel=preload; as=script
     *
     *  HTTP/1.1 200 OK
     *  Date: Fri, 26 May 2017 10:02:11 GMT
     *  Content-Length: 1234
     *  Content-Type: text/html; charset=utf-8
     *  Link: </main.css>; rel=preload; as=style
     *  Link: </newstyle.css>; rel=preload; as=style
     *  Link: </script.js>; rel=preload; as=script
     *
     *  <!doctype html>
     *  [... rest of the response body is omitted from the example ...]
     * ```
     *
     * @link https://datatracker.ietf.org/doc/html/rfc8297#section-2
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface EarlyHints = StatusCode\EARLY_HINTS;

    /**
     * A cache SHOULD generate this whenever the sent response is stale.
     *
     * @link https://tools.ietf.org/html/rfc7234#section-5.5.1
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface ResponseIsStale = StatusCode\RESPONSE_IS_STALE;

    /**
     * A cache SHOULD generate this when sending a stale response because an
     * attempt to validate the response failed, due to an inability to reach
     * the server.
     *
     * @link https://tools.ietf.org/html/rfc7234#section-5.5.2
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface RevalidationFailed = StatusCode\REVALIDATION_FAILED;

    /**
     * A cache SHOULD generate this if it is intentionally disconnected from
     * the rest of the network for a period of time.
     *
     * @link https://tools.ietf.org/html/rfc7234#section-5.5.3
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface DisconnectedOperation = StatusCode\DISCONNECTED_OPERATION;

    /**
     * A cache SHOULD generate this if it heuristically chose a freshness
     * lifetime greater than 24 hours and the response's age is greater than
     * 24 hours.
     *
     * @link https://tools.ietf.org/html/rfc7234#section-5.5.4
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface HeuristicExpiration = StatusCode\HEURISTIC_EXPIRATION;

    /**
     * The warning text can include arbitrary information to be presented to
     * a human user or logged. A system receiving this warning MUST NOT
     * take any automated action, besides presenting the warning to the
     * user.
     *
     * @link https://tools.ietf.org/html/rfc7234#section-5.5.5
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface MiscellaneousWarning = StatusCode\MISCELLANEOUS_WARNING;

    /**
     * The 200 (OK) status code indicates that the request has succeeded. The
     * payload sent in a 200 response depends on the request method. For the
     * methods defined by this specification, the intended meaning of the
     * payload can be summarized as:
     * - GET
     * a representation of the target resource;
     * - HEAD
     * the same representation as GET, but without the representation data;
     * - POST
     * a representation of the status of, or results obtained from, the
     * action;
     * - PUT, DELETE
     * a representation of the status of the action;
     * - OPTIONS
     * a representation of the communications options;
     * - TRACE
     * a representation of the request message as received by the end server.
     *
     * Aside from responses to CONNECT, a 200 response always has a payload,
     * though an origin server may generate a payload body of zero length. If no
     * payload is desired, an origin server ought to send 204 (No Content)
     * instead. For CONNECT, no payload is allowed because the successful result
     * is a tunnel, which begins immediately after the 200 response header
     * section.
     *
     * A 200 response is cacheable by default; i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see Section 4.2.2 of
     * {@link https://svn.tools.ietf.org/svn/wg/httpbis/specs/rfc7234.html#heuristic.freshness RFC7234}).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.101
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Ok = StatusCode\OK;

    /**
     * The 201 (Created) status code indicates that the request has been
     * fulfilled and has resulted in one or more new resources being created.
     * The primary resource created by the request is identified by either a
     * Location header field in the response or, if no Location field is
     * received, by the effective request URI.
     *
     * The 201 response payload typically describes and links to the resource(s)
     * created. See Section 7.2 for a discussion of the meaning and purpose of
     * validator header fields, such as ETag and Last-Modified, in a 201
     * response.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.201
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Created = StatusCode\CREATED;

    /**
     * The 202 (Accepted) status code indicates that the request has been
     * accepted for processing, but the processing has not been completed. The
     * request might or might not eventually be acted upon, as it might be
     * disallowed when processing actually takes place. There is no facility in
     * HTTP for re-sending a status code from an asynchronous operation.
     *
     * The 202 response is intentionally noncommittal. Its purpose is to allow a
     * server to accept a request for some other process (perhaps a
     * batch-oriented process that is only run once per day) without requiring
     * that the user agent's connection to the server persist until the process
     * is completed. The representation sent with this response ought to
     * describe the request's current status and point to (or embed) a status
     * monitor that can provide the user with an estimate of when the request
     * will be fulfilled.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.202
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Accepted = StatusCode\ACCEPTED;

    /**
     * The 203 (Non-Authoritative Information) status code indicates that the
     * request was successful but the enclosed payload has been modified from
     * that of the origin server's 200 (OK) response by a transforming proxy
     * (Section 5.7.2 of [RFC7230]). This status code allows the proxy to notify
     * recipients when a transformation has been applied, since that knowledge
     * might impact later decisions regarding the content. For example, future
     * cache validation requests for the content might only be applicable along
     * the same request path (through the same proxies).
     *
     * The 203 response is similar to the Warning code of 214 Transformation
     * Applied (Section 5.5 of [RFC7234]), which has the advantage of being
     * applicable to responses with any status code.
     *
     * A 203 response is cacheable by default; i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see Section 4.2.2 of
     * [RFC7234]).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.203
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface NonAuthoritativeInformation = StatusCode\NON_AUTHORITATIVE_INFORMATION;

    /**
     * The 204 (No Content) status code indicates that the server has
     * successfully fulfilled the request and that there is no additional
     * content to send in the response payload body. Parameter in the response
     * header fields refer to the target resource and its selected
     * representation after the requested action was applied.
     *
     * For example, if a 204 status code is received in response to a PUT
     * request and the response contains an ETag header field, then the PUT was
     * successful and the ETag field-value contains the entity-tag for the new
     * representation of that target resource.
     *
     * The 204 response allows a server to indicate that the action has been
     * successfully applied to the target resource, while implying that the user
     * agent does not need to traverse away from its current "document view"
     * (if any). The server assumes that the user agent will provide some
     * indication of the success to its user, in accord with its own interface,
     * and apply any new or updated metadata in the response to its active
     * representation.
     *
     * For example, a 204 status code is commonly used with document editing
     * interfaces corresponding to a "save" action, such that the document being
     * saved remains available to the user for editing. It is also frequently
     * used with interfaces that expect automated data transfers to be
     * prevalent, such as within distributed version control systems.
     *
     * A 204 response is terminated by the first empty line after the header
     * fields because it cannot contain a message body.
     *
     * A 204 response is cacheable by default; i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see Section 4.2.2 of
     * [RFC7234]).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.204
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface NoContent = StatusCode\NO_CONTENT;

    /**
     * The 205 (Reset Content) status code indicates that the server has
     * fulfilled the request and desires that the user agent reset the "document
     * view", which caused the request to be sent, to its original state as
     * received from the origin server.
     *
     * This response is intended to support a common data entry use case where
     * the user receives content that supports data entry (a form, notepad,
     * canvas, etc.), enters or manipulates data in that space, causes the
     * entered data to be submitted in a request, and then the data entry
     * mechanism is reset for the next entry so that the user can easily
     * initiate another input action.
     *
     * Since the 205 status code implies that no additional content will be
     * provided, a server must not generate a payload in a 205 response. In
     * other words, a server must do one of the following for a 205 response:
     * a) indicate a zero-length body for the response by including a
     * Content-Length header field with a value of 0;
     * b) indicate a zero-length payload for the response by including a
     * Transfer-Encoding header field with a value of chunked and a message body
     * consisting of a single chunk of zero-length; or,
     * c) close the connection immediately after sending the blank line
     * terminating the header section.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.205
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface ResetContent = StatusCode\RESET_CONTENT;

    /**
     * The 206 (Partial Content) status code indicates that the server is
     * successfully fulfilling a range request for the target resource by
     * transferring one or more parts of the selected representation that
     * correspond to the satisfiable ranges found in the request's Range header
     * field (Section 3.1).
     *
     * If a single part is being transferred, the server generating the 206
     * response must generate a Content-Range header field, describing what
     * range of the selected representation is enclosed, and a payload
     * consisting of the range. For example:
     *
     * ```
     *  HTTP/1.1 206 Partial Content
     *  Date: Wed, 15 Nov 1995 06:25:24 GMT
     *  Last-Modified: Wed, 15 Nov 1995 04:58:08 GMT
     *  Content-Range: bytes 21010-47021/47022
     *  Content-Length: 26012
     *  Content-Type: image/gif
     * ```
     *
     * ... 26012 bytes of partial image data ...
     * If multiple parts are being transferred, the server generating the 206
     * response must generate a "multipart/byteranges" payload, as defined in
     * Appendix A, and a Content-Type header field containing the
     * multipart/byteranges media type and its required boundary parameter. To
     * avoid confusion with single-part responses, a server must not generate a
     * Content-Range header field in the HTTP header section of a multiple part
     * response (this field will be sent in each part instead).
     * Within the header area of each body part in the multipart payload, the
     * server must generate a Content-Range header field corresponding to the
     * range being enclosed in that body part. If the selected representation
     * would have had a Content-Type header field in a 200 (OK) response, the
     * server should generate that same Content-Type field in the header area of
     * each body part. For example:
     *
     * ```
     *  HTTP/1.1 206 Partial Content
     *  Date: Wed, 15 Nov 1995 06:25:24 GMT
     *  Last-Modified: Wed, 15 Nov 1995 04:58:08 GMT
     *  Content-Length: 1741
     *  Content-Type: multipart/byteranges; boundary=THIS_STRING_SEPARATES
     *
     *  --THIS_STRING_SEPARATES
     *  Content-Type: application/pdf
     *  Content-Range: bytes 500-999/8000
     *
     *  ...the first range...
     *  --THIS_STRING_SEPARATES
     *  Content-Type: application/pdf
     *  Content-Range: bytes 7000-7999/8000
     *
     *  ...the second range
     *  --THIS_STRING_SEPARATES--
     * ```
     *
     * When multiple ranges are requested, a server may coalesce any of the
     * ranges that overlap, or that are separated by a gap that is smaller than
     * the overhead of sending multiple parts, regardless of the order in which
     * the corresponding byte-range-spec appeared in the received Range header
     * field. Since the typical overhead between parts of a multipart/byteranges
     * payload is around 80 bytes, depending on the selected representation's
     * media type and the chosen boundary parameter length, it can be less
     * efficient to transfer many small disjoint parts than it is to transfer
     * the entire selected representation.
     *
     * A server must not generate a multipart response to a request for a single
     * range, since a client that does not request multiple parts might not
     * support multipart responses. However, a server may generate a
     * multipart/byteranges payload with only a single body part if multiple
     * ranges were requested and only one range was found to be satisfiable or
     * only one range remained after coalescing. A client that cannot process a
     * multipart/byteranges response must not generate a request that asks for
     * multiple ranges.
     *
     * When a multipart response payload is generated, the server should send
     * the parts in the same order that the corresponding byte-range-spec
     * appeared in the received Range header field, excluding those ranges that
     * were deemed unsatisfiable or that were coalesced into other ranges. A
     * client that receives a multipart response must inspect the Content-Range
     * header field present in each body part in order to determine which range
     * is contained in that body part; a client cannot rely on receiving the
     * same ranges that it requested, nor the same order that it requested.
     *
     * When a 206 response is generated, the server must generate the following
     * header fields, in addition to those required above, if the field would
     * have been sent in a 200 (OK) response to the same request: Date,
     * Cache-Control, ETag, Expires, Content-Location, and Vary.
     *
     * If a 206 is generated in response to a request with an If-Range header
     * field, the sender should not generate other representation header fields
     * beyond those required above, because the client is understood to already
     * have a prior response containing those header fields. Otherwise, the
     * sender must generate all of the representation header fields that would
     * have been sent in a 200 (OK) response to the same request.
     *
     * A 206 response is cacheable by default; i.e., unless otherwise indicated
     * by explicit cache controls (see Section 4.2.2 of [RFC7234]).
     *
     * @link https://svn.tools.ietf.org/svn/wg/httpbis/specs/rfc7233.html#status.206
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface PartialContent = StatusCode\PARTIAL_CONTENT;

    /**
     * The message body that follows is an XML message and can contain a
     * number of separate response codes, depending on how many sub-requests
     * were made.
     *
     * Multiple resources were to be affected by the COPY, but errors on some of
     * them prevented the operation from taking place. Specific error messages,
     * together with the most appropriate of the source and destination URLs,
     * appear in the body of the multi-status response. For example, if a
     * destination resource was locked and could not be overwritten, then the
     * destination resource URL appears with the 423 (Locked) status.
     *
     * @link http://www.ietf.org/rfc/rfc4918.txt
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface MultiStatus = StatusCode\MULTI_STATUS;

    /**
     * The members of a DAV binding have already been enumerated in a previous
     * reply to this request, and are not being included again.
     *
     * The 208 (Already Reported) status code can be used inside a DAV: propstat
     * response element to avoid enumerating the internal members of multiple
     * bindings to the same collection repeatedly. For each binding to a
     * collection inside the request's scope, only one will be reported with
     * a 200 status, while subsequent DAV:response elements for all other
     * bindings will use the 208 status, and no DAV:response elements for
     * their descendants are included.
     *
     * @link http://www.ietf.org/rfc/rfc5842.txt
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface AlreadyReported = StatusCode\ALREADY_REPORTED;

    /**
     * This Warning code MUST be added by a proxy if it applies any
     * transformation to the representation, such as changing the
     * content-coding, media-type, or modifying the representation data,
     * unless this Warning code already appears in the response.
     *
     * @link https://tools.ietf.org/html/rfc7234#section-5.5.6
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface TransformationApplied = StatusCode\TRANSFORMATION_APPLIED;

    /**
     * The server has fulfilled a GET request for the resource, and the
     * response is a representation of the result of one or more
     * instance-manipulations applied to the current instance. The actual
     * current instance might not be available except by combining this response
     * with other previous or future responses, as appropriate for the
     * specific instance-manipulation(s).  If so, the headers of the
     * resulting instance are the result of combining the headers from the
     * status-226 response and the other instances, following the rules in
     * section 13.5.3 of the HTTP/1.1 specification [10].
     *
     * The request MUST have included an A-IM header field listing at least
     * one instance-manipulation.  The response MUST include an Etag header
     * field giving the entity tag of the current instance. A response received
     * with a status code of 226 MAY be stored by a cache and used in reply to
     * a subsequent request, subject to the HTTP expiration mechanism and any
     * Cache-Control headers, and to the requirements in section 10.6.
     *
     * A response received with a status code of 226 MAY be used by a cache,
     * in conjunction with a cache entry for the base instance, to create a
     * cache entry for the current instance.
     *
     * @link http://www.ietf.org/rfc/rfc3229.txt
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface ImUsed = StatusCode\IM_USED;

    /**
     * The warning text can include arbitrary information to be presented to
     * a human user or logged. A system receiving this warning MUST NOT
     * take any automated action.
     *
     * @link https://tools.ietf.org/html/rfc7234#section-5.5.7
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface MiscellaneousPersistentWarning = StatusCode\MISCELLANEOUS_PERSISTENT_WARNING;

    /**
     * The 300 (Multiple Choices) status code indicates that the target resource
     * has more than one representation, each with its own more specific
     * identifier, and information about the alternatives is being provided so
     * that the user (or user agent) can select a preferred representation by
     * redirecting its request to one or more of those identifiers. In other
     * words, the server desires that the user agent engage in reactive
     * negotiation to select the most appropriate representation(s) for its
     * needs (Section 3.4).
     *
     * If the server has a preferred choice, the server should generate a
     * Location header field containing a preferred choice's URI reference. The
     * user agent may use the Location field value for automatic redirection.
     *
     * For request methods other than HEAD, the server should generate a payload
     * in the 300 response containing a list of representation metadata and URI
     * reference(s) from which the user or user agent can choose the one most
     * preferred. The user agent may make a selection from that list
     * automatically if it understands the provided media type. A specific
     * format for automatic selection is not defined by this specification
     * because HTTP tries to remain orthogonal to the definition of its
     * payloads. In practice, the representation is provided in some easily
     * parsed format believed to be acceptable to the user agent, as determined
     * by shared design or content negotiation, or in some commonly accepted
     * hypertext format.
     *
     * A 300 response is cacheable by default; i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see Section 4.2.2 of
     * [RFC7234]).
     *
     * Note: The original proposal for the 300 status code defined the URI
     * header field as providing a list of alternative representations, such
     * that it would be usable for 200, 300, and 406 responses and be
     * transferred in responses to the HEAD method. However, lack of deployment
     * and disagreement over syntax led to both URI and Alternates (a subsequent
     * proposal) being dropped from this specification. It is possible to
     * communicate the list using a set of Link header fields [RFC5988], each
     * with a relationship of "alternate", though deployment is a
     * chicken-and-egg problem.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.300
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface MultipleChoices = StatusCode\MULTIPLE_CHOICES;

    /**
     * The 301 (Moved Permanently) status code indicates that the target
     * resource has been assigned a new permanent URI and any future references
     * to this resource ought to use one of the enclosed URIs. Clients with
     * link-editing capabilities ought to automatically re-link references to
     * the effective request URI to one or more of the new references sent by
     * the server, where possible.
     *
     * The server should generate a Location header field in the response
     * containing a preferred URI reference for the new permanent URI. The user
     * agent may use the Location field value for automatic redirection. The
     * server's response payload usually contains a short hypertext note with a
     * hyperlink to the new URI(s).
     *
     * Note: For historical reasons, a user agent may change the request method
     * from POST to GET for the subsequent request. If this behavior is
     * undesired, the 307 (Temporary Redirect) status code can be used instead.
     *
     * A 301 response is cacheable by default; i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see Section 4.2.2 of
     * [RFC7234]).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.301
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface MovedPermanently = StatusCode\MOVED_PERMANENTLY;

    /**
     * The 302 (Found) status code indicates that the target resource resides
     * temporarily under a different URI. Since the redirection might be altered
     * on occasion, the client ought to continue to use the effective request
     * URI for future requests.
     *
     * The server should generate a Location header field in the response
     * containing a URI reference for the different URI. The user agent may use
     * the Location field value for automatic redirection. The server's response
     * payload usually contains a short hypertext note with a hyperlink to the
     * different URI(s).
     *
     * Note: For historical reasons, a user agent may change the request method
     * from POST to GET for the subsequent request. If this behavior is
     * undesired, the 307 (Temporary Redirect) status code can be used instead.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.302
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Found = StatusCode\FOUND;

    /**
     * The 303 (See Other) status code indicates that the server is redirecting
     * the user agent to a different resource, as indicated by a URI in the
     * Location header field, which is intended to provide an indirect response
     * to the original request. A user agent can perform a retrieval request
     * targeting that URI (a GET or HEAD request if using HTTP), which might
     * also be redirected, and present the eventual result as an answer to the
     * original request. Note that the new URI in the Location header field is
     * not considered equivalent to the effective request URI.
     *
     * This status code is applicable to any HTTP method. It is primarily used
     * to allow the output of a POST action to redirect the user agent to a
     * selected resource, since doing so provides the information corresponding
     * to the POST response in a form that can be separately identified,
     * bookmarked, and cached, independent of the original request.
     *
     * A 303 response to a GET request indicates that the origin server does not
     * have a representation of the target resource that can be transferred by
     * the server over HTTP. However, the Location field value refers to a
     * resource that is descriptive of the target resource, such that making a
     * retrieval request on that other resource might result in a representation
     * that is useful to recipients without implying that it represents the
     * original target resource. Note that answers to the questions of what can
     * be represented, what representations are adequate, and what might be a
     * useful description are outside the scope of HTTP.
     *
     * Except for responses to a HEAD request, the representation of a 303
     * response ought to contain a short hypertext note with a hyperlink to the
     * same URI reference provided in the Location header field.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.303
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface SeeOther = StatusCode\SEE_OTHER;

    /**
     * The 304 (Not Modified) status code indicates that a conditional GET or
     * HEAD request has been received and would have resulted in a 200 (OK)
     * response if it were not for the fact that the condition evaluated to
     * false. In other words, there is no need for the server to transfer a
     * representation of the target resource because the request indicates that
     * the client, which made the request conditional, already has a valid
     * representation; the server is therefore redirecting the client to make
     * use of that stored representation as if it were the payload of a 200 (OK)
     * response.
     *
     * The server generating a 304 response must generate any of the following
     * header fields that would have been sent in a 200 (OK) response to the
     * same request: Cache-Control, Content-Location, Date, ETag, Expires, and
     * Vary.
     *
     * Since the goal of a 304 response is to minimize information transfer when
     * the recipient already has one or more cached representations, a sender
     * should not generate representation metadata other than the above listed
     * fields unless said metadata exists for the purpose of guiding cache
     * updates (e.g., Last-Modified might be useful if the response does not
     * have an ETag field).
     *
     * Requirements on a cache that receives a 304 response are defined in
     * Section 4.3.4 of [RFC7234]. If the conditional request originated with an
     * outbound client, such as a user agent with its own cache sending a
     * conditional GET to a shared proxy, then the proxy should forward the 304
     * response to that client.
     *
     * A 304 response cannot contain a message-body; it is always terminated by
     * the first empty line after the header fields.
     *
     * @link https://svn.tools.ietf.org/svn/wg/httpbis/specs/rfc7232.html#status.304
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface NotModified = StatusCode\NOT_MODIFIED;

    /**
     * The 305 (Use Proxy) status code was defined in a previous version of this
     * specification and is now deprecated
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.305
     *
     * @deprecated
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface UseProxy = StatusCode\USE_PROXY;

    /**
     * The 306 (Unused) status code was defined in a previous version of this
     * specification, is no longer used, and the code is reserved.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.306
     *
     * @deprecated
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Unused = StatusCode\UNUSED;

    /**
     * The 307 (Temporary Redirect) status code indicates that the target
     * resource resides temporarily under a different URI and the user agent
     * must not change the request method if it performs an automatic
     * redirection to that URI. Since the redirection can change over time, the
     * client ought to continue using the original effective request URI for
     * future requests.
     *
     * The server should generate a Location header field in the response
     * containing a URI reference for the different URI. The user agent may use
     * the Location field value for automatic redirection. The server's response
     * payload usually contains a short hypertext note with a hyperlink to the
     * different URI(s).
     *
     * Note: This status code is similar to 302 (Found), except that it does not
     * allow changing the request method from POST to GET. This specification
     * defines no equivalent counterpart for 301 (Moved Permanently) ([RFC7238],
     * however, defines the status code 308 (Permanent Redirect) for this
     * purpose).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.307
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface TemporaryRedirect = StatusCode\TEMPORARY_REDIRECT;

    /**
     * The 308 (Permanent Redirect) status code indicates that the target
     * resource has been assigned a new permanent URI and any future references
     * to this resource ought to use one of the enclosed URIs. Clients with link
     * editing capabilities ought to automatically re-link references to the
     * effective request URI (Section 5.5 of [RFC7230]) to one or more of the
     * new references sent by the server, where possible.
     *
     * The server should generate a Location header field ([RFC7231], Section
     * 7.1.2) in the response containing a preferred URI reference for the new
     * permanent URI. The user agent may use the Location field value for
     * automatic redirection. The server's response payload usually contains a
     * short hypertext note with a hyperlink to the new URI(s).
     *
     * A 308 response is cacheable by default; i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see [RFC7234],
     * Section 4.2.2).
     *
     * Note: This status code is similar to 301 (Moved Permanently) ([RFC7231],
     * Section 6.4.2), except that it does not allow changing the request method
     * from POST to GET.
     *
     * @link https://svn.tools.ietf.org/svn/wg/httpbis/specs/rfc7538.html#status.308
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface PermanentRedirect = StatusCode\PERMANENT_REDIRECT;

    /**
     * The 400 (Bad Request) status code indicates that the server cannot or
     * will not process the request due to something that is perceived to be a
     * client error (e.g., malformed request syntax, invalid request message
     * framing, or deceptive request routing).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.400
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface BadRequest = StatusCode\BAD_REQUEST;

    /**
     * The 401 (Unauthorized) status code indicates that the request has not
     * been applied because it lacks valid authentication credentials for the
     * target resource. The server generating a 401 response must send a
     * WWW-Authenticate header field (Section 4.1) containing at least one
     * challenge applicable to the target resource.
     *
     * If the request included authentication credentials, then the 401 response
     * indicates that authorization has been refused for those credentials. The
     * user agent may repeat the request with a new or replaced Authorization
     * header field (Section 4.2). If the 401 response contains the same
     * challenge as the prior response, and the user agent has already attempted
     * authentication at least once, then the user agent should present the
     * enclosed representation to the user, since it usually contains relevant
     * diagnostic information.
     *
     * @link https://svn.tools.ietf.org/svn/wg/httpbis/specs/rfc7235.html#status.401
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Unauthorized = StatusCode\UNAUTHORIZED;

    /**
     * The 402 (Payment Required) status code is reserved for future use.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.402
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface PaymentRequired = StatusCode\PAYMENT_REQUIRED;

    /**
     * The 403 (Forbidden) status code indicates that the server understood the
     * request but refuses to authorize it. A server that wishes to make public
     * why the request has been forbidden can describe that reason in the
     * response payload (if any).
     *
     * If authentication credentials were provided in the request, the server
     * considers them insufficient to grant access. The client should not
     * automatically repeat the request with the same credentials. The client
     * may repeat the request with new or different credentials. However, a
     * request might be forbidden for reasons unrelated to the credentials.
     *
     * An origin server that wishes to "hide" the current existence of a
     * forbidden target resource may instead respond with a status code of 404
     * (Not Found).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.403
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Forbidden = StatusCode\FORBIDDEN;

    /**
     * The 404 (Not Found) status code indicates that the origin server did not
     * find a current representation for the target resource or is not willing
     * to disclose that one exists. A 404 status code does not indicate whether
     * this lack of representation is temporary or permanent; the 410 (Gone)
     * status code is preferred over 404 if the origin server knows, presumably
     * through some configurable means, that the condition is likely to be
     * permanent.
     *
     * A 404 response is cacheable by default; i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see Section 4.2.2 of
     * [RFC7234]).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.404
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface NotFound = StatusCode\NOT_FOUND;

    /**
     * The 405 (Method Not Allowed) status code indicates that the method
     * received in the request-line is known by the origin server but not
     * supported by the target resource. The origin server must generate an
     * Allow header field in a 405 response containing a list of the target
     * resource's currently supported methods.
     *
     * A 405 response is cacheable by default; i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see Section 4.2.2 of
     * [RFC7234]).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.405
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface MethodNotAllowed = StatusCode\METHOD_NOT_ALLOWED;

    /**
     * The 406 (Not Acceptable) status code indicates that the target resource
     * does not have a current representation that would be acceptable to the
     * user agent, according to the proactive negotiation header fields
     * received in the request (Section 5.3), and the server is unwilling to
     * supply a default representation.
     *
     * The server should generate a payload containing a list of available
     * representation characteristics and corresponding resource identifiers
     * from which the user or user agent can choose the one most appropriate. A
     * user agent may automatically select the most appropriate choice from
     * that list. However, this specification does not define any standard for
     * such automatic selection, as described in Section 6.4.1.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.406
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface NotAcceptable = StatusCode\NOT_ACCEPTABLE;

    /**
     * The 407 (Proxy Authentication Required) status code is similar to 401
     * (Unauthorized), but it indicates that the client needs to authenticate
     * itself in order to use a proxy. The proxy must send a Proxy-Authenticate
     * header field (Section 4.3) containing a challenge applicable to that
     * proxy for the target resource. The client may repeat the request with a
     * new or replaced Proxy-Authorization header field (Section 4.4).
     *
     * @link https://svn.tools.ietf.org/svn/wg/httpbis/specs/rfc7235.html#status.407
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface ProxyAuthenticationRequired = StatusCode\PROXY_AUTHENTICATION_REQUIRED;

    /**
     * The 408 (Request Timeout) status code indicates that the server did not
     * receive a complete request message within the time that it was prepared
     * to wait. A server should send the "close" connection option (Section
     * 6.1 of [RFC7230]) in the response, since 408 implies that the server
     * has decided to close the connection rather than continue waiting. If
     * the client has an outstanding request in transit, the client may repeat
     * that request on a new connection.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.408
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface RequestTimeout = StatusCode\REQUEST_TIMEOUT;

    /**
     * The 409 (Conflict) status code indicates that the request could not be
     * completed due to a conflict with the current state of the target
     * resource. This code is used in situations where the user might be able to
     * resolve the conflict and resubmit the request. The server should generate
     * a payload that includes enough information for a user to recognize the
     * source of the conflict.
     *
     * Conflicts are most likely to occur in response to a PUT request. For
     * example, if versioning were being used and the representation being PUT
     * included changes to a resource that conflict with those made by an
     * earlier (third-party) request, the origin server might use a 409 response
     * to indicate that it can't complete the request. In this case, the
     * response representation would likely contain information useful for
     * merging the differences based on the revision history.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.409
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Conflict = StatusCode\CONFLICT;

    /**
     * The 410 (Gone) status code indicates that access to the target resource
     * is no longer available at the origin server and that this condition is
     * likely to be permanent. If the origin server does not know, or has no
     * facility to determine, whether or not the condition is permanent, the
     * status code 404 (Not Found) ought to be used instead.
     *
     * The 410 response is primarily intended to assist the task of web
     * maintenance by notifying the recipient that the resource is intentionally
     * unavailable and that the server owners desire that remote links to that
     * resource be removed. Such an event is common for limited-time,
     * promotional services and for resources belonging to individuals no longer
     * associated with the origin server's site. It is not necessary to mark all
     * permanently unavailable resources as "gone" or to keep the mark for any
     * length of time — that is left to the discretion of the server owner.
     *
     * A 410 response is cacheable by default; i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see Section 4.2.2 of
     * [RFC7234]).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.410
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Gone = StatusCode\GONE;

    /**
     * The 411 (Length Required) status code indicates that the server refuses
     * to accept the request without a defined Content-Length (Section 3.3.2 of
     * [RFC7230]). The client may repeat the request if it adds a valid
     * Content-Length header field containing the length of the message body in
     * the request message.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.411
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface LengthRequired = StatusCode\LENGTH_REQUIRED;

    /**
     * The 412 (Precondition Failed) status code indicates that one or more
     * conditions given in the request header fields evaluated to false when
     * tested on the server. This response code allows the client to place
     * preconditions on the current resource state (its current representations
     * and metadata) and, thus, prevent the request method from being applied if
     * the target resource is in an unexpected state.
     *
     * @link https://svn.tools.ietf.org/svn/wg/httpbis/specs/rfc7232.html#status.412
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface PreconditionFailed = StatusCode\PRECONDITION_FAILED;

    /**
     * The 413 (Payload Too Large) status code indicates that the server is
     * refusing to process a request because the request payload is larger than
     * the server is willing or able to process. The server may close the
     * connection to prevent the client from continuing the request.
     *
     * If the condition is temporary, the server should generate a Retry-After
     * header field to indicate that it is temporary and after what time the
     * client may try again.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.413
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface PayloadTooLarge = StatusCode\PAYLOAD_TOO_LARGE;

    /**
     * The 414 (URI Too Long) status code indicates that the server is
     * refusing to service the request because the request-target (Section 5.3
     * of [RFC7230]) is longer than the server is willing to interpret. This
     * rare condition is only likely to occur when a client has improperly
     * converted a POST request to a GET request with long query information,
     * when the client has descended into a "black hole" of redirection (e.g.,
     * a redirected URI prefix that points to a suffix of itself) or when the
     * server is under attack by a client attempting to exploit potential
     * security holes.
     *
     * A 414 response is cacheable by default; i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see Section 4.2.2
     * of [RFC7234]).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.414
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface UriTooLong = StatusCode\URI_TOO_LONG;

    /**
     * The 415 (Unsupported Media Type) status code indicates that the origin
     * server is refusing to service the request because the payload is in a
     * format not supported by this method on the target resource. The format
     * problem might be due to the request's indicated Content-Type or
     * Content-Encoding, or as a result of inspecting the data directly.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.415
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface UnsupportedMediaType = StatusCode\UNSUPPORTED_MEDIA_TYPE;

    /**
     * The 416 (Range Not Satisfiable) status code indicates that none of the
     * ranges in the request's Range header field (Section 3.1) overlap the
     * current extent of the selected resource or that the set of ranges
     * requested has been rejected due to invalid ranges or an excessive request
     * of small or overlapping ranges.
     *
     * For byte ranges, failing to overlap the current extent means that the
     * first-byte-pos of all of the byte-range-spec values were greater than the
     * current length of the selected representation. When this status code is
     * generated in response to a byte-range request, the sender should generate
     * a Content-Range header field specifying the current length of the selected
     * representation (Section 4.2).
     *
     * Note: Because servers are free to ignore Range, many implementations will
     * simply respond with the entire selected representation in a 200 (OK)
     * response. That is partly because most clients are prepared to receive a
     * 200 (OK) to complete the task (albeit less efficiently) and partly because
     * clients might not stop making an invalid partial request until they have
     * received a complete representation. Thus, clients cannot depend on
     * receiving a 416 (Range Not Satisfiable) response even when it is most
     * appropriate.
     *
     * @link https://svn.tools.ietf.org/svn/wg/httpbis/specs/rfc7233.html#status.416
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface RangeNotSatisfiable = StatusCode\RANGE_NOT_SATISFIABLE;

    /**
     * The 417 (Expectation Failed) status code indicates that the expectation
     * given in the request's Expect header field (Section 5.1.1) could not be
     * met by at least one of the inbound servers.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.417
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface ExpectationFailed = StatusCode\EXPECTATION_FAILED;

    /**
     * TEA-capable pots that are not provisioned to brew coffee may return
     * either a status code of 503, indicating temporary unavailability of
     * coffee, or a code of 418 as defined in the base HTCPCP specification
     * to denote a more permanent indication that the pot is a teapot.
     *
     * @link https://www.rfc-editor.org/rfc/rfc7168.txt
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface ImATeapot = StatusCode\IM_A_TEAPOT;

    /**
     * The 421 (Misdirected Request) status code indicates that the request was
     * directed at a server that is not able to produce a response. This can be
     * sent by a server that is not configured to produce responses for the
     * combination of scheme and authority that are included in the request URI.
     *
     * Clients receiving a 421 (Misdirected Request) response from a server MAY
     * retry the request — whether the request method is idempotent or not —
     * over a different connection. This is possible if a connection is reused
     * (Section 9.1.1) or if an alternative service is selected [ALT-SVC].
     *
     * This status code MUST NOT be generated by proxies.
     *
     * A 421 response is cacheable by default, i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see Section 4.2.2 of
     * [RFC7234]).
     *
     * @link https://http2.github.io/http2-spec/#MisdirectedRequest
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface MisdirectedRequest = StatusCode\MISDIRECTED_REQUEST;

    /**
     * The 422 (Unprocessable Entity) status code means the server understands
     * the content type of the request entity (hence a
     * 415[Unsupported Media Type] status code is inappropriate), and the
     * syntax of the request entity is correct (thus a 400 (Bad Request)
     * status code is inappropriate) but was unable to process the contained
     * instructions. For example, this error condition may occur if an XML
     * request body contains well-formed (i.e., syntactically correct), but
     * semantically erroneous, XML instructions.
     *
     * @link http://www.ietf.org/rfc/rfc4918.txt
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface UnprocessableEntity = StatusCode\UNPROCESSABLE_ENTITY;

    /**
     * The 423 (Locked) status code means the source or destination resource
     * of a method is locked. This response SHOULD contain an appropriate
     * precondition or post-condition code, such as 'lock-token-submitted' or
     * 'no-conflicting-lock'.
     *
     * @link http://www.ietf.org/rfc/rfc4918.txt
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface EntityLocked = StatusCode\ENTITY_LOCKED;

    /**
     * The 424 (Failed Dependency) status code means that the method could not
     * be performed on the resource because the requested action
     * depended on another action and that action failed. For example, if a
     * command in a PROPPATCH method fails then, at minimum, the rest
     * of the commands will also fail with 424 (Failed Dependency).
     *
     * @link http://www.ietf.org/rfc/rfc4918.txt
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface FailedDependency = StatusCode\FAILED_DEPENDENCY;

    /**
     * A 425 (Too Early) status code indicates that the server is unwilling
     * to risk processing a request that might be replayed.
     *
     * User agents that send a request in early data are expected to retry
     * the request when receiving a 425 (Too Early) response status code. A
     * user agent MAY do so automatically, but any retries MUST NOT be sent
     * in early data.
     *
     * In all cases, an intermediary can forward a 425 (Too Early) status
     * code. Intermediaries MUST forward a 425 (Too Early) status code if
     * the request that it received and forwarded contained an "Early-Data"
     * header field. Otherwise, an intermediary that receives a request in
     * early data MAY automatically retry that request in response to a 425
     * (Too Early) status code, but it MUST wait for the TLS handshake to
     * complete on the connection where it received the request.
     *
     * The server cannot assume that a client is able to retry a request
     * unless the request is received in early data or the "Early-Data"
     * header field is set to "1". A server SHOULD NOT emit the 425 status
     * code unless one of these conditions is met.
     *
     * The 425 (Too Early) status code is not cacheable by default. Its
     * payload is not the representation of any identified resource.
     *
     * @link https://datatracker.ietf.org/doc/html/draft-ietf-httpbis-replay-04#section-5.2
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface HttpTooEarly = StatusCode\HTTP_TOO_EARLY;

    /**
     * The 426 (Upgrade Required) status code indicates that the server refuses
     * to perform the request using the current protocol but might be willing to
     * do so after the client upgrades to a different protocol. The server must
     * send an Upgrade header field in a 426 response to indicate the required
     * protocol(s) (Section 6.7 of [RFC7230]).
     *
     * Example:
     *
     * ```
     *  HTTP/1.1 426 Upgrade Required
     *  Upgrade: HTTP/3.0
     *  Connection: Upgrade
     *  Content-Length: 53
     *  Content-Type: text/plain
     * ```
     *
     * This service requires use of the HTTP/3.0 protocol.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.426
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface UpgradeRequired = StatusCode\UPGRADE_REQUIRED;

    /**
     * The origin server requires the request to be conditional. Its typical
     * use is to avoid the "lost update" problem, where a client GETs a
     * resource's state, modifies it, and PUTs it back to the server, when
     * meanwhile a third party has modified the state on the server, leading to
     * a conflict. By requiring requests to be conditional, the server can
     * assure that clients are working with the correct copies.
     *
     * Responses using this status code SHOULD explain how to resubmit the
     * request successfully.
     *
     * Responses with the 428 status code MUST NOT be stored by a cache.
     *
     * @link http://tools.ietf.org/html/rfc6585
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface PreconditionRequired = StatusCode\PRECONDITION_REQUIRED;

    /**
     * The 429 status code indicates that the user has sent too many requests
     * in a given amount of time ("rate limiting").
     *
     * The response representations SHOULD include details explaining the
     * condition, and MAY include a Retry-After header indicating how long
     * to wait before making a new request.
     *
     * For example:
     *
     * ```
     *  HTTP/1.1 429 Too Many Requests
     *  Content-Type: text/html
     *  Retry-After: 3600
     *  <html>
     *   <head>
     *    <title>Too Many Requests</title>
     *   </head>
     *   <body>
     *    <h1>Too Many Requests</h1>
     *    <p>I only allow 50 requests per hour to this Web site per
     *    logged in user. Try again soon.</p>
     *   </body>
     *  </html>
     * ```
     *
     * Note that this specification does not define how the origin server
     * identifies the user, nor how it counts requests. For example, an
     * origin server that is limiting request rates can do so based upon
     * counts of requests on a per-resource basis, across the entire server,
     * or even among a set of servers. Likewise, it might identify the user
     * by its authentication credentials, or a stateful cookie.
     *
     * Responses with the 429 status code MUST NOT be stored by a cache.
     *
     * @link http://tools.ietf.org/html/rfc6585
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface TooManyRequests = StatusCode\TOO_MANY_REQUESTS;

    /**
     * The 431 status code indicates that the server is unwilling to process
     * the request because its header fields are too large. The request MAY
     * be resubmitted after reducing the size of the request header fields.
     *
     * It can be used both when the set of request header fields in total is
     * too large, and when a single header field is at fault. In the latter
     * case, the response representation SHOULD specify which header field
     * was too large.
     *
     * @link http://tools.ietf.org/html/rfc6585
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface RequestHeaderFieldsTooLarge = StatusCode\REQUEST_HEADER_FIELDS_TOO_LARGE;

    /**
     * Used internally to instruct the server to return no information to the
     * client and close the connection immediately.
     *
     * @link https://www.nginx.com/resources/wiki/extending/api/http/
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface Close = StatusCode\CLOSE;

    /**
     * This status code indicates that the server is subject to legal
     * restrictions which prevent it servicing the request.
     *
     * Since such restrictions typically apply to all operators in a legal
     * jurisdiction, the server in question may or may not be an origin
     * server. The restrictions typically most directly affect the
     * operations of ISPs and search engines.
     *
     * Responses using this status code SHOULD include an explanation, in
     * the response body, of the details of the legal restriction; which
     * legal authority is imposing it, and what class of resources it
     * applies to.
     *
     * @link http://tools.ietf.org/html/draft-tbray-http-legally-restricted-status-00#section-3
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface UnavailableForLegalReasons = StatusCode\UNAVAILABLE_FOR_LEGAL_REASONS;

    /**
     * Used when the client has closed the request before the server could send
     * a response.
     *
     * @link https://www.nginx.com/resources/wiki/extending/api/http/
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface ClientClosedRequest = StatusCode\CLIENT_CLOSED_REQUEST;

    /**
     * The 500 (Internal Server Error) status code indicates that the server
     * encountered an unexpected condition that prevented it from fulfilling the
     * request.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.500
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface InternalServerError = StatusCode\INTERNAL_SERVER_ERROR;

    /**
     * The 501 (Not Implemented) status code indicates that the server does not
     * support the functionality required to fulfill the request. This is the
     * appropriate response when the server does not recognize the request
     * method and is not capable of supporting it for any resource.
     *
     * A 501 response is cacheable by default; i.e., unless otherwise indicated
     * by the method definition or explicit cache controls (see Section 4.2.2 of
     * [RFC7234]).
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.501
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface NotImplemented = StatusCode\NOT_IMPLEMENTED;

    /**
     * The 502 (Bad Gateway) status code indicates that the server, while acting
     * as a gateway or proxy, received an invalid response from an inbound
     * server it accessed while attempting to fulfill the request.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.502
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface BadGateway = StatusCode\BAD_GATEWAY;

    /**
     * The 503 (Service Unavailable) status code indicates that the server is
     * currently unable to handle the request due to a temporary overload or
     * scheduled maintenance, which will likely be alleviated after some delay.
     * The server may send a Retry-After header field (Section 7.1.3) to suggest
     * an appropriate amount of time for the client to wait before retrying the
     * request.
     *
     * Note: The existence of the 503 status code does not imply that a server
     * has to use it when becoming overloaded. Some servers might simply refuse
     * the connection.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.503
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface ServiceUnavailable = StatusCode\SERVICE_UNAVAILABLE;

    /**
     * The 504 (Gateway Timeout) status code indicates that the server, while
     * acting as a gateway or proxy, did not receive a timely response from an
     * upstream server it needed to access in order to complete the request.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.504
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface GatewayTimeout = StatusCode\GATEWAY_TIMEOUT;

    /**
     * The 505 (HTTP Version Not Supported) status code indicates that the
     * server does not support, or refuses to support, the major version of HTTP
     * that was used in the request message. The server is indicating that it is
     * unable or unwilling to complete the request using the same major version
     * as the client, as described in Section 2.6 of [RFC7230], other than with
     * this error message. The server should generate a representation for the
     * 505 response that describes why that version is not supported and what
     * other protocols are supported by that server.
     *
     * @link https://datatracker.ietf.org/doc/html/rfc7231#status.505
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface HttpVersionNotSupported = StatusCode\HTTP_VERSION_NOT_SUPPORTED;

    /**
     * Transparent content negotiation for the request results in a circular
     * reference.
     *
     * @link http://tools.ietf.org/search/rfc2295#section-8.1
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface HttpVariantAlsoNegotiates = StatusCode\HTTP_VARIANT_ALSO_NEGOTIATES;

    /**
     * The server is unable to store the representation needed to complete the
     * request.
     *
     * @link http://www.ietf.org/rfc/rfc4918.txt
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface HttpInsufficientStorage = StatusCode\HTTP_INSUFFICIENT_STORAGE;

    /**
     * The 508 (Loop Detected) status code indicates that the server detected
     * an infinite loop while processing a request with "Depth: infinity".
     * (sent in lieu of 208).
     *
     * @link https://tools.ietf.org/html/draft-ietf-webdav-collection-protocol-04#section-7.1
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface HttpLoopDetected = StatusCode\HTTP_LOOP_DETECTED;

    /**
     * The policy for accessing the resource has not been met in the
     * request. The server should send back all the information necessary
     * for the client to issue an extended request. It is outside the scope
     * of this specification to specify how the extensions inform the client.
     *
     * If the 510 response contains information about extensions that were
     * not present in the initial request then the client MAY repeat the
     * request if it has reason to believe it can fulfill the extension
     * policy by modifying the request according to the information provided
     * in the 510 response. Otherwise the client MAY present any entity
     * included in the 510 response to the user, since that entity may
     * include relevant diagnostic information.
     *
     * @link http://tools.ietf.org/search/rfc2774#section-7
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface HttpNotExtended = StatusCode\HTTP_NOT_EXTENDED;

    /**
     * The 511 status code is designed to mitigate problems caused by
     * "captive portals" to software (especially non-browser agents) that is
     * expecting a response from the server that a request was made to, not
     * the intervening network infrastructure. It is not intended to
     * encourage deployment of captive portals -- only to limit the damage
     * caused by them.
     *
     * A network operator wishing to require some authentication, acceptance
     * of terms, or other user interaction before granting access usually
     * does so by identifying clients who have not done so ("unknown
     * clients") using their Media Access Control (MAC) addresses.
     *
     * Unknown clients then have all traffic blocked, except for that on TCP
     * port 80, which is sent to an HTTP server (the "login server")
     * dedicated to "logging in" unknown clients, and of course traffic to
     * the login server itself.
     *
     * @link http://tools.ietf.org/html/rfc6585
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface HttpNetworkAuthenticationRequired = StatusCode\HTTP_NETWORK_AUTHENTICATION_REQUIRED;

    /**
     * This status code is not specified in any RFCs but is used by some HTTP
     * proxies to signal a network connect timeout behind the proxy to a client
     * in front of the proxy.
     *
     * @noinspection PhpUndefinedConstantInspection
     */
    public const StatusCodeInterface NetworkConnectTimeout = StatusCode\NETWORK_CONNECT_TIMEOUT;

    /**
     * @var non-empty-array<int<100, 599>, StatusCodeInterface>
     */
    private const array CASES = [
        100 => self::Continue,
        101 => self::SwitchingProtocols,
        102 => self::Processing,
        103 => self::EarlyHints,
        110 => self::ResponseIsStale,
        111 => self::RevalidationFailed,
        112 => self::DisconnectedOperation,
        113 => self::HeuristicExpiration,
        199 => self::MiscellaneousWarning,
        200 => self::Ok,
        201 => self::Created,
        202 => self::Accepted,
        203 => self::NonAuthoritativeInformation,
        204 => self::NoContent,
        205 => self::ResetContent,
        206 => self::PartialContent,
        207 => self::MultiStatus,
        208 => self::AlreadyReported,
        214 => self::TransformationApplied,
        226 => self::ImUsed,
        299 => self::MiscellaneousPersistentWarning,
        300 => self::MultipleChoices,
        301 => self::MovedPermanently,
        302 => self::Found,
        303 => self::SeeOther,
        304 => self::NotModified,
        305 => self::UseProxy,
        306 => self::Unused,
        307 => self::TemporaryRedirect,
        308 => self::PermanentRedirect,
        400 => self::BadRequest,
        401 => self::Unauthorized,
        402 => self::PaymentRequired,
        403 => self::Forbidden,
        404 => self::NotFound,
        405 => self::MethodNotAllowed,
        406 => self::NotAcceptable,
        407 => self::ProxyAuthenticationRequired,
        408 => self::RequestTimeout,
        409 => self::Conflict,
        410 => self::Gone,
        411 => self::LengthRequired,
        412 => self::PreconditionFailed,
        413 => self::PayloadTooLarge,
        414 => self::UriTooLong,
        415 => self::UnsupportedMediaType,
        416 => self::RangeNotSatisfiable,
        417 => self::ExpectationFailed,
        418 => self::ImATeapot,
        421 => self::MisdirectedRequest,
        422 => self::UnprocessableEntity,
        423 => self::EntityLocked,
        424 => self::FailedDependency,
        425 => self::HttpTooEarly,
        426 => self::UpgradeRequired,
        428 => self::PreconditionRequired,
        429 => self::TooManyRequests,
        431 => self::RequestHeaderFieldsTooLarge,
        444 => self::Close,
        451 => self::UnavailableForLegalReasons,
        499 => self::ClientClosedRequest,
        500 => self::InternalServerError,
        501 => self::NotImplemented,
        502 => self::BadGateway,
        503 => self::ServiceUnavailable,
        504 => self::GatewayTimeout,
        505 => self::HttpVersionNotSupported,
        506 => self::HttpVariantAlsoNegotiates,
        507 => self::HttpInsufficientStorage,
        508 => self::HttpLoopDetected,
        510 => self::HttpNotExtended,
        511 => self::HttpNetworkAuthenticationRequired,
        599 => self::NetworkConnectTimeout,
    ];

    /**
     * Translates a string value into the corresponding {@see StatusCode}
     * case, if any. If there is no matching case defined,
     * it will return {@see null}.
     *
     * @api
     *
     * @param int<100, 599> $value
     */
    public static function tryFrom(int $value): ?StatusCodeInterface
    {
        return self::CASES[$value] ?? null;
    }

    /**
     * Translates a string value into the corresponding {@see StatusCode}
     * case, if any. If there is no matching case defined,
     * it will throw {@see \ValueError}.
     *
     * @api
     *
     * @param int<100, 599> $value
     *
     * @throws \ValueError if there is no matching case defined
     */
    public static function from(int $value): StatusCodeInterface
    {
        return self::tryFrom($value)
            ?? throw new \ValueError(\sprintf(
                '"%s" is not a valid backing value for enum-like %s',
                $value,
                self::class,
            ));
    }

    /**
     * Return a packed {@see array} of all cases in an enumeration,
     * in order of declaration.
     *
     * @api
     *
     * @return non-empty-list<StatusCodeInterface>
     */
    public static function cases(): array
    {
        return \array_values(self::CASES);
    }
}

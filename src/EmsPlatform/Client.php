<?php

namespace EmsPlatform;

use GuzzleHttp\Client as GuzzleHttpClient;

/**
 * @author elwingert
 */
class Client
{
    private $baseUri;
    private $clientToken;
    private $debugMode = false;
    private $guzzleClient;

    private $defaultPageSize;

    public function __construct($baseUri, $clientId = '', $secret = '')
    {
        $this->baseUri = $baseUri;
        $this->guzzleClient = new GuzzleHttpClient(['base_uri' => $baseUri]);

        if ($clientId != '' && $secret != '') {
            $this->clientAuthentication($clientId, $secret);
        }
    }

    /**
     * @param string $clientId
     * @param string $secret
     */
    public function clientAuthentication(string $clientId, string $secret)
    {
        $this->clientToken = $this->request('POST', 'clientauthentication', ['clientId' => $clientId, 'secret' => $secret])->clientToken;
    }

    /**
     * Return a list of Areas filtered by optional query string parameters.
     *
     * @param int    $page
     * @param int    $pageSize
     * @param string $searchText
     *
     * @return mixed
     */
    public function getAreas(int $page = null, int $pageSize = null, string $searchText = '')
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('GET', 'areas', $parameters);
    }

    /**
     * Return a Booking, given its id.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getBooking(int $id)
    {
        return $this->request('GET', 'bookings/'.$id);
    }

    /**
     * Return a list of Bookings, filtered by optional query string parameters and sorted by eventStartTime ascending.
     *
     * @param int    $webUserId
     * @param int    $page
     * @param int    $pageSize
     * @param string $minReserveStartTime
     * @param string $maxReserveStartTime
     * @param string $originalEventStartTime
     * @param int    $roomId
     * @param int    $buildingId
     * @param int    $statusId
     * @param int    $roomTypeId
     * @param int    $floorId
     * @param int    $eventTypeId
     * @param int    $reservationId
     * @param string $searchText
     * @param bool   $includeCancelled
     * @param bool   $userBookingsOnly
     * @param bool   $includeComponentRooms
     * @param bool   $excludeOverrideDescriptionRooms
     * @param string $udf
     * @param int    $kioskProfileId
     *
     * @return mixed
     */
    public function getBookings(int $webUserId, int $page = null, int $pageSize = null, string $minReserveStartTime = '', string $maxReserveStartTime = '', string $originalEventStartTime = '', int $roomId = null, int $buildingId = null, int $statusId = null, int $roomTypeId = null, int $floorId = null, int $eventTypeId = null, int $reservationId = null, string $searchText = '', bool $includeCancelled = null, bool $userBookingsOnly = null, bool $includeComponentRooms = null, bool $excludeOverrideDescriptionRooms = null, string $udf = '', int $kioskProfileId = null)
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('GET', 'bookings', $parameters);
    }

    /**
     * Return a list of buildings, filtered by optional query string parameters.
     *
     * @param int    $page
     * @param int    $pageSize
     * @param string $searchText
     *
     * @return mixed
     */
    public function getBuildings(int $page = null, int $pageSize = null, string $searchText = '')
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('GET', 'buildings', $parameters);
    }

    /**
     * Return a list of categories, filtered by optional query string parameters.
     *
     * @param int    $page
     * @param int    $pageSize
     * @param string $searchText
     *
     * @return mixed
     */
    public function getCategories(int $page = null, int $pageSize = null, string $searchText = '')
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('GET', 'categories', $parameters);
    }

    /**
     * Return all contacts, paged based on query string attributes.
     *
     * @param int    $page
     * @param int    $pageSize
     * @param int    $groupId
     * @param string $searchText
     *
     * @return mixed
     */
    public function getContacts(int $page = null, int $pageSize = null, int $groupId = null, string $searchText = '')
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('GET', 'contacts', $parameters);
    }

    /**
     * Returns a list of departments, filtered by optional query string parameters.
     *
     * @param int    $page
     * @param int    $pageSize
     * @param bool   $active
     * @param string $searchText
     *
     * @return mixed
     */
    public function getDepartments(int $page = null, int $pageSize = null, bool $active = null, string $searchText = '')
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('GET', 'departments', $parameters);
    }

    /**
     * Returns a list of event types.
     *
     * @param int    $page
     * @param int    $pageSize
     * @param bool   $active
     * @param string $searchText
     * @param bool   $displayOnWeb
     * @param bool   $allowWebRequest
     *
     * @return mixed
     */
    public function getEventTypes(int $page = null, int $pageSize = null, bool $active = null, string $searchText = '', bool $displayOnWeb = null, bool $allowWebRequest = null)
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('GET', 'eventtypes', $parameters);
    }

    /**
     * Return a Reservation, given its id.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getReservation(int $id)
    {
        return $this->request('GET', 'reservations/'.$id);
    }

    /**
     * Return a list of all configured statuses.
     *
     * @param int $page
     * @param int $pageSize
     *
     * @return mixed
     */
    public function getStatuses(int $page = null, int $pageSize = null)
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('GET', 'statuses', $parameters);
    }

    /**
     * Return list of web users filtered by optional request payload parameters.
     *
     * @param int    $page
     * @param int    $pageSize
     * @param string $searchText
     *
     * @return mixed
     */
    public function getWebUsers(int $page = null, int $pageSize = null, string $searchText = '')
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('POST', 'webusers/actions/search', $parameters);
    }

    /**
     * Return a list of bookings, filtered by optional request payload parameters.
     *
     * Return a list of bookings, filtered by optional request payload parameters. If
     * 'webUserId' is not specified, and a user authentication token is provided, then
     * the user id associated with the token will be used for webUserId. If 'userBookingsOnly'
     * is omitted, it will default to 'true' for user tokens, and default to 'false' for
     * client tokens. Results are sorted by eventStartTime ascending.
     *
     * @param int    $page
     * @param int    $pageSize
     * @param array  $buildingIds
     * @param array  $eventTypeIds
     * @param bool   $excludeOverrideDescriptionRooms
     * @param array  $floorIds
     * @param array  $groupIds
     * @param bool   $includeCancelled
     * @param bool   $includeComponentRooms
     * @param int    $kioskProfileId
     * @param string $maxReserveStartTime
     * @param string $minReserveStartTime
     * @param string $originalEventStartTime
     * @param string $reservationId
     * @param array  $reservationIds
     * @param array  $roomIds
     * @param array  $roomTypeIds
     * @param string $searchText
     * @param array  $statusIds
     * @param array  $udfSearch
     * @param bool   $userBookingsOnly
     * @param string $webUserId
     *
     * @return mixed
     */
    public function searchBookings(int $page = null, int $pageSize = null, array $buildingIds = [], array $eventTypeIds = [], bool $excludeOverrideDescriptionRooms = null, array $floorIds = [], array $groupIds = [], bool $includeCancelled = null, bool $includeComponentRooms = null, int $kioskProfileId = null, $maxReserveStartTime = '', $minReserveStartTime = '', $originalEventStartTime = '', $reservationId = '', $reservationIds = [], $roomIds = [], $roomTypeIds = [], $searchText = '', $statusIds = [], $udfSearch = [], bool $userBookingsOnly = null, $webUserId = '')
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('POST', 'bookings/actions/search', $parameters);
    }

    /**
     * Primary request function.
     *
     * @param string $method
     * @param string $request
     * @param array  $parameters
     *
     * @throws EmsException
     *
     * @return mixed
     */
    public function request(string $method, string $request, $parameters = [])
    {
        try {
            if ($request != 'clientauthentication' && $this->clientToken == '') {
                throw new EmsException("[{$request}] clientauthentication must be called first and a valid token must be returned.");
            }

            $parms['json'] = $parms['query'] = [];

            if ((isset($parameters['pageSize']) && $parameters['pageSize'] == '') || !isset($parameters['pageSize'])) {
                $parameters['pageSize'] = $this->defaultPageSize;
            }

            switch ($method) {
                case 'POST':
                    $parms['json'] = $parameters;
                    if (isset($parameters['page'])) {
                        $parms['query']['page'] = $parameters['page'];
                        unset($parms['json']['page']);
                    }
                    if (isset($parameters['pageSize'])) {
                        $parms['query']['pageSize'] = $parameters['pageSize'];
                        unset($parms['json']['pageSize']);
                    }
                    break;
                case 'GET':
                    $parms['query'] = $parameters;
                    break;
                default: throw new EmsException("[{$method}] Invalid request method.");
            }

            //Remove blank values
            foreach ($parms['json'] as $key => $val) {
                if (!is_array($val) && ($val === '' || $val === null)) {
                    unset($parms['json'][$key]);
                }
            }

            $res = $this->guzzleClient->request(
                    $method,
                    $request,
                    [
                                    'json'    => $parms['json'],
                                    'query'   => $parms['query'],
                                    'headers' => [
                                                    'x-ems-api-token' => $this->clientToken,
                                    ],
                                    'debug' => $this->debugMode,
                    ]
                    );

            if (is_object($res->getBody())) {
                $json = $res->getBody()->getContents();
            } else {
                $json = $res->getBody();
            }

            return json_decode($json);
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            $msg = $e->getResponse()->getBody()->getContents();
            $json = json_decode($msg);
            if ($this->debugMode) {
                $msg = '<pre>'.htmlentities(print_r($parms, true)).print_r($json, true).'</pre>';
            } else {
                $msg = $json->message;
            }

            throw new EmsException($msg);
        }
    }

    /**
     * @return the $clientToken
     */
    public function getClientToken()
    {
        return $this->clientToken;
    }

    /**
     * @return the $debugMode
     */
    public function getDebugMode()
    {
        return $this->debugMode;
    }

    /**
     * @return the $defaultPageSize
     */
    public function getDefaultPageSize()
    {
        return $this->defaultPageSize;
    }

    /**
     * @param bool $debugMode
     */
    public function setDebugMode($debugMode)
    {
        $this->debugMode = ($debugMode) ? true : false;

        return $this;
    }

    /**
     * @param number $defaultPageSize
     */
    public function setDefaultPageSize($defaultPageSize)
    {
        $this->defaultPageSize = intval($defaultPageSize);

        return $this;
    }

    public function processParameters($method, $args)
    {
        $reflection = new \ReflectionClass($this);
        $methods = $reflection->getMethods();
        $parameters = $reflection->getMethod('searchBookings')->getParameters();

        $returns = [];
        foreach ($args as $key => $val) {
            $argName = $parameters[$key]->name;
            $returns[$argName] = $val;
        }

        return $returns;
    }
}

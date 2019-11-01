<?php

namespace EmsPlatform;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Psr7;

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
     * Creates a reservation with bookings.
     *
     * https://scheduling.nebraska.edu/EmsPlatform/api/v1/static/swagger-ui/#tag/Reservations%2Fpaths%2F~1reservations~1actions~1create%2Fpost
     *
     * @param  bool   $sendConfirmation
     * @param  int    $actualAttendance
     * @param  int    $altContactId
     * @param  string $altEmailAddress
     * @param  string $altFax
     * @param  string $altPhone
     * @param  string $altTempContact
     * @param  string $billingReference
     * @param  array  $bookings
     * @param  array  $calendaringData
     * @param  string $comment
     * @param  int    $commentId
     * @param  string $emailAddress
     * @param  int    $estimatedAttendance
     * @param  int    $eventTypeId
     * @param  string $eventname
     * @param  string $fax
     * @param  int    $groupId
     * @param  string $phone
     * @param  string $poNumber
     * @param  int    $processTemplateId
     * @param  array  $serviceOrders
     * @param  int    $statusId
     * @param  string $tempContact
     * @param  string $tempRoomDescription
     * @param  array  $userDefinedFields
     * @param  bool   $vip
     *
     * @return mixed
     */
    public function createReservation(bool $sendConfirmation = false, int $actualAttendance = null, int $altContactId = null, string $altEmailAddress = '', string $altFax = '', string $altPhone = '', string $altTempContact = '', string $billingReference = '', array $bookings = [], array $calendaringData = [], string $comment = '', int $commentId = null, string $emailAddress = '', int $estimatedAttendance = null, int $eventTypeId = null, string $eventname = '', string $fax = '', int $groupId = null, string $phone = '', string $poNumber = '', int $processTemplateId = null, array $serviceOrders = [], int $statusId = null, string $tempContact = '', string $tempRoomDescription = '', array $userDefinedFields = [], bool $vip = null)
    {
        if (count($calendaringData) == 0) {
            unset($calendaringData);
        }
        if (count($serviceOrders) == 0) {
            unset($serviceOrders);
        }

        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('POST', 'reservations/actions/create', $parameters);
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
     * Returns a list of groups.
     *
     * @param  int    $page
     * @param  int    $pageSize
     * @param  bool   $active
     * @param  bool   $displayOnWeb
     * @param  int    $webUserId
     * @param  string $searchText
     * @param  string $groupName
     * @param  string $emailAddress
     * @param  string $badgeNumber
     * @param  string $externalReference
     * @param  int    $networkId
     * @param  string $personnelNumber
     * @param  string $city
     *
     * @return mixed
     */
    public function getGroups(int $page = null, int $pageSize = null, bool $active = null, bool $displayOnWeb = null, int $webUserId = null, string $searchText = '', string $groupName = '', string $emailAddress = '', string $badgeNumber = '', string $externalReference = '', string $networkId = '', string $personnelNumber = '', string $city = '')
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('GET', 'groups', $parameters);
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
     * Return a list of rooms, filtered by optional query string parameters.
     *
     * @param  int    $page
     * @param  int    $pageSize
     * @param  string $searchText
     * @param  bool   $favorite
     * @param  bool   $bookable
     * @param  int    $webUserId
     * @param  int    $webTemplateId
     * @param  int    $buildingId
     * @param  int    $bookViewId
     * @param  int    $roomTypeId
     * @param  int    $floorId
     * @param  string $code
     * @param  string $externalReference
     * @param  int    $capacity
     * @param  bool   $includeSyncMailboxes
     * @param  string $udf
     *
     * @return mixed
     */
    public function getRooms(int $page = null, int $pageSize = null, string $searchText = '', bool $favorite = null, bool $bookable = null, int $webUserId = null, int $webTemplateId = null, int $buildingId = null, int $bookViewId = null, int $roomTypeId = null, int $floorId = null, string $code = '', string $externalReference = '', int $capacity = null, bool $includeSyncMailboxes = null, string $udf = '')
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('GET', 'rooms', $parameters);
    }

    /**
     * Return a list of all setup types.
     *
     * @param int    $page
     * @param int    $pageSize
     * @param bool   $active
     * @param string $searchText
     * @param int    $webTemplateId
     * @param int    $facilityId
     *
     * @return mixed
     */
    public function getSetupTypes(int $page = null, int $pageSize = null, bool $active = true, string $searchText = '', int $webTemplateId = null, int $facilityId = null)
    {
        $parameters = $this->processParameters(__FUNCTION__, func_get_args());

        return $this->request('GET', 'setuptypes', $parameters);
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

        //Make sure all array elements are integers
        foreach ($parameters as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $key2 => $val2) {
                    $parameters[ $key ][ $key2 ] = intval($val2);
                }
            }
        }

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
                    if (isset($parameters['sendConfirmation'])) {
                        $parms['query']['sendConfirmation'] = $parameters['sendConfirmation'];
                        unset($parms['json']['sendConfirmation']);
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
            if ($this->debugMode) {
                $msg = '<pre>'.htmlentities(print_r($parms, true))."\n". Psr7\str($e->getRequest())."\n".Psr7\str($e->getResponse()).'</pre>';
            } else {
                $json = json_decode($msg);
                if ( json_last_error() === JSON_ERROR_NONE ) {
                    $msg = $json->message;
                } else {
                    $o = $this->jsonSplitObjects($msg);
                    $json = json_decode($o[0]);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $msg = $json->appMessage;
                    }
                }
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

    function jsonSplitObjects($json)
    {
        $q = FALSE;
        $len = strlen($json);
        for($l=$c=$i=0;$i<$len;$i++)
        {   
            $json[$i] == '"' && ($i>0?$json[$i-1]:'') != '\\' && $q = !$q;
            if(!$q && in_array($json[$i], array(" ", "\r", "\n", "\t"))){continue;}
            in_array($json[$i], array('{', '[')) && !$q && $l++;
            in_array($json[$i], array('}', ']')) && !$q && $l--;
            (isset($objects[$c]) && $objects[$c] .= $json[$i]) || $objects[$c] = $json[$i];
            $c += ($l == 0);
        }   
        return $objects;
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
        $parameters = $reflection->getMethod($method)->getParameters();

        $returns = [];
        foreach ($args as $key => $val) {
            $argName = $parameters[$key]->name;
            $returns[$argName] = $val;
        }

        return $returns;
    }
}

<?php

namespace App\Services;

use Google_Client;
use Google_Service_MyBusiness;

class GoogleService
{
    protected Google_Client $client;
    protected Google_Service_MyBusiness $service;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('app/private/google/business-profile.json'));
        $this->client->addScope('https://www.googleapis.com/auth/business.manage');
        $this->service = new Google_Service_MyBusiness($this->client);
    }

    public function getFirstLocationReviews(): array
    {
        $accounts = $this->service->accounts->listAccounts();
        $account = $accounts->getAccounts()[0] ?? null;

        if (!$account) {
            return ['error' => 'No account found'];
        }

        $accountId = $account->getName(); // e.g., 'accounts/123456789'

        // You must fetch the list of locations to get the locationId
        $locations = $this->service->accounts_locations->listAccountsLocations($accountId);
        $location = $locations->getLocations()[0] ?? null;

        if (!$location) {
            return ['error' => 'No location found for account'];
        }

        $locationId = $location->getName(); // e.g., 'accounts/123456789/locations/987654321'

        // Now get the reviews
        $reviews = $this->service->accounts_locations_reviews->listAccountsLocationsReviews($locationId);

        return $reviews->toSimpleObject();
    }
}

<?php

namespace App\Repository\Eloquent;

use App\Models\MasterDeveloperApp;
use App\Models\MasterPartner;
use App\Models\MasterService;
use App\Models\MasterUserAuthentication;
use App\Repository\AuthenticationRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Hash;

class AuthenticationRepository implements AuthenticationRepositoryInterface
{
    // private $authBaseUrl = 'http://10.34.232.5:8080';
    private $authBaseUrl = 'http://10.34.52.5:8080';
    private $roleBaseUrl = 'http://10.34.52.5:8080';
    private $base64Credential = 'b3JnYWRtaW5AaW5kb3NhdG9vcmVkb28uY29tOkphazIwMTch';

    public function signIn($email, $password)
    {
        try {
            $client = new Client();
            $request = $client->request('GET', $this->authBaseUrl . '/v1/organizations/indosatooredoo', [
                'auth' => [$email, $password]
            ]);

            $responseStatus = $request->getStatusCode();

            if ($responseStatus == 200) {
                return $this->checkRole($email, $password);
            } else {
                $response = json_encode(array('status' => false, 'body' => 'Invalid credential'), JSON_FORCE_OBJECT);

                return $response;
            }
        } catch (BadResponseException $e) {
            $error = $e->getResponse()->getBody();
            $response = json_encode(array('status' => false, 'body' => 'Invalid credential ' . $error), JSON_FORCE_OBJECT);

            return $response;
        }
    }

    public function checkRole($email, $password)
    {
        try {
            $isAdmin = json_decode($this->isAdmin($email));
            $isBusiness = json_decode($this->isBusiness($email));
            $isOrgAdmin = json_decode($this->isOrgAdmin($email));

            session()->put('admin', $isAdmin->body);
            session()->put('business', $isBusiness->body);
            session()->put('orgAdmin', $isOrgAdmin->body);

            if ($isAdmin->status == 200 && $isAdmin->body == true) {
                $services = MasterService::orderBy('service_name', 'asc')->get();

                $servicesMenu = $this->generateServices($services);
                $adminMenu = $this->generateAdminMenu();

                session()->put('signedIn', true);
                session()->put('user', $email);
                session()->put('services', $services);
                session()->put('servicesMenu', $servicesMenu);
                session()->put('adminMenu', $adminMenu);
                session()->put('partner', 'Indosat Ooredoo');

                $response = json_encode(array('status' => true, 'body' => 'success'), JSON_FORCE_OBJECT);

                $this->registerExisting($email, $password, 1, 'Registered');

                return $response;
            } elseif ($isAdmin->status == 401) {
                $response = json_encode(array('status' => false, 'body' => 'Invalid credential'), JSON_FORCE_OBJECT);

                return $response;
            } elseif ($isAdmin->status == 404) {
                // Check if users has other admin role
                if ($isBusiness->body || $isOrgAdmin->body) {
                    // Is admin
                    $partner = MasterPartner::where('partner_email', $email)->first();

                    if ($partner && $partner->additional_role == 1) {
                        $developerApps = MasterDeveloperApp::where('additional_role_id', 'like', '%|' . $partner->partner_id . '|%')
                            ->orderBy('developer_app_name', 'asc')
                            ->get();

                        $developerApp = array();
                        foreach ($developerApps as $app) {
                            array_push($developerApp, $app->developer_app_name);
                        }

                        $services = DB::connection('mysql_2')->table('master_service')
                            ->join('master_developer_app', 'master_developer_app.service_id', '=', 'master_service.service_id')
                            ->whereIn('developer_app_name', $developerApp)
                            ->orderBy('service_name', 'asc')
                            ->get()
                            ->unique('service_name');

                        $this->registerExisting($email, $password, 3, 'Registered');
                    } else {
                        $services = MasterService::orderBy('service_name', 'asc')->get();

                        $adminMenu = $this->generateAdminMenu();
                        session()->put('adminMenu', $adminMenu);

                        $this->registerExisting($email, $password, 2, 'Registered');
                    }

                    $servicesMenu = $this->generateServices($services);

                    session()->put('admin', true);
                    session()->put('partner', 'Indosat Ooredoo');
                    session()->put('services', $services);
                    session()->put('servicesMenu', $servicesMenu);
                } else {
                    // Is not admin
                    $partner = MasterPartner::where('partner_email', $email)->first();

                    $services = DB::connection('mysql_2')->table('master_service')
                        ->join('master_developer_app', 'master_developer_app.service_id', '=', 'master_service.service_id')
                        ->where('partner_id', $partner->partner_id)
                        ->orderBy('service_name', 'asc')
                        ->get()
                        ->unique('service_name');

                    $servicesMenu = $this->generateServices($services);

                    session()->put('partner', $partner->partner_name);
                    session()->put('services', $services);
                    session()->put('servicesMenu', $servicesMenu);

                    $this->registerExisting($email, $password, 4, 'Registered');
                }

                session()->put('signedIn', true);
                session()->put('user', $email);

                $response = json_encode(array('status' => true, 'body' => 'success'), JSON_FORCE_OBJECT);

                return $response;
            } else {
                if ($isAdmin->status == 'error') {
                    $response = json_encode(array('status' => false, 'body' => 'Error ' . $isAdmin->body), JSON_FORCE_OBJECT);

                    return $response;
                }

                if ($isBusiness->status == 'error') {
                    $response = json_encode(array('status' => false, 'body' => 'Error ' . $isBusiness->body), JSON_FORCE_OBJECT);

                    return $response;
                }

                if ($isOrgAdmin->status == 'error') {
                    $response = json_encode(array('status' => false, 'body' => 'Error ' . $isOrgAdmin->body), JSON_FORCE_OBJECT);

                    return $response;
                }

                $response = json_encode(array('status' => false, 'body' => 'Error'), JSON_FORCE_OBJECT);

                return $response;
            }
        } catch (BadResponseException $e) {
            $error = $e->getResponse()->getBody();
            $response = json_encode(array('status' => false, 'body' => 'Error ' . $error), JSON_FORCE_OBJECT);

            return $response;
        }
    }

    public function isAdmin($email)
    {
        try {
            $client = new Client();
            $request = $client->request('GET', $this->roleBaseUrl . '/v1/o/indosatooredoo/userroles/opsadmin/users/' . $email, [
                'http_errors' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . $this->base64Credential,
                ]
            ]);

            $responseStatus = $request->getStatusCode();
            $responseBody = $request->getBody();

            if ($responseStatus == 200) {
                $response = json_encode(array('status' => $responseStatus, 'body' => true), JSON_FORCE_OBJECT);

                return $response;
            } else {
                $response = json_encode(array('status' => $responseStatus, 'body' => false), JSON_FORCE_OBJECT);

                return $response;
            }
        } catch (BadResponseException $e) {
            $error = $e->getResponse()->getBody();
            $response = json_encode(array('status' => 'error', 'body' => 'Error ' . $error), JSON_FORCE_OBJECT);

            return $response;
        }
    }

    public function isBusiness($email)
    {
        try {
            $client = new Client();
            $request = $client->request('GET', $this->roleBaseUrl . '/v1/o/indosatooredoo/userroles/businessuser/users/' . $email, [
                'http_errors' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . $this->base64Credential,
                ]
            ]);

            $responseStatus = $request->getStatusCode();

            if ($responseStatus == 200) {
                $response = json_encode(array('status' => $responseStatus, 'body' => true), JSON_FORCE_OBJECT);

                return $response;
            } else {
                $response = json_encode(array('status' => $responseStatus, 'body' => false), JSON_FORCE_OBJECT);

                return $response;
            }
        } catch (BadResponseException $e) {
            $error = $e->getResponse()->getBody();
            $response = json_encode(array('status' => 'error', 'body' => 'Error ' . $error), JSON_FORCE_OBJECT);

            return $response;
        }
    }

    public function isOrgAdmin($email)
    {
        try {
            $client = new Client();
            $request = $client->request('GET', $this->roleBaseUrl . '/v1/o/indosatooredoo/userroles/orgadmin/users/' . $email, [
                'http_errors' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . $this->base64Credential,
                ]
            ]);

            $responseStatus = $request->getStatusCode();

            if ($responseStatus == 200) {
                $response = json_encode(array('status' => $responseStatus, 'body' => true), JSON_FORCE_OBJECT);

                return $response;
            } else {
                $response = json_encode(array('status' => $responseStatus, 'body' => false), JSON_FORCE_OBJECT);

                return $response;
            }
        } catch (BadResponseException $e) {
            $error = $e->getResponse()->getBody();
            $response = json_encode(array('status' => 'error', 'body' => 'Error ' . $error), JSON_FORCE_OBJECT);

            return $response;
        }
    }

    public function registerExisting($email, $password, $roleId, $status)
    {
        try {
            $masterUser = MasterUserAuthentication::on('mysql')->firstOrCreate(
                [
                    'email' => $email,
                ],
                [
                    'password' => Hash::make($password),
                    'role_id' => $roleId,
                    'status' => $status
                ]
            );

            return $masterUser;
        } catch (Exception $e) {
            $response = json_encode(array('status' => 'error', 'body' => 'Error ' . $e), JSON_FORCE_OBJECT);

            return $response;
        }
    }

    public function signOut()
    {
        session()->flush();

        $response = json_encode(array('status' => true, 'body' => 'Signed out'), JSON_FORCE_OBJECT);

        return $response;
    }

    public function generateServices($services)
    {
        $response = array();
        foreach ($services as $key => $service) {
            $response[$key]['title'] = $service->service_name;
            $response[$key]['icon'] = 'mdi-account-multiple-outline';
            $response[$key]['url'] = '/service/' . $service->path;
        }

        return $response;
    }

    public function generateAdminMenu()
    {
        $response = array();
        $response[0]['title'] = 'Work Order';
        $response[0]['icon'] = 'mdi-account-multiple-outline';
        $response[0]['url'] = '/workorder';

        $response[1]['title'] = 'Partner';
        $response[1]['icon'] = 'mdi-account-multiple-outline';
        $response[1]['url'] = '/partner';

        $response[2]['title'] = 'Wallet Expiry';
        $response[2]['icon'] = 'mdi-account-multiple-outline';
        $response[2]['url'] = '/wallet/digitalReward/expiredInfo';

        return $response;
    }
}

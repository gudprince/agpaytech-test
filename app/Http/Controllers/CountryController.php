<?php

namespace App\Http\Controllers;

use Framework\Routing\Router;
use Framework\Database\DatabaseTable;
use Framework\Database\MysqlConnection;
use App\Traits\Helper;

class CountryController
{
    use Helper;

    private $router;
    private $pdo;
    private $currenciesTable;
    private $countriesTable;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->pdo = (new MysqlConnection())->connect();
        $this->countriesTable = new DatabaseTable($this->pdo, 'countries', 'id');
        $this->currenciesTable = new DatabaseTable($this->pdo, 'currencies', 'id');
    }

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * 10;
        $keyword = $_GET['officialName'] ?? null;
        $orderBy = 'official_name ASC';
        $searchColumn = 'official_name';

        $countries = $this->countriesTable->findAll(10, $offset, $orderBy, $keyword, $searchColumn);

        $response = [];
        foreach ($countries as $country) {
            $currency = $this->currenciesTable->find('iso_code', $country['currency_code']);
            $country['currency'] = $currency;
            $response[] = $country;
        }
        $message = 'Countries Retrieved Successfully';

        return $this->jsonResponse(200, $message, $response);
    }

    public function find()
    {
        $parameters = $this->router->current()->parameters();
        $country_id = $parameters['id'];
        $country = $this->countriesTable->findById($country_id);
        if ($country) {
            $message = 'Country Retrieved Successfully';

            return $this->jsonResponse(200, $message, $country);
        } else {
            $message = 'Not Found';

            return $this->jsonResponse(404, $message);
        }
    }

    public function save()
    {
        try {
            // $currency = json_decode($_POST['currency']);
            if (!empty($_FILES['country']['tmp_name'])) {
                $check1 = $this->validateFile($_FILES['country']);
                $check2 = $this->validateCountryCsvHeader($_FILES['country']['tmp_name']);

                if ($check1 === false) {
                    return $this->jsonResponse(422, 'The file format must be CSV');
                } else if ($check2 === false) {
                    return $this->jsonResponse(422, 'Invalid CSV header');
                } else {
                    $params = $this->extractCsvFile($_FILES['country']['tmp_name']);

                    foreach ($params as $param) {
                        $country = $this->countriesTable->find('official_name', $param['common_name']);
                        if (!$country) {
                            $this->countriesTable->save($param);
                        }
                    }
                    $message = 'Country Saved Successfully';

                    return $this->jsonResponse(201, $message);
                }
            } else {
                $message = 'country field is required and expect a CSV file';

                return $this->jsonResponse(422, $message);
            }
        } catch (\Exception $e) {

            return $this->jsonResponse(500, $e->getMessage());
        }
    }
}

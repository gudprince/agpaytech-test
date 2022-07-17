<?php

namespace App\Http\Controllers;

use Framework\Routing\Router;
use Framework\Database\DatabaseTable;
use Framework\Database\MysqlConnection;
use App\Traits\Helper;

class CurrencyController
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
        $this->currenciesTable = new DatabaseTable($this->pdo, 'currencies', 'id');
        $this->countriesTable = new DatabaseTable($this->pdo, 'countries', 'id');
    }

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * 10;
        $keyword = $_GET['officialName'] ?? null;
        $orderBy = 'official_name ASC';
        $searchColumn = 'official_name';

        $currencies = $this->currenciesTable->findAll(10, $offset, $orderBy, $keyword, $searchColumn);

        $response = [];
        foreach ($currencies as $currency) {
            $country = $this->countriesTable->find('currency_code', $currency['iso_code']);
            $currency['country'] = $country;
            $response[] = $currency;
        }

        $message = 'Currencies Retrieved Successfully';

        return $this->jsonResponse(200, $message, $response);
    }

    public function find()
    {
        $parameters = $this->router->current()->parameters();
        $currency_id = $parameters['id'];
        $currency = $this->currenciesTable->findById($currency_id);
        if ($currency) {
            $country = $this->countriesTable->find('currency_code', $currency['iso_code']);
            $currency['country'] =  $country;
            $message = 'Currency Retrieved Successfully';

            return $this->jsonResponse(200, $message, $currency);
        } else {
            $message = 'Not Found';

            return $this->jsonResponse(404, $message);
        }
    }

    public function save()
    {
        try {
            //$currency = $_POST['currency'];
            
            if (!empty($_FILES['currency']['tmp_name'])) {
                $check_1 = $this->validateFile($_FILES['currency']);
                $check_2 = $this->validateCurrencyCsvHeader($_FILES['currency']['tmp_name']);

                if ($check_1 === false) {
                    return $this->jsonResponse(422, 'The file format must be CSV');
                } else if ($check_2 === false) {
                    return $this->jsonResponse(422, 'Invalid CSV header');
                } else {
                    $params = $this->extractCsvFile($_FILES['currency']['tmp_name']);

                    foreach ($params as $param) {
                        $currency = $this->currenciesTable->find('official_name', $param['official_name']);
                        if (!$currency) {
                            $this->currenciesTable->save($param);
                        }
                    }
                    $message = 'Currency Saved Successfully';

                    return $this->jsonResponse(201, $message);
                }
            } else {
                $message = 'currency field is required and expect a CSV file';

                return $this->jsonResponse(422, $message);
            }
        } catch (\Exception $e) {

            return $this->jsonResponse(500, 'an error occured');
        }
    }
}

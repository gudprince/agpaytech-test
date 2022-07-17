<?php

namespace App\Traits;

trait Helper
{

    public function validateFile($file)
    {
        // Allowed mime types
        $fileMimes = array(
            'application/vnd.ms-excel',
            'application/x-csv',
            'text/x-csv',
            'text/csv',
            'application/csv',
            'application/excel',

        );

        // Validate whether selected file is a CSV file
        if (!empty($file['name']) && in_array($file['type'], $fileMimes) == false) {
            return false;
        } else {
            true;
        }
    }

    public function jsonResponse($code = 200, $message = null, $data = null)
    {
        // clear the old headers
        header_remove();
        // set the actual code
        http_response_code($code);
        // set the header to make sure cache is forced
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        // treat this as json
        header('Content-Type: application/json');
        $status = array(
            200 => '200 OK',
            201 => 'Created',
            400 => '400 Bad Request',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error',
            404 => 'Not Found'
        );
        // ok, validation error, or failure
        header('Status: ' . $status[$code]);
        // return the encoded json
        return json_encode(array(
            'status' => $code < 300, // success or not?
            'message' => $message,
            'data' => $data
        ));
    }
    public function extractCsvFile($file)
    {
        // Open uploaded CSV file with read-only mode
        $csvFile = fopen($file, 'r');

        $header = Null;
        $params = [];

        // Parse data from CSV file line by line
        while (($row = fgetcsv($csvFile, 10000, ",")) !== FALSE) {
            // Get row data
            if (!$header) {
                $header = $row;
            } else {
                $params[] = array_combine($header, $row);
            }
        }
        // Close opened CSV file
        fclose($csvFile);

        return $params;
    }

    public function validateCurrencyCsvHeader($file)
    {   
        if(!$file){
            return false;
        }

        $csvFile = fopen($file, 'r');
        $csvHeaderColumn = fgetcsv($csvFile, 10000, ",");
        $currencyTableColumn = [
            'iso_code',
            'iso_numeric_code',
            'common_name',
            'official_name',
            'symbol',
        ];
        
        sort($csvHeaderColumn);
        sort($currencyTableColumn);

        if ($csvHeaderColumn ===  $currencyTableColumn) {
            return true;
        } else {
            return false;
        }
    }

    public function validateCountryCsvHeader($file)
    {   
        if(!$file){
            return false;
        }

        $csvFile = fopen($file, 'r');
        $csvHeaderColumn = fgetcsv($csvFile, 10000, ",");
        $countryTableColumn = [
            'continent_code',
            'currency_code',
            'iso2_code',
            'iso3_code',
            'iso_numeric_code',
            'fips_code',
            'calling_code',
            'common_name',
            'official_name',
            'endonym',
            'demonym',
        ];
        
        sort($csvHeaderColumn);
        sort($countryTableColumn);

        if ($csvHeaderColumn ===  $countryTableColumn) {
            return true;
        } else {
            return false;
        }
    }
}

<?php
namespace CPlusC\TestRefactoring;

/**
 * Main class for calculations transaction commissions
 *
 * @author Aleksey Sulzhenko <redlex@live.ru>
 */
class TransactionCommissions
{
    const EU_CODE = array(
        "AT",
        "BE",
        "BG",
        "CY",
        "CZ",
        "DE",
        "DK",
        "EE",
        "ES",
        "FI",
        "FR",
        "GR",
        "HR",
        "HU",
        "IE",
        "IT",
        "LT",
        "LU",
        "LV",
        "MT",
        "NL",
        "PO",
        "PT",
        "RO",
        "SE",
        "SI",
        "SK"
    );
    public string $inputFile;

    public function __construct($inputFile)
    {
        $this->inputFile = $inputFile;
        if (empty($this->inputFile)) {
            throw new Exception("Error: Specify the source file");
        }
        if (!file_exists($this->inputFile)) {
            throw new Exception("Error: Source file does not exist");
        }
    }

    /**
     *
     * Main method calculation transaction commissions
     *
     */
    public function startCalculation(): void
    {
        $lines = file($this->inputFile);
        foreach ($lines as $line_num => $line) {
            $data = json_decode($line);
            $countryCode = $this->getCountryCode((int)$data->bin, $line_num);
            $isEu = in_array($countryCode, self::EU_CODE) ? true : false;
            $rate = $this->getExchangeRate($data->currency);
            $amntFixed = $rate == 0 ? $result = (int)$data->amount : (int)$data->amount / $rate;
            echo round($amntFixed * ($isEu ? 0.01 : 0.02), 2) . "\n";
        }
    }

    /**
     *
     * Gets the country code from lookup.binlist.net service
     *
     * @param int $bin BIN number represents first digits of credit card number
     * @param int $line_num line number in file
     * @return string
     */
    private function getCountryCode(int $bin, int $line_num): string
    {
        /*if (empty($this->inputFile)) {
            throw new Exception("Error: «bin» is empty on line {$line_num}");
        }*/
        $binResults = file_get_contents('https://lookup.binlist.net/' . $bin);
        /*if (empty($binResults)) {
            throw new Exception("Error: Service for country code return NULL, send bin = {$bin} on line {$line_num}");
        }*/
        $binObject = @json_decode($binResults);
        return $binObject->country->alpha2 ?? "";
    }

    /**
     *
     * Get exchange rates from exchangeratesapi.io service
     *
     * @param string $currency
     * @return int
     */
    private function getExchangeRate(string $currency): int
    {
        if ($currency == 'EUR') {
            return 0;
        }
        $jsonFromService = file_get_contents('https://api.exchangeratesapi.io/latest');
        /*if (empty($jsonFromService)) {
            throw new Exception("Error: Service exchange rates unable");
        }*/
        $result = @json_decode($jsonFromService, true);
        /*if (empty($result['rates'][$currency])) {
            throw new Exception("Error: Rate {$currency} empty form service «exchangeratesapi»");
        }*/
        return $result['rates'][$currency] ?? 0;
    }

}
<?php
class Kinopoisk {
    private $ch;
    private $months = [
        "JANUARY",
        "FEBRUARY",
        "MARCH",
        "APRIL",
        "MAY",
        "JUNE",
        "JULY",
        "AUGUST",
        "SEPTEMBER",
        "OCTOBER",
        "NOVEMBER",
        "DECEMBER",
    ];

    public function __construct() {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
            "accept: application/json",
            "X-API-KEY: 2ac8fc59-ae8e-45be-a83a-6f386743c7a8"
        ));
	}

	public function __destruct() {
		curl_close($this->ch);
    }
    
    private function get($url) {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $data = curl_exec($this->ch);
        if ($data === false) {
            return null;
        }
        return json_decode($data, true);
    }

    private function concatValFromArr($arr) {
        $n = [];
        foreach($arr as $value) {
            $n[] = implode(", ", array_values($value));
        }
        return implode(", ", $n);
    }
    
    public function getPremiere($year, $month) {
        $url =
            "https://kinopoiskapiunofficial.tech/api/v2.2/films/premieres?year=" .
            $year .
            "&month=" . 
            $this->months[--$month];
        $data = $this->get($url);
        $arr = [];
        if ($data !== null) {
            foreach ($data["items"] as $value) {
                if (!array_key_exists($value["premiereRu"], $arr)) {
                    $arr[$value["premiereRu"]] = [];
                }
                $arr[$value["premiereRu"]][] = [
                    "kinopoiskId" => $value["kinopoiskId"],
                    "nameRu" => $value["nameRu"],
                    "nameEn" => $value["nameEn"],
                    "year" => $value["year"],
                    "posterUrlPreview" => $value["posterUrlPreview"],
                    "countries" => $this->concatValFromArr($value["countries"]),
                    "genres" => $this->concatValFromArr($value["genres"]),
                    "duration" => $value["duration"],
                ];
            }
        }
        return $arr;
    }
    
    public function getFilm($id) {
        $url =
            "https://kinopoiskapiunofficial.tech/api/v2.2/films/" .
            $id;
        $data = $this->get($url);
        if ($data === null) {
            return [];
        }
        $data["countries"] = $this->concatValFromArr($data["countries"]);
        $data["genres"] = $this->concatValFromArr($data["genres"]);
        return $data;
    }
    
    public function getFind($findstr, $page) {
        $url =
            "https://kinopoiskapiunofficial.tech/api/v2.1/films/search-by-keyword?keyword=" .
            urlencode($findstr) .
            "&page=" .
            $page;
        $data = $this->get($url);
        $arr = [];
        if ($data !== null) {
            foreach ($data["films"] as $value) {
                $arr[] = [
                    "filmId" => $value["filmId"],
                    "nameRu" => $value["nameRu"],
                    "nameEn" => $value["nameEn"],
                    "year" => $value["year"],
                    "posterUrlPreview" => $value["posterUrlPreview"],
                    "countries" => $this->concatValFromArr($value["countries"]),
                    "genres" => $this->concatValFromArr($value["genres"]),
                ];
            }
        }
        return [
            "keyword" => $data["keyword"],
            "pagesCount" => ($data["pagesCount"] > 20 ? 20 : $data["pagesCount"]),
            "films" => $arr,
        ];
    }
}
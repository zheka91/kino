<?php
class DB {
	private $pdo;

	public function __construct() {
		try {
			$this->pdo = new PDO("mysql:host=localhost;dbname=kino;charset=utf8", "root", "");
		} catch (PDOException $e) {
			echo json_encode([
				"error" => "Подключение не удалось: " . $e->getMessage(),
            ]);
		}
	}

	public function __destruct() {
		$this->pdo = null;
	}

	private function getAll($sql, $params = null) {
		$stmt = $this->pdo->prepare($sql);

		try {
			$stmt->execute($params);
		} catch (Exception $e) {
			header("HTTP/1.1 500 Internal Server Error");
			echo json_encode([
				"error" => $e->getMessage(),
            ]);
		}
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	private function save($sql, $params = null) {
		$stmt = $this->pdo->prepare($sql);

        try {
			$res = $stmt->execute($params);
		} catch (Exception $e) {
			header("HTTP/1.1 500 Internal Server Error");
			echo json_encode([
				"error" => $e->getMessage(),
            ]);
			exit();
		}
		return $res;
    }
    
    public function checkLogin($login) {
		$sql =
			"SELECT COUNT(id) as cnt
            FROM users
            WHERE login = :login";

		return $this->getAll($sql, [
			":login" => $login,
        ]);
	}

	public function addUser($login, $pass) {
		$sql =
			"INSERT INTO users (login, pass)
            VALUES (:login, SHA1(:pass));";

		return $this->save($sql, [
			":login" => $login,
			":pass" => $pass,
        ]);
	}

	public function addAuthUser($id, $data) {
		$sql =
			"INSERT INTO users_auth (users_id, servers)
			VALUES (:id, :data);";

		return $this->save($sql, [
			":id" => $id,
			":data" => json_encode($data),
        ]);
	}
    
    public function checkUser($login, $pass) {
		$sql =
			"SELECT id, login
            FROM users
            WHERE 1 = 1
                AND login = :login
                AND pass = SHA1(:pass)";

		return $this->getAll($sql, [
			":login" => $login,
			":pass" => $pass,
        ]);
	}
    
    public function getFilmseeIds($ids, $user) {
		$sql =
			"SELECT filmId, metka
			FROM filmsee
			WHERE 1 = 1
				AND users_id = :user
				AND filmId IN (" . implode(", ", $ids) . ")";

		return $this->getAll($sql, [
			":user" => $user,
        ]);
	}
    
    public function checkFilmseeId($id, $user) {
		$sql =
			"SELECT COUNT(id) AS cnt
			FROM filmsee
			WHERE 1 = 1
				AND users_id = :user
				AND filmId = :id";

		return $this->getAll($sql, [
			":user" => $user,
			":id" => $id,
        ]);
	}

	public function addFilmsee($userid, $metka, $data) {
		$sql =
			"INSERT INTO filmsee (users_id, metka, filmId, nameRu, nameEn, year, posterUrlPreview, countries, genres)
			VALUES (:users_id, :metka, :filmId, :nameRu, :nameEn, :year, :posterUrlPreview, :countries, :genres);";

		return $this->save($sql, [
			":users_id" => $userid,
			":metka" => $metka,
			":filmId" => $data["kinopoiskId"],
			":nameRu" => $data["nameRu"],
			":nameEn" => $data["nameEn"],
			":year" => $data["year"],
			":posterUrlPreview" => $data["posterUrlPreview"],
			":countries" => $data["countries"],
			":genres" => $data["genres"],
        ]);
	}

	public function updateFilmsee($userid, $metka, $data) {
		$sql =
			"UPDATE filmsee
			SET
				metka = :metka,
				nameRu = :nameRu,
				nameEn = :nameEn,
				year = :year,
				posterUrlPreview = :posterUrlPreview,
				countries = :countries,
				genres = :genres
			WHERE 1 = 1
				AND users_id = :users_id
				AND filmId = :filmId;";

		return $this->save($sql, [
			":users_id" => $userid,
			":metka" => $metka,
			":filmId" => $data["kinopoiskId"],
			":nameRu" => $data["nameRu"],
			":nameEn" => $data["nameEn"],
			":year" => $data["year"],
			":posterUrlPreview" => $data["posterUrlPreview"],
			":countries" => $data["countries"],
			":genres" => $data["genres"],
        ]);
	}

	public function deleteFilmsee($userid, $filmId) {
		$sql =
			"DELETE FROM filmsee 
			WHERE 1 = 1
				AND users_id = :users_id
				AND filmId = :filmId;";

		return $this->save($sql, [
			":users_id" => $userid,
			":filmId" => $filmId,
        ]);
	}
    
    public function getFilmseeMyMetka($user, $metka) {
		$sql =
			"SELECT metka, filmId, nameRu, nameEn, year, posterUrlPreview, countries, genres
			FROM filmsee
			WHERE 1 = 1
				AND users_id = :users_id
				AND metka = :metka
			ORDER BY IFNULL(updated_at, created_at) DESC;";

		return $this->getAll($sql, [
			":users_id" => $user,
			":metka" => $metka,
        ]);
	}
}

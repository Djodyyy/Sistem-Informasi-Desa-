<?php
class Database
{
  private static $instance = null;
  private $connection;

  private $host;
  private $username;
  private $password;
  private $database;

  private function __construct()
  {
    $config = require __DIR__ . '/config-db.php';

    $this->host = $config['host'];
    $this->username = $config['username'];
    $this->password = $config['password'];
    $this->database = $config['database'];

    try {
      mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
      $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
      $this->connection->set_charset("utf8mb4");
    } catch (mysqli_sql_exception $e) {
      $this->handleError($e);
    }
  }

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new Database();
    }
    return self::$instance->connection;
  }

  private function handleError(mysqli_sql_exception $e)
  {
    $errorMessage = match ($e->getCode()) {
      1045 => "Akses ditolak! Username/password salah.",
      1049 => "Database tidak ditemukan.",
      1064 => "Kesalahan sintaks SQL.",
      1146 => "Tabel tidak ditemukan.",
      default => "Kesalahan database: " . $e->getMessage(),
    };

    if (php_sapi_name() == "cli" || isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
      http_response_code(500);
      echo json_encode([
        "status" => 500,
        "icon" => "error",
        "title" => "Error!",
        "message" => 'Gagal terhubung ke database: ' . $errorMessage
      ]);
    } else {
      echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'Database Error!',
          text: `" . addslashes($errorMessage) . "`,
          showConfirmButton: true
        });
      </script>";
    }
    exit;
  }

  public function query($sql)
  {
    return $this->connection->query($sql);
  }
}

function dbConnect()
{
  return Database::getInstance();
}

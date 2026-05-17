<?php
declare(strict_types=1);

class Config
{
	protected const string DB_NAME_DEFAULT = 'faved';
	protected const string STORAGE_PATH = ROOT_DIR . '/storage';

	protected const string IMAGE_STORAGE_DIR_NAME = 'img';

	public static function getPDO(): PDO
	{
		$envPath = ROOT_DIR . '/.env';
		if (file_exists($envPath)) {
			$env = parse_ini_file($envPath);
			if (isset($env['DATABASE_URL'])) {
				$_SERVER['DATABASE_URL'] = $env['DATABASE_URL'];
			}
		}

		$databaseUrl = $_SERVER['DATABASE_URL'] ?? getenv('DATABASE_URL') ?? null;
		
		if (!$databaseUrl) {
			throw new Exception("DATABASE_URL not set in .env");
		}

		$parsed = parse_url($databaseUrl);
		$host = $parsed['host'] ?? 'localhost';
		$port = $parsed['port'] ?? 5432;
		$user = $parsed['user'] ?? '';
		$pass = $parsed['pass'] ?? '';
		$dbname = ltrim($parsed['path'] ?? '', '/');

		$dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";
		
		$pdo = new PDO($dsn, $user, $pass);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $pdo;
	}

	public static function getImageStoragePath(): string
	{
		return sprintf('%s/%s', self::STORAGE_PATH, self::IMAGE_STORAGE_DIR_NAME);
	}

	public static function getPasswordAlgo(): string
	{
		if (in_array(PASSWORD_ARGON2ID, password_algos())) {
			return PASSWORD_ARGON2ID;
		}
		return PASSWORD_DEFAULT;
	}

	public static function getSessionLifetime(): int
	{
		return 60 * 60 * 24 * 7; // 7 days
	}

	public static function getSessionCookieName(): string
	{
		return 'faved-session';
	}
}

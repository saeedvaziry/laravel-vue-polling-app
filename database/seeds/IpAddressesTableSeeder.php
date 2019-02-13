<?php

use Illuminate\Database\Seeder;

class IpAddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$ipsPath = storage_path('ips.csv');
        $pdo = \DB::connection()->getPdo();
        $pdo->exec("
			LOAD DATA LOCAL
				INFILE '" . $ipsPath . "'
			INTO TABLE
				`ip_addresses`
			FIELDS TERMINATED BY ','
			ENCLOSED BY '\"'
			LINES TERMINATED BY '\r\n'
			IGNORE 0 LINES;
        ");
    }
}

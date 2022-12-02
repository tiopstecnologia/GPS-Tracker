<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\SharedApp\Migration\MigrationAbstract;

return new class extends MigrationAbstract
{
    /**
     * @return void
     */
    public function up()
    {
        $this->delete();
        $this->keys();
    }

    /**
     * @return void
     */
    protected function delete()
    {
        $this->db()->statement('
            DELETE FROM `device_alarm`
            WHERE `device_id` NOT IN (
                SELECT `id`
                FROM `device`
            );
        ');

        $this->db()->statement('
            DELETE FROM `device_alarm_notification`
            WHERE `device_id` NOT IN (
                SELECT `id`
                FROM `device`
            );
        ');

        $this->db()->statement('
            DELETE FROM `device_alarm_notification`
            WHERE `device_alarm_id` NOT IN (
                SELECT `id`
                FROM `device_alarm`
            );
        ');
    }

    /**
     * @return void
     */
    protected function keys()
    {
        Schema::table('device_alarm', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'device');
        });

        Schema::table('device_alarm_notification', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'device');
            $this->foreignOnDeleteCascade($table, 'device_alarm');
            $this->foreignOnDeleteSetNull($table, 'position');
            $this->foreignOnDeleteSetNull($table, 'trip');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('device_alarm', function (Blueprint $table) {
            $this->tableDropForeign($table, 'device', 'fk_');
        });

        Schema::table('device_alarm_notification', function (Blueprint $table) {
            $this->tableDropForeign($table, 'device', 'fk_');
            $this->tableDropForeign($table, 'device_alarm', 'fk_');
            $this->tableDropForeign($table, 'position', 'fk_');
            $this->tableDropForeign($table, 'trip', 'fk_');
        });
    }
};
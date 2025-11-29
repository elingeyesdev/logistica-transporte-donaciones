<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
/**
 * Class Ubicacion
 *
 * @property $id_ubicacion
 * @property $latitud
 * @property $longitud
 * @property $zona
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ubicacion extends Model
{
     protected $table = 'ubicacion'; 
    protected $primaryKey = 'id_ubicacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['latitud', 'longitud', 'zona'];
    public function historialSeguimientos()
    {
        return $this->hasMany(\App\Models\HistorialSeguimientoDonacione::class, 'id_ubicacion', 'id_ubicacion');
    }

     public function getDireccionAttribute()
    {
        if (!$this->latitud || !$this->longitud) {
            return null;
        }

        try {
            $client = new Client([
                'timeout' => 5,
            ]);

            $response = $client->get('https://nominatim.openstreetmap.org/reverse', [
                'query' => [
                    'format'          => 'json',
                    'lat'             => $this->latitud,
                    'lon'             => $this->longitud,
                    'zoom'            => 16,
                    'addressdetails'  => 1,
                ],
                'headers' => [
                    'User-Agent' => 'AlasChiquitanasLogistica/1.0 (anagvillafanis@gmail.com)',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['display_name'] ?? null;

        } catch (\Throwable $e) {
            Log::warning('Error en reverse geocoding: '.$e->getMessage());
            return null;
        }
    }
}

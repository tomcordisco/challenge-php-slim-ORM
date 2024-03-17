// app/Models/Persona.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';
    protected $fillable = ['nombre', 'apellido', 'edad', 'telefono'];
}
